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


function kyss_speaker_template($single_template) {
	global $post;

	if ($post->post_type == 'speaker') {
		$single_template = dirname( __FILE__ ) . '/includes/single-speaker.php';
	}
	return $single_template;
}
add_filter( 'single_template', 'kyss_speaker_template' );

function kyss_post_type_template( $archive_template ) {

	if ( is_post_type_archive ( 'speaker' ) ) {
		$archive_template = dirname( __FILE__ ) . '/includes/archive-speaker.php';
	}
	return $archive_template;
}

add_filter( 'archive_template', 'kyss_post_type_template' ) ;

//http://wordpress.stackexchange.com/questions/51022/default-taxonomy-template-in-plugin-override-in-theme

function kyss_topic_type_template(){
	//$taxonomy_array = array('topics'); //additional plugin-specific taxonomies may be added later
	//foreach ($taxonomy_array as $taxonomy_single) {
		//$template = dirname( __FILE__ ) . '/includes/taxonomy-'.$taxonomy_single.'.php';
	$template = dirname( __FILE__ ) . '/includes/taxonomy_topics.php';
	//}
	return $template;
}

add_filter('taxonomy_template','kyss_topic_type_template');





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
			'slug'                => 'kyss',
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

//Create the editbox for Topics for the Speakers edit page
function kyss_create_topic_metabox($post){?>
	<form action="" method="post" xmlns="http://www.w3.org/1999/html">
		<?php //add nonce for security
		wp_nonce_field('kyss_metabox_nonce', 'kyss_nonce');
		//retrieve the metadata values if they exist
		$kyss_topics = get_post_meta($post -> ID, 'Topics', true ); ?>
		<label for='kyss_topics'>What are this speaker's topics?
			<input type="text" name="kyss_topics" value="
		<?php echo esc_attr($kyss_topics); ?>" /></label>
	</form>
<?php }



//save the metabox data
function kyss_save_topic_meta( $post_id ){
	if ( isset( $_POST['kyss_topics'] ) ) {
		$new_topic_value = ( $_POST['kyss_topics'] );
		update_post_meta( $post_id, 'Topics', $new_topic_value );

		//add Speaker category automatically
		$category = wp_create_category( 'Speakers' );
		update_post_meta( $post_id, 'category', $category );

	}
}

add_action( 'save_post', 'kyss_save_topic_meta' );




