<?php

/*
Plugin Name: KySS Speaker Registry
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A plugin to gather, edit, and display information for a Speaker Registry.
Version: 1.0.0
Author: Mikel Hensley
Author URI: http://mikelhensley.info
License: GPL2
*/

defined('ABSPATH') or die("No script kiddies please!");

/*Templates*/

function kyss_speaker_template($single_template) {
	global $post;

	if ($post->post_type == 'speaker') {
		$single_template = dirname( __FILE__ ) . '/templates/single-speaker.php';
	}
	return $single_template;
}
add_filter( 'single_template', 'kyss_speaker_template' );

function kyss_post_type_template( $archive_template ) {

	if ( is_post_type_archive ( 'speaker' ) ) {
		$archive_template = dirname( __FILE__ ) . '/templates/archive-speaker.php';
	}
	return $archive_template;
}

add_filter( 'archive_template', 'kyss_post_type_template' ) ;

//http://wordpress.stackexchange.com/questions/51022/default-taxonomy-template-in-plugin-override-in-theme

function kyss_topic_type_template($template){
	if(get_queried_object()->taxonomy == 'topics') {
		$template = dirname( __FILE__ ) . '/templates/taxonomy_topics.php';
	}
	return $template;
}

add_filter('taxonomy_template','kyss_topic_type_template');

/*Custom Taxonomies*/

if ( ! function_exists('kyss_create_taxonomies') ) {
	//register custom taxonomies
	function kyss_create_taxonomies() {
		$labels = array(
			'name'                       => __( 'Topics', 'kyss' ),
			'singular_name'              => __( 'Topic', 'kyss' ),
			'search_items'               => __( 'Search By Topic', 'kyss' ),
			'all_items'                  => __( 'All Topics', 'kyss' ),
			'parent_item'                => __( 'Parent Topic', 'kyss' ),
			'parent_item_colon'          => __( 'Parent Topic:', 'kyss' ),
			'edit_item'                  => __( 'Edit Topic', 'kyss' ),
			'update_item'                => __( 'Update Topic', 'kyss' ),
			'add_new_item'               => __( 'Add New Topic', 'kyss' ),
			'new_item_name'              => __( 'New Topic', 'kyss' ),
			'separate_items_with_commas' => __( 'Separate topics with commas', '' ),
			'menu_name'                  => __( 'Topics', 'kyss' ),
			'slug'                       => __( 'kyss_topics' ),
		);
		register_taxonomy( 'topics', 'speaker', array(
				'hierarchical'      => false,
				'labels'            => $labels,
				'query_var'         => true,
				'rewrite'           => true,
				'show_admin_column' => true
			)
		);
	}

	add_action( 'init', 'kyss_create_taxonomies', 0 );
}

/*Custom Objects*/

//register custom post type for speakers
if ( ! function_exists('kyss_speakers_post_type') ) {

// Register Custom Post Type
	function kyss_speakers_post_type() {

		$labels = array(
			'name'                => _x( 'Speakers', 'Post Type General Name', 'text_domain' ),
			'singular_name'       => _x( 'Speaker', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'           => __( 'Speakers', 'text_domain' ),
			'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
			'all_items'           => __( 'All Speakers', 'text_domain' ),
			'view_item'           => __( 'View Speaker', 'text_domain' ),
			'add_new_item'        => __( 'Add New Speaker', 'text_domain' ),
			'add_new'             => __( 'Add Speaker', 'text_domain' ),
			'edit_item'           => __( 'Edit Speaker', 'text_domain' ),
			'update_item'         => __( 'Update Speaker', 'text_domain' ),
			'search_items'        => __( 'Search Speakers', 'text_domain' ),
			'not_found'           => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
		);
		$rewrite = array(
			'slug'                => 'kyss_speakers',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( 'speaker', 'text_domain' ),
			'description'         => __( 'Speakers registered with the speaker registry', 'text_domain' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
			'taxonomies'          => array( 'Topics'),
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
			'rewrite'             => $rewrite,
			'capability_type'     => 'post',
			'query_var'           => true,
		);
		register_post_type( 'speaker', $args );

	}

// Hook into the 'init' action
	add_action( 'init', 'kyss_speakers_post_type', 0 );

}

/*Custom Taxonomy Metabox*/

if ( ! function_exists('kyss_create_topic_metabox') ) {
//Create the editbox for Topics for the Speakers edit page
	function kyss_create_topic_metabox( $post ) {
		?>
		<form action="" method="post" xmlns="http://www.w3.org/1999/html">
			<?php wp_nonce_field( 'kyss_metabox_nonce', 'kyss_nonce' );
			//retrieve the metadata values if they exist
			$kyss_topics = get_post_meta( $post->ID, 'Topics', true ); ?>
			<label for='kyss_topics'>What are this speaker's topics?
				<input type="text" name="kyss_topics" value="
		<?php echo esc_attr( $kyss_topics ); ?>"/></label>
		</form>
	<?php
	}


//save the metabox data
	function kyss_save_topic_meta( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['kyss_metabox_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['kyss_metabox_nonce'], 'kyss_create_topic_metabox' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		/* OK, it's safe for us to save the data now. */

		// Make sure that it is set.
		if ( ! isset( $_POST['kyss_topics'] ) ) {
			return;
		}

		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['kyss_topics'] );

		// Update the meta field in the database.
		$new_topic_value = ( $_POST['kyss_topics'] );
		update_post_meta( $post_id, 'Topics', $new_topic_value );

	}

	add_action( 'save_post', 'kyss_save_topic_meta' );
}
/*Speaker Contact form  and shortcode*/
/* code below adapted from http://www.sitepoint.com/build-your-own-wordpress-contact-form-plugin-in-5-minutes/ */

function html_form_code() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
	echo 'Your Name (required) <br/>';
	echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Email (required) <br/>';
	echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Subject (required) <br/>';
	echo '<input type="text" readonly name="cf-subject" pattern="[a-zA-Z ]+" value="Speaker Request: ' . ( isset( $_GET["current_speaker"] ) ? $_GET["current_speaker"] : '' ). '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Message (required). Please include the following information:
			<ul>
				<li>Title and description of the event where you would like the speaker to speak</li>
				<li>The event location</li>
				<li>Date and time</li>
				<li>What you would like the speaker to talk about</li>
			</ul>';
	echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<p><input type="submit" name="cf-submitted" value="Send"></p>';
	echo '</form>';
}

function deliver_mail() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['cf-submitted'] ) ) {

		// sanitize form values
		$name    = sanitize_text_field( $_POST["cf-name"] );
		$email   = sanitize_email( $_POST["cf-email"] );
		$subject = sanitize_text_field( $_POST["cf-subject"] );
		$message = esc_textarea( $_POST["cf-message"] );

		// get the blog administrator's email address
		//TODO: include the speaker's email in the TO field if it has been provided.
		$to = get_option( 'admin_email' );

		$headers = "From: $name <$email>" . "\r\n";

		// If email has been process for sending, display a success message
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo '<div>';
			echo '<p><strong>Thanks for contacting us, we will respond to you as soon as we can.</strong></p>';
			echo '</div>';
		} else {
			echo 'An unexpected error occurred';
		}
	}
}

