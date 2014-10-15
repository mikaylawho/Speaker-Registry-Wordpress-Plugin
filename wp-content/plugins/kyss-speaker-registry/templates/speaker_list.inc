<?php
defined('ABSPATH') or die("No script kiddies please!");



$speakers_abc = get_posts($args); //$args comes from the template that calls the include file
?>

<style type="text/css">

	/*override of WP image thumbnail style*/
	img.wp-post-image {
		margin-top: 0;
		margin-right: 10px;
		padding-top: 0;
		height:80px;
		width:80px;
	}

	#content{
		padding-left:20px;
		padding-right:20px;
		padding-bottom:10px;
		padding-top:10px;
	}

	div.page-numbers{
		margin-top:30px;
		margin-left:100px;
	}


</style>


<?php paginate();
	foreach( $speakers_abc as $post ) :	setup_postdata($post); ?>
	<div class="kyss_speaker_search_result" >
		<ul class="entry">
			<li style="display:inline-block;list-style-type: none;padding-right:10px;vertical-align: middle">
				<?php
				if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					the_post_thumbnail( 'thumbnail', array('style' => 'float:left;') );
				}
				else {
					?>
					<!-- image placeholder for Speakers with no featured image set. the purpose
					 for this is to line up the text of the Speaker results even if they do not show an image.-->
					<div id="placeholder" style="width:80px;"></div>
				<?php } ?>
			</li>

			<li style="display:inline-block;list-style-type: none;padding-right:10px;">
				<a href="<?php the_permalink()  ?>"><h3><?php the_title();?></h3></a></li>

			<li style="display:inline-block;list-style-type: none;padding-right:10px;">
				<?php the_terms( $post->ID, 'topics', 'Topics: ', ', ', ' ' ); ?></li>

		</ul>
	</div>
<?php
	endforeach;
	wp_reset_postdata();
?>
<?php
function paginate() {
	global $wp_query, $wp_rewrite;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
	$pagination = array(
		'base'     => @add_query_arg( 'page', '%#%' ),
		'format'   => '',
		'total'    => $wp_query->max_num_pages,
		'current'  => $current,
		'show_all' => false,
		'end_size' => 0,
		'mid_size' => 0,
		'type'     => 'list'
	);
	if ( $wp_rewrite->using_permalinks() ) {
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
	}
	if ( ! empty( $wp_query->query_vars['s'] ) ) {
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );
	}

	$links = paginate_links();
	echo '<div class="page-numbers">' . $links . '</div>';
}
paginate();
?>
