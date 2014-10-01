<?php

class CrmSync {

	private static $MembershipType;
	private static $MembershipStatus;

	/**
	 * @return mixed
	 */
	public static function getMembershipType() {
		global $MembershipType;
		if(!isset($MembershipType)){
			self::setMembershipType();
		}return $MembershipType;
	}

	/**
	 * @param mixed $MembershipType
	 */
	public static function setMembershipType() {
		global $MembershipType;
		$MembershipTypeDetails = civicrm_api( "MembershipType", "get", array(
			'version'    => '3',
			'sequential' => '1'
		), null );
		foreach ( $MembershipTypeDetails['values'] as $key => $values ) {
			$MemType[ $values['id'] ] = $values['name'];
		}
		if(isset($MemType)){
			$MembershipType = $MemType;
		}

	}

	/**
	 * @return mixed
	 */
	public static function getMembershipStatus() {
		global $MembershipStatus;
		if(!isset($MembershipStatus)){
			self::setMembershipStatus();
		}
		return $MembershipStatus;
	}

	/**
	 * @param mixed $MembershipStatus
	 */
	public static function setMembershipStatus() {
		global $MembershipStatus;
		$MembershipStatusDetails = civicrm_api( "MembershipStatus", "get", array(
			'version'    => '3',
			'sequential' => '1'
		), null );
		foreach ( $MembershipStatusDetails['values'] as $key => $values ) {
			$MemStatus[ $values['id'] ] = str_replace( ' ', '', $values['name'] );
		}
		if(isset($MemStatus)){
			$MembershipStatus = $MemStatus;
		}
	}





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

	static function civi_member_sync() {
		$users = get_users();

		foreach ( $users as $user ) {
			$uid    = $user->ID;
			$uemail = $user->data->user_email;

			if ( empty( $uemail ) ) {
				continue;
			}

			//Mikel -- Updated to use the Civicrm API, and to match CiviUsers with Wordpress users based on primary email
			//rather than id

			$contact = civicrm_api3( 'UFMatch', 'get', array(
				'sequential' => 1,
				'return'     => array( "uf_id", "contact_id" ),
				'uf_name'    => $uemail,
			) );

			if ( isset( $contact ) ) {
				//if ( $contact->fetch() ) {
				$cid        = $contact['values']['0']['contact_id'];
				$memDetails = civicrm_api( "Membership", "get", array(
					'sequential' => '1',
					'contact_id' => $cid
				) );
				if ( ! empty( $memDetails['values'] ) ) {
					foreach ( $memDetails['values'] as $key => $value ) {
						$memStatusID      = $value['status_id'];
						$membershipTypeID = $value['membership_type_id'];
					}
				}

				$userData = get_userdata( $uid );
				if ( ! empty( $userData ) ) {
					$currentRole = $userData->roles[0];
				}
				//checking membership status and assign role
				$check = member_check( $cid, $uid, $currentRole );

			}
		}
	}

	static function import_civi_members_to_wordpress() {
		$return_message = '';
		$account_creation_messages = '';

//TODO: adjust this so that the targeted member organization(s) and membership types are configurable
		$result_current_members = civicrm_api3( 'Membership', 'get', array(
			'sequential'         => 1,
			'return'             => array( "contact_id" ),
			'status_id'          => array(
				'IN' => array(
					"1",
					"2",
					"3"
				)
			),
			'membership_type_id' => array(
				'IN' => array(
					"1",
					"2",
					"3",
				)
			),
		) );

		$current_member_id_array = array();

		$array_counter = 0;
		foreach ( $result_current_members['values'] as $key => $values ) {

			$current_member_id_array[ $array_counter ] = $values['contact_id'];
			$array_counter += 1;
		}

		$result_member_emails = civicrm_api3( 'Email', 'get', array(
			'return'     => array( "email", "contact_id" ),
			'contact_id' => array( 'IN' => $current_member_id_array ),
			'is_primary' => 1,
		) );

		if ( isset( $result_member_emails ) ) {
			$new_account_count = 0;

			foreach ( $result_member_emails['values'] as $key => $values ) {

				//extract the alias part of the email for the new user's username
				$email    = $values['email'];
				$parts    = explode( "@", $email );
				$username = $parts[0];

				$email = $values['email'];

				if ( null == username_exists( $username ) ) {
					$password = wp_generate_password( 12, true );
					$user_id  = wp_create_user( $username, $password, $email );

					if ( is_wp_error( $user_id ) ) {

						echo 'Error creating user ' . $username . '/' . $email . ': ' . $user_id->get_error_message();
						echo '<br>';

					} else {

						$account_creation_messages = $account_creation_messages . 'Created account for user ' . $username . ' ID: ' . $user_id . '. <br>';


						wp_update_user(
							array(
								'ID'       => $user_id,
								'nickname' => $email,
							)

						);

						// Email the user
						wp_mail( $email, 'Welcome ' . $username . '!', ' Your Password: ' . $password );

						$new_account_count += 1;
					}

				}
			}
			$return_message = $return_message . 'Total new accounts created: ' . $new_account_count . '<br>' . $account_creation_messages;
		}

		return $return_message;

	}
}

civicrm_wp_initialize();

?>