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


</style>


<?php
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
<div class="nav-next alignright"><?php previous_posts_link( 'Next Page' ); ?></div>
<div class="nav-previous alignleft"><?php next_posts_link( 'Previous Page' ); ?></div>

