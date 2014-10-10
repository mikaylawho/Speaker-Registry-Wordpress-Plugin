<?php

class CrmSync {


	/**
	 * @return Array
	 */
	public static function getMembershipType() {
		global $MembershipType;
		if ( ! isset( $MembershipType ) ) {
			self::setMembershipType();
		}

		return $MembershipType;
	}

	/**
	 * @internal param mixed $MembershipType
	 */
	public static function setMembershipType() {
		civicrm_wp_initialize();
		global $MembershipType;
		$MembershipTypeDetails = civicrm_api( "MembershipType", "get", array(
			'version'    => '3',
			'sequential' => '1'
		), null );
		foreach ( $MembershipTypeDetails['values'] as $key => $values ) {
			$MemType[ $values['id'] ] = $values['name'];
		}
		if ( isset( $MemType ) ) {
			$MembershipType = $MemType;
		}

	}

	/**
	 * @return Array
	 */
	public static function getMembershipStatus() {
		global $MembershipStatus;
		if ( ! isset( $MembershipStatus ) ) {
			self::setMembershipStatus();
		}

		return $MembershipStatus;
	}

	/**
	 * @param mixed $MembershipStatus
	 */
	public static function setMembershipStatus() {
		civicrm_wp_initialize();
		global $MembershipStatus;
		$MembershipStatusDetails = civicrm_api( "MembershipStatus", "get", array(
			'version'    => '3',
			'sequential' => '1'
		), null );
		foreach ( $MembershipStatusDetails['values'] as $key => $values ) {
			$MemStatus[ $values['id'] ] = str_replace( ' ', '', $values['name'] );
		}
		if ( isset( $MemStatus ) ) {
			$MembershipStatus = $MemStatus;
		}
	}


	static function civi_member_sync() {
		civicrm_wp_initialize();
		$users = get_users();

		foreach ( $users as $user ) {
			$uid    = $user->ID;
			$email = $user->data->user_email;

			if ( empty( $uid ) ) {
				continue;
			}

			//Mikel -- Updated to use the Civicrm API, and to match CiviUsers with Wordpress users based on primary email
			//rather than id -- changing it back now that I think I can sync the id's

			$contact = self::get_civicrm_contacts_that_match_wordpress_users( $email );

			if ( isset( $contact ) ) {
				$cid = $contact['values']['0']['contact_id'];

				$userData = get_userdata( $uid );
				if ( ! empty( $userData ) ) {
					$currentRole = $userData->roles[0];
					//checking membership status and assign role
					self::member_check( $cid, $uid, $currentRole );
				}


			}
		}
	}

	/**
	 * @param $uemail
	 *
	 * @return array
	 * @throws CiviCRM_API3_Exception
	 */
	private static function get_civicrm_contacts_that_match_wordpress_users( $email ) {
		civicrm_wp_initialize();
		$contact = civicrm_api3( 'UFMatch', 'get', array(
			'sequential' => 1,
			'return'     => array( "uf_id", "contact_id", "uf_name" ),
			'uf_name'    => $email,
		) );

		return $contact;
	}

	/**
	 * @param $cid
	 */
	private static function get_civi_contact_membership_details( $cid ) {
		civicrm_wp_initialize();
		global $memStatusID, $membershipTypeID;
		$memDetails = civicrm_api3('Membership', 'get', array(
			'sequential' => 1,
			'return' => array("status_id", "membership_type_id"),
			'contact_id' => $cid,
		));
		if ( ! empty( $memDetails['values'] ) ) {
			foreach ( $memDetails['values'] as $key => $value ) {
				$memStatusID      = $value['status_id'];
				$membershipTypeID = $value['membership_type_id'];
			}
		}
	}


	/** function to check membership record and assign wordpress role based on the membership status
	 * input params
	 * #CiviCRM contactID
	 * #Wordpress UserID and
	 * #User Role *
	 *
	 * @param $contactID
	 * @param $currentUserID
	 * @param $current_user_role
	 *
	 * @return bool
	 */
	static function member_check( $contactID, $currentUserID, $current_user_role ) {
		civicrm_wp_initialize();

		global $membershipTypeID, $memStatusID;
		if ( $current_user_role != 'administrator' ) {
			//fetching membership details, setting $membershipTypeID and $memStatusID
			self::get_civi_contact_membership_details( $contactID );
			$memSyncRulesDetails = self::get_civi_sync_rules_by_member_type_id( $membershipTypeID );


			if ( ! empty( $memSyncRulesDetails ) ) {
				$current_rule = unserialize( $memSyncRulesDetails[0]->current_rule );
				//$expiry_rule  = unserialize( $memSyncRulesDetails[0]->expiry_rule ); unused variable
				//checking membership status
				if ( isset( $memStatusID ) && array_search( $memStatusID, $current_rule ) ) {
					$wp_role = strtolower( $memSyncRulesDetails[0]->wp_role );
					if ( $wp_role == $current_user_role ) {
						//do nothing
					} else {
						$wp_user_object = new WP_User( $currentUserID );
						$wp_user_object->set_role( "$wp_role" );
					}
				} else {
					$wp_user_object  = new WP_User( $currentUserID );
					$expired_wp_role = strtolower( $memSyncRulesDetails[0]->expire_wp_role );
					if ( ! empty( $expired_wp_role ) ) {
						$wp_user_object->set_role( "$expired_wp_role" );
					} else {
						$wp_user_object->set_role( "" );
					}
				}
			}
		}

		return true;
	}


