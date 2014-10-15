<?php

/*
Plugin Name: KySS Convention Scheduler
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A plugin to schedule speakers and events for a convention.
Version: 1.0.0
Author: Mikel Hensley
Author URI: http://mikelhensley.info
License: GPL2
*/

defined('ABSPATH') or die("No script kiddies please!");

define( 'ROOT', plugins_url( '', __FILE__ ) );
define( 'IMAGES', ROOT . '/img/' );
define( 'STYLES', ROOT . '/css/' );
define( 'SCRIPTS', ROOT . '/js/' );

/*Templates*/
//
function kyss_con_speaker_template($single_template) {
	global $post;

	if ($post->post_type == 'con_speaker') {
		$single_template = dirname( __FILE__ ) . '/templates/single-speaker.php';
	}
	return $single_template;
}
add_filter( 'single_template', 'kyss_con_speaker_template' );


function kyss_con_event_template($single_template) {
	global $post;

	if ($post->post_type == 'event') {
		$single_template = dirname( __FILE__ ) . '/templates/single-event.php';
	}
	return $single_template;
}
add_filter( 'single_template', 'kyss_con_event_template' );


function kyss_con_post_type_template( $archive_template ) {

	if ( is_post_type_archive ( 'con_speaker' ) ) {
		$archive_template = dirname( __FILE__ ) . '/templates/archive-speaker.php';
	}
	return $archive_template;
}

add_filter( 'archive_template', 'kyss_con_post_type_template' ) ;


/*Custom Objects*/

