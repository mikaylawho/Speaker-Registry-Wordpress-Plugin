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

		CrmSync::civi_member_sync();

	}
	?>

	<div id="message" class="updated below-h2">
			<span><p> CiviMember Memberships and WordPress Roles have been synchronized using available rules. Note: if
					no association rules exist then synchronization has not been completed.</p></span>
	</div>
	<?php


	/* NEW FUNCTION: sync civicrm members into wordpress user list */
	//function import_civicrm_members_to_wordpress() {


	if ( $_GET['action'] == 'import' ) {

		$status_message = CrmSync::import_civi_members_to_wordpress(); ?>

		<div class="updated below-h2">
			<span><p> <?php echo $status_message ?> </p></span>
		</div>
	<?php
	}
}?>