function cf_shortcode() {
	ob_start();
	deliver_mail();
	html_form_code();

	return ob_get_clean();
}

add_shortcode( 'speaker_contact_form', 'cf_shortcode' );

/* end speaker contact form code*/



/* Admin Configuration screen */

class MySettingsPage
{

	/**
	 * Start up
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_kyss_speaker_registry_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_kyss_speaker_registry_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'Settings Admin',
			'Speaker Registry',
			'manage_options',
			'kyss-speaker-settings-admin',
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
				settings_fields( 'kyss_speaker_settings_group' );
				do_settings_sections( 'kyss-speaker-settings-admin' );
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
			'kyss_speaker_settings_group', // Option group
			'kyss_speaker_contact_form_url' // Option name
		);

		register_setting(
			'kyss_speaker_settings_group', // Option group
			'kyss_all_speakers_page_header' // Option name
		);

		add_settings_section(
			'kyss_speaker_contact_settings', // ID
			'Speaker Contact Settings', // Title
			array( $this, 'print_contact_section_info' ), // Callback
			'kyss-speaker-settings-admin' // Page
		);

		add_settings_field(
			'kyss_contact_form_url', // ID
			'Speaker Contact Form URL', // Title
			array( $this, 'contact_form_url_callback' ), // Callback
			'kyss-speaker-settings-admin', // Page
			'kyss_speaker_contact_settings' // Section
		);


		add_settings_section(
			'kyss_speaker_page_settings', // ID
			'Speaker Registry Page Settings', // Title
			array( $this, 'print_page_section_info' ), // Callback
			'kyss-speaker-settings-admin' // Page
		);


		add_settings_field(
			'kyss_all_speakers_page_header',
			'All Speakers Page Header', // Title
			array( $this, 'speaker_list_page_title_callback' ), // Callback
			'kyss-speaker-settings-admin', // Page
			'kyss_speaker_page_settings' // Section
		);



	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{
		$new_input = array();
		if( isset( $input['id_number'] ) )
			$new_input['id_number'] = absint( $input['id_number'] );

		if( isset( $input['title'] ) )
			$new_input['title'] = sanitize_text_field( $input['title'] );

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_contact_section_info()
	{
		print 'In order to use the "Request this Speaker function on the Speaker pages, you will need to create a page and add the shortcode
		[speaker_contact_form] in the page content area (include the brackets). This will set up an email form which will notify the site administrator
		when a speaker has been requested.';
	}

	public function print_page_section_info()
	{
		print 'Enter the settings for Speaker Registry page displays.';
	}


    public function contact_form_url_callback()
	{
		$setting = get_option( 'kyss_speaker_contact_form_url' );
		echo "<input type='url' name='kyss_speaker_contact_form_url' value='$setting' />";

	}

	public function speaker_list_page_title_callback()
	{
		$setting = get_option( 'kyss_all_speakers_page_header' );
		echo "<input type='text' name='kyss_all_speakers_page_header' value='$setting' />";

	}

}

if( is_admin() )
	$my_settings_page = new MySettingsPage();

/* End configuration screen */
