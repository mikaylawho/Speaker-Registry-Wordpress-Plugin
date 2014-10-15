<?php
/*
	Plugin Name: KySS CiviMember Role Synchronize
	Depends: CiviCRM
	Plugin URI: https://github.com/mikaylawho/
	Description: Plugin to syncronize members in CiviCRM with WordPress -- with addition of CiviMember import into Wordpress user list
	Author: mikaylawho based on work by Jag Kandasamy and Tadpole Collective
	Version: 3.0
	Author URI: https://github.com/mikaylawho/

	Based on CiviMember Role Synchronize by Jag Kandasamy of http://www.orangecreative.net.  This has been
	altered to use WP $wpdb class.

	*/

global $tadms_db_version;
$tadms_db_version = '1.1';


include_once('civi.php');

function tadms_install() {
	global $wpdb;
	global $tadms_db_version;

	$table_name = $wpdb->prefix . "civi_member_sync";

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `wp_role` varchar(255) NOT NULL,
          `civi_mem_type` int(11) NOT NULL,
          `current_rule` varchar(255) NOT NULL,
          `expiry_rule` varchar(255) NOT NULL,
          `expire_wp_role` varchar(255) NOT NULL,
           PRIMARY KEY (`id`),         
           UNIQUE KEY `civi_mem_type` (`civi_mem_type`)
           )ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( "tadms_db_version", $tadms_db_version );
}

register_activation_hook( __FILE__, 'tadms_install' );

/* function civi_member_sync_daily moved to civi.php. Same function is shared by manual sync and by daily sync.*/
//require_once( 'civi.php' );

if ( ! wp_next_scheduled( 'civi_member_sync_refresh' ) ) {
	wp_schedule_event( time(), 'daily', 'civi_member_sync_refresh' );
}
add_action( 'civi_member_sync_refresh', 'civi_member_sync_daily' );


/** function to check user's membership record while login and logout **/

function civi_member_sync_check() {

	civicrm_wp_initialize();

	global $wpdb;
	global $current_user, $currentUserID, $currentUserEmail;
	//get username in post while login
	if ( ! empty( $_POST['log'] ) ) {
		$username = $_POST['log'];
		/*$userDetails   = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_login =%s", $username ) );
		$currentUserID = $userDetails[0]->ID;*/
		$current_user     = get_user_by( 'login', $username );
		$currentUserID    = $current_user->ID;
		$currentUserEmail = $current_user->user_email;


		//getting current logged in user's role
		//$current_user_role = new WP_User( $currentUserID );
		$current_user_role = $current_user->roles[0];


		//getting user's civi contact id and checkmembership details
		if ( $current_user_role != 'administrator' ) {
			require_once 'CRM/Core/Config.php';
			$config = CRM_Core_Config::singleton();
			require_once 'api/api.php';
			$params         = array(
				'version'    => '3',
				'page'       => 'CiviCRM',
				'q'          => 'civicrm/ajax/rest',
				'sequential' => '1',
				'uf_name'    => $currentUserEmail,
			);
			$contactDetails = civicrm_api( "UFMatch", "get", $params );
			$contactID      = $contactDetails['values'][0]['contact_id'];
			if ( ! empty( $contactID ) ) {
				$member = CrmSync::member_check( $contactID, $currentUserID, $current_user_role );
			}
		}
	}

	return true;
}

add_action( 'wp_login', 'civi_member_sync_check' );
add_action( 'wp_logout', 'civi_member_sync_check' );


/** function to set setings page for the plugin in menu **/
function setup_civi_member_sync_check_menu() {

	add_submenu_page( 'CiviMember Role Sync', 'CiviMember Role Sync', 'List of Rules', 'add_users', 'civi_member_sync/settings.php' );
	add_submenu_page( 'CiviMember Role Manual Sync', 'CiviMember Role Manual Sync', 'List of Rules', 'add_users', 'civi_member_sync/manual_sync.php' );
	add_options_page( 'CiviMember Role Sync', 'CiviMember Role Sync', 'manage_options', 'civi_member_sync/list.php' );
}

add_action( "admin_menu", "setup_civi_member_sync_check_menu" );
add_action( 'admin_init', 'my_plugin_admin_init' );

//create the function called by your new action
function my_plugin_admin_init() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-form' );
}

function plugin_add_settings_link( $links ) {
	$settings_link = '<a href="admin.php?page=civi_member_sync/list.php">Settings</a>';
	array_push( $links, $settings_link );

	return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );
?>