	/**
	 * @return string
	 * @throws CiviCRM_API3_Exception
	 */
	static function import_civi_members_to_wordpress() {
		civicrm_wp_initialize();
		$return_message            = '';
		$account_creation_messages = '';

		$member_types_to_sync = array();

		$rules         = self::get_civi_sync_rules();
		$array_counter = 0;
		foreach ( $rules as $key => $value ) {
			$member_types_to_sync[ $array_counter ] = $value->civi_mem_type;
			$array_counter += 1;
		}

		//have them pull from the CiviSync rules
		$result_current_members = civicrm_api3( 'Membership', 'get', array(
			'sequential'         => 1,
			'return'             => array( "contact_id" ),
			'membership_type_id' => array(
				'IN' => $member_types_to_sync
			),
		) );

		$current_member_array = array();
		foreach ( $result_current_members['values'] as $key => $values ) {
			$current_member_array[] = $values['contact_id'];
		}


		$result_contacts = civicrm_api3( 'Contact', 'get', array(
			'sequential' => 1,
			'return'     => array( "first_name", "last_name", "id" ),
			'id'         => array( 'IN' => $current_member_array ),
		) );
		if ( isset( $result_contacts ) ) {
			foreach ( $result_contacts['values'] as $key => $values ) {
				$member_array[ $values['id'] ] = array( 'email'      => '',
				                                        'first_name' => $values['first_name'],
				                                        'last_name'  => $values['last_name']
				);
			}
		}

		$result_member_emails = civicrm_api3( 'Email', 'get', array(
			'return'     => array( "email", "contact_id" ),
			'contact_id' => array( 'IN' => $current_member_array ),
			'is_primary' => 1,
		) );
		if ( isset( $result_member_emails ) ) {
			foreach ( $result_member_emails['values'] as $key => $values ) {
				$member_array[ $values['contact_id'] ]['email'] = $values['email'];
			}
		}

		if ( isset( $member_array ) ) {
			$new_account_count = 0;


			//TODO: make this more efficient by filtering out the emails that already exist in Wordpress.
			foreach ( $member_array as $key => $values ) {

				//extract the alias part of the email for the new user's username
				$email    = $values['email'];
				$parts    = explode( "@", $email );
				$username = $parts[0];

				if ( null == username_exists( $username ) and !empty($email)  ) {
					$password = wp_generate_password( 12, true );
					$user_id  = wp_create_user( $username, $password, $email );

					if ( is_wp_error( $user_id ) ) {

						echo 'Error creating user ' . $username . ' / ' . $email . ':<br>' . $user_id->get_error_message();
						echo '<br>';

					} else {

						$account_creation_messages = $account_creation_messages . 'Created account for user ' . $username . ' ID: ' . $user_id . '. <br>';


						wp_update_user(
							array(
								'ID'         => $user_id,
								'nickname'   => $email,
								'first_name' => $values['first_name'],
								'last_name'  => $values['last_name'],
							)

						);

						//Update CiviCRM UFMatch record so that the new Wordpress user is appropriately connected to their Contact record in CiviCrm
						$user = get_user_by('id', $user_id);
						/*TODO: this line is not working. Need to research*/
						CRM_Core_BAO_UFMatch::synchronizeUFMatch( $user, $user->ID, $user->email, 'WordPress' );

						// Email the user
						wp_mail( $email, 'Welcome ' . $username . '!', ' Your Password: ' . $password );
						//echo 'Welcome ' . $username . '!', ' Your Password: ' . $password . ' sent to ' . $email . '<br>';

						$new_account_count += 1;
					}

				}
			}
			$return_message = $return_message . 'Total new accounts created: ' . $new_account_count . '<br>' . $account_creation_messages;
		}

		return $return_message;

	}

	//helper functions
	static function get_names_serialized( $values, $memArray ) {
		$memArray     = array_flip( $memArray );
		$current_rule = unserialize( $values );
		if ( empty( $current_rule ) ) {
			$current_rule = $values;
		}
		$current_roles = "";
		if ( ! empty( $current_rule ) ) {
			if ( is_array( $current_rule ) ) {
				foreach ( $current_rule as $ckey => $cvalue ) {
					$current_roles .= array_search( $ckey, $memArray ) . "<br>";
				}
			} else {
				$current_roles = array_search( $current_rule, $memArray ) . "<br>";
			}
		}

		return $current_roles;
	}

	static function get_names( $values, $memArray ) {
		$memArray     = array_flip( $memArray );
		$current_rule = $values;
		$current_role = "";

		if ( ! empty( $current_rule ) ) {
			$current_role = array_search( $current_rule, $memArray ) . "<br>";
		}

		return $current_role;
	}

	/**
	 * @param $wpdb
	 *
	 * @return
	 */
	static function get_civi_sync_rules() {
		global $wpdb;
		$tablename = $wpdb->prefix . 'civi_member_sync';
		//changed to %s from $tablename. The syntax was wrong for string replacement...
		//replaced "Select *" with column names
		$select = $wpdb->get_results( " SELECT id, wp_role, civi_mem_type, current_rule, expiry_rule, expire_wp_role FROM " . $tablename );

		return $select;
	}


	/**
	 * @param $wpdb
	 * @param $membershipTypeID
	 *
	 * @return mixed
	 */
	static function get_civi_sync_rules_by_member_type_id( $membershipTypeID ) {
		global $wpdb;
//fetching member sync association rule to the corsponding membership type
		$wpdb->civi_member_sync = $wpdb->prefix . 'civi_member_sync';
		$memSyncRulesDetails    = $wpdb->get_results( $wpdb->prepare( "SELECT id, wp_role, civi_mem_type, current_rule, expiry_rule, expire_wp_role FROM $wpdb->civi_member_sync WHERE `civi_mem_type`= %d", $membershipTypeID ) );

		return $memSyncRulesDetails;
	}


}

//civicrm_wp_initialize();

?>