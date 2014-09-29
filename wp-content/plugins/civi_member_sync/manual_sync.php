<div id="icon-edit-pages" class="icon32"></div>
<div class="wrap">
	<h2 id="add-new-user">Manual Synchronize</h2>

	<?php ?>
	<table class="form-table">
		<td>
			<span>Manual Synchronization:</span> <br/>
			<?php $sync_confirm_url = get_site_url() . "/wp-admin/admin.php?&action=confirm&page=civi_member_sync/manual_sync.php"; ?>
			<?php $sync_import_url = get_site_url() . "/wp-admin/admin.php?&action=import&page=civi_member_sync/manual_sync.php"; ?>
			<input class="button-primary" type="submit"
			       value="Synchronize CiviMember Membership Types to WordPress Roles now"
			       onclick="window.location.href='<?php echo $sync_confirm_url; ?>'"/>
			<input class="button-primary" type="submit" value="Import CiviMember Members To Wordpress User List"
			       onclick="window.location.href='<?php echo $sync_import_url; ?>'"/>
		</td>
		</tr>
	</table>
</div>
<?php

require_once( 'civi.php' );
require_once( ABSPATH . 'wp-content/plugins/civicrm/civicrm/CRM/Core/BAO/UFMatch.php' );

if ( isset( $_GET['action'] ) ) {
	if ( $_GET['action'] == 'confirm' ) {

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



		?>

		<div id="message" class="updated below-h2">
			<span><p> CiviMember Memberships and WordPress Roles have been synchronized using available rules. Note: if
					no association rules exist then synchronization has not been completed.</p></span>
		</div>
	<?php
	}

	/* NEW FUNCTION: sync civicrm members into wordpress user list */
	//function import_civicrm_members_to_wordpress() {


	if ( $_GET['action'] == 'import' ) {

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

						echo 'Created account for user ' . $username . ' ID: ' . $user_id . '. <br>';


						wp_update_user(
							array(
								'ID'       => $user_id,
								'nickname' => $email_address,
							)

						);

						// Email the user
						wp_mail( $email, 'Welcome ' . $username . '!', ' Your Password: ' . $password );

						$new_account_count += 1;
					}

				}
			}
			echo 'Total new accounts created: ' . $new_account_count;
		}


	}
	//}
}?>