//register custom post type for speakers
if ( ! function_exists('kyss_con_speakers_post_type') ) {

// Register Custom Post Type
	function kyss_con_speakers_post_type() {

		$labels  = array(
			'name'               => _x( 'Scheduled Speakers', 'Post Type General Name', 'text_domain' ),
			'singular_name'      => _x( 'Scheduled Speaker', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'          => __( 'Scheduled Speakers', 'text_domain' ),
			'parent_item_colon'  => __( 'Parent Item:', 'text_domain' ),
			'all_items'          => __( 'All Scheduled Speakers', 'text_domain' ),
			'view_item'          => __( 'View Scheduled Speaker', 'text_domain' ),
			'add_new_item'       => __( 'Schedule New Speaker', 'text_domain' ),
			'add_new'            => __( 'Schedule Speaker', 'text_domain' ),
			'edit_item'          => __( 'Edit Scheduled Speaker', 'text_domain' ),
			'update_item'        => __( 'Update Scheduled Speaker', 'text_domain' ),
			'search_items'       => __( 'Search Scheduled Speakers', 'text_domain' ),
			'not_found'          => __( 'Not found', 'text_domain' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'text_domain' ),
		);
		$rewrite = array(
			'slug'       => 'kyss_con_speakers',
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);
		$args    = array(
			'label'               => __( 'con_speaker', 'text_domain' ),
			'description'         => __( 'Scheduled Speakers', 'text_domain' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
			'taxonomies'          => array( 'Category' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => true,
			'capability_type'     => 'post',
			'query_var'           => true,
		);
		register_post_type( 'con_speaker', $args );

	}

// Hook into the 'init' action
	add_action( 'init', 'kyss_con_speakers_post_type', 0 );

}

//register custom post type for speakers
if ( ! function_exists('kyss_con_event_post_type') ) {

// Register Custom Post Type
	function kyss_con_event_post_type() {


		$labels = array(
			'name'               => __( 'Events', 'kyss' ),
			'singular_name'      => __( 'Event', 'kyss' ),
			'add_new_item'       => __( 'Add New Event', 'kyss' ),
			'all_items'          => __( 'All Events', 'kyss' ),
			'edit_item'          => __( 'Edit Event', 'kyss' ),
			'new_item'           => __( 'New Event', 'kyss' ),
			'view_item'          => __( 'View Event', 'kyss' ),
			'not_found'          => __( 'No Events Found', 'kyss' ),
			'not_found_in_trash' => __( 'No Events Found in Trash', 'kyss' )
		);

		$supports = array(
			'title',
			'editor',
			'excerpt'
		);

		$args = array(
			'label'        => __( 'Events', 'kyss' ),
			'labels'       => $labels,
			'description'  => __( 'Scheduled Convention Events', 'kyss' ),
			'public'       => true,
			'show_in_menu' => true,
			'has_archive'  => true,
			'rewrite'      => true,
			'supports'     => $supports
		);
		register_post_type( 'event', $args );

	}
}

// Hook into the 'init' action
	add_action( 'init', 'kyss_con_event_post_type', 0 );

function kyss_render_con_event_info_metabox( $post ) {

	// generate a nonce field
	wp_nonce_field( basename( __FILE__ ), 'kyss-con-event-info-nonce' );

	// get previously saved meta values (if any)
	$event_start_date = get_post_meta( $post->ID, 'event-start-date', true );
	$event_end_date = get_post_meta( $post->ID, 'event-end-date', true );
	$event_venue = get_post_meta( $post->ID, 'event-venue', true );

	// if there is previously saved value then retrieve it, else set it to the current time
	$event_start_date = ! empty( $event_start_date ) ? $event_start_date : time();

	//we assume that if the end date is not present, event ends on the same day
	$event_end_date = ! empty( $event_end_date ) ? $event_end_date : $event_start_date;

	?>

<!--TODO: Set fields in the metabox for start and end times. Include only the start date field (for possible multi-day convention.)-->

	<label for="kyss-con-event-start-date"><?php _e( 'Event Start Date:', 'kyss' ); ?></label>
	<input class="widefat kyss-con-event-date-input" id="kyss-con-event-start-date" type="text" name="kyss-con-event-start-date" placeholder="Format: February 18, 2014" value="<?php echo date( 'F d, Y', $event_start_date ); ?>" />

	<label for="kyss-con-event-end-date"><?php _e( 'Event End Date:', 'kyss' ); ?></label>
	<input class="widefat kyss-con-event-date-input" id="kyss-con-event-end-date" type="text" name="kyss-con-event-end-date" placeholder="Format: February 18, 2014" value="<?php echo date( 'F d, Y', $event_end_date ); ?>" />

	<label for="kyss-con-event-venue"><?php _e( 'Event Venue:', 'kyss' ); ?></label>
	<input class="widefat" id="kyss-con-event-venue" type="text" name="kyss-con-event-venue" placeholder="eg. Times Square" value="<?php echo $event_venue; ?>" />

	<br>

<?php }

function kyss_add_event_info_metabox() {
	add_meta_box(
		'kyss-event-info-metabox',
		__( 'Event Info', 'kyss' ),
		'kyss_render_con_event_info_metabox',
		'event',
		'side',
		'core'
	);
}
add_action( 'add_meta_boxes', 'kyss_add_event_info_metabox' );

function kyss_save_event_info( $post_id ) {

	// checking if the post being saved is an 'event',
	// if not, then return
	if ( 'event' != $_POST['post_type'] ) {
		return;
	}

	// checking for the 'save' status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST['kyss-event-info-nonce'] ) && ( wp_verify_nonce( $_POST['kyss-event-info-nonce'], basename( __FILE__ ) ) ) ) ? true : false;

	// exit depending on the save status or if the nonce is not valid
	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	// checking for the values and performing necessary actions
	if ( isset( $_POST['kyss-event-start-date'] ) ) {
		update_post_meta( $post_id, 'event-start-date', strtotime( $_POST['kyss-event-start-date'] ) );
	}

	if ( isset( $_POST['kyss-event-end-date'] ) ) {
		update_post_meta( $post_id, 'event-end-date', strtotime( $_POST['kyss-event-end-date'] ) );
	}

	if ( isset( $_POST['kyss-event-venue'] ) ) {
		update_post_meta( $post_id, 'event-venue', sanitize_text_field( $_POST['kyss-event-venue'] ) );
	}
}
add_action( 'save_post', 'kyss_save_event_info' );

///*
//// Create the meta box
//function select_speaker_box_add_meta_box() {
//	add_meta_box(
//		'kyss-event-speaker',
//		__( 'Event Speaker', 'kyss' ),
//		'speaker_select_box_content',
//		'event',
//		'side',
//		'core'
//	);
//}
//
//// Create the meta box content
//function speaker_select_box_content() {
//
//	// generate a nonce field
//	wp_nonce_field( basename( __FILE__ ), 'kyss-con-event-info-nonce' );
//
//	$static_args = array(
//		'post_type' => 'con_speaker',
//		'show_option_none' => 'Choose Speaker'
//	);
//
//	$args = array( 'posts_per_page' => -1, 'orderby'=> 'title', 'order' => 'ASC', 'post_type' => 'speaker' );
//
////	$speakers = get_posts($args);
//
////	foreach( $speakers as $post ) : setup_postdata($post);
//		$selected_item = get_post_meta($post->ID,'_selected_item', true);
//		echo $post->title;
//		for($i=0; $i<=2; $i++) {
//			$incrementing_args = array(
//				'id' => "selected_item_{$i}",
//				'name' => 'selected_item[]',
//				'selected' => (empty($selected_item[$i]) ? 0 : $selected_item[$i]),
//			);
//		}
//
//		wp_dropdown_pages(array_merge($static_args,$incrementing_args));
////	endforeach;
//
//}
//
//// Save the selection
//function speaker_select_box_save_postdata($data, $postarr) {
//	// checking if the post being saved is an 'event',
//	// if not, then return
//	if ( 'event' != $_POST['post_type'] ) {
//		return;
//	}
//
//	// checking for the 'save' status
//	$is_autosave = wp_is_post_autosave( $post_id );
//	$is_revision = wp_is_post_revision( $post_id );
//	$is_valid_nonce = ( isset( $_POST['kyss-event-info-nonce'] ) && ( wp_verify_nonce( $_POST['kyss-event-info-nonce'], basename( __FILE__ ) ) ) ) ? true : false;
//
//	// exit depending on the save status or if the nonce is not valid
//	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
//		return;
//	}
//
//	update_post_meta($postarr['ID'], '_selected_item', $postarr['selected_item']);
//	return $data;
//}
//
//
//add_action( 'add_meta_boxes', 'select_speaker_box_add_meta_box' );
//add_action( 'save_post', 'speaker_select_box_save_postdata' );*/



/* Admin Configuration screen */

class ConventionSchedulerSettingsPage
{

	/**
	 * Start up
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_con_kyss_con_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_con_kyss_con_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'Settings Admin',
			'Convention Scheduler',
			'manage_options',
			'kyss-con-speaker-settings-admin',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		?>
		<div class="wrap">
			<h2>Speaker Registry Settings</h2>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'kyss_con_speaker_settings_group' );
				do_settings_sections( 'kyss-con-speaker-settings-admin' );
				submit_button();
				?>
			</form>
		</div>
	<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init()
	{
		register_setting(
			'kyss_con_events_settings_group', // Option group
			'kyss_all_events_page_header' // Option name
		);

		add_settings_section(
			'kyss_con_events_page_settings', // ID
			'Convention Scheduler Page Settings', // Title
			array( $this, 'print_page_section_info' ), // Callback
			'kyss-events-settings-admin' // Page
		);


		add_settings_field(
			'kyss_all_events_page_header',
			'Convention Schedule Page Header', // Title
			array( $this, 'event_list_page_title_callback' ), // Callback
			'kyss-event-settings-admin', // Page
			'kyss_event_page_settings' // Section
		);

		/* TODO: Add fields for Convention Title, Convention Description, start date, end date, start time, end time.*/

	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{
		$new_input = array();
//		if( isset( $input['id_number'] ) )
//			$new_input['id_number'] = absint( $input['id_number'] );
//
//		if( isset( $input['title'] ) )
//			$new_input['title'] = sanitize_text_field( $input['title'] );

		return $new_input;
	}

	/**
	 * Print the Section text
	 */

	public function print_page_section_info()
	{
		print 'Enter the settings for Convention Schedule page displays.';
	}



	public function speaker_list_page_title_callback()
	{
		$setting = get_option( 'kyss_all_events_page_header' );
		echo "<input type='text' name='kyss_all_events_page_header' value='$setting' />";

	}

}

if( is_admin() )
	$my_settings_page = new ConventionSchedulerSettingsPage();

/* End configuration screen */


/**
 * Plugin Name: @WPSE 85107
 * Description: <a target="_blank" href="http://wordpress.stackexchange.com/q/85107/89">WPSE 85107</a>
 */

class WPSE_85107 {
	var $FOR_POST_TYPE = 'event';
	var $SELECT_POST_TYPE = 'con_speaker';
	var $SELECT_POST_LABEL = 'Speaker';
	var $box_id;
	var $box_label;
	var $field_id;
	var $field_label;
	var $field_name;
	var $meta_key;
	function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}
	function admin_init() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		$this->meta_key     = "_selected_{$this->SELECT_POST_TYPE}";
		$this->box_id       = "select-{$this->SELECT_POST_TYPE}-metabox";
		$this->field_id     = "selected-{$this->SELECT_POST_TYPE}";
		$this->field_name   = "selected_{$this->SELECT_POST_TYPE}";
		$this->box_label    = __( "Select {$this->SELECT_POST_LABEL}", 'wpse-85107' );
		$this->field_label  = __( "Choose {$this->SELECT_POST_LABEL}", 'wpse-85107' );
	}
	function add_meta_boxes() {
		add_meta_box(
			$this->box_id,
			$this->box_label,
			array( $this, 'select_box' ),
			$this->FOR_POST_TYPE,
			'side'
		);
	}
	function select_box( $post ) {
		$selected_post_id = get_post_meta( $post->ID, $this->meta_key, true );
		global $wp_post_types;
		$save_hierarchical = $wp_post_types[$this->SELECT_POST_TYPE]->hierarchical;
		$wp_post_types[$this->SELECT_POST_TYPE]->hierarchical = true;
		wp_dropdown_pages( array(
			'id' => $this->field_id,
			'name' => $this->field_name,
			'selected' => empty( $selected_post_id ) ? 0 : $selected_post_id,
			'post_type' => $this->SELECT_POST_TYPE,
			'show_option_none' => $this->field_label,
		));
		$wp_post_types[$this->SELECT_POST_TYPE]->hierarchical = $save_hierarchical;
	}
	function save_post( $post_id, $post ) {
		if ( $post->post_type == $this->FOR_POST_TYPE && isset( $_POST[$this->field_name] ) ) {
			update_post_meta( $post_id, $this->meta_key, $_POST[$this->field_name] );
		}
	}
}
new WPSE_85107();