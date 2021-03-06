<?php
/**
 * The Template for displaying all single posts.
 *
 */
defined('ABSPATH') or die("No script kiddies please!");

get_header(); ?>

<script type="text/javascript">

	function redirectSpeakerList(){
		window.location = '../';
	}

</script>

<style type="text/css">

	.kyss_header h1, input, img{
		margin-top:0;
		padding:0;
		display:block;
		align-items: left;
	}


	ul.kyss_page{
		margin:0;
		padding:0;
	}

	ul.kyss_page{
		list-style-type: none;
	}

	li.kyss_page{
		margin-bottom: 3px;
		indent:none;
	}

	li.kyss_label{
		font-weight:bold;
	}

	.kyss_speaker_info
	{
		float:left;
		clear:both;
	}

	#content{
		padding-left:20px;
		padding-right:20px;
		padding-bottom:10px;
		padding-top:10px;
	}


	/*override of WP image thumbnail style*/
	img.alignleft.wp-post-image {
		margin-top: 0;
		margin-right: 10px;
		padding-top: 0;
	}



</style>

	<div id="primary" class="site-content">
		<div id="wrap" class="clearfix">

			<div id="kyss_speaker_div" class="kyss_speaker_div">

				<?php while ( have_posts() ) : the_post() ?>

					<section id="content" class="primary" role="main">
					<article>
						<header class="kyss_header">
							<?php
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail( 'thumbnail', array( 'class' => 'alignleft' ) );
							}
							?>
							<h2 class="post-title"><?php the_title();?></h2>
							<form action="<?php echo get_option( 'kyss_speaker_contact_form_url' ) ?>" method="get">
								<input name="current_speaker" type="hidden" value="<?php echo get_the_title(); ?>">
								<input class="entry" value="Request This Speaker" type="submit" width="100px">
							</form>
							<input class="entry" value="View All Speakers" type="button" width="100px" onclick="redirectSpeakerList();">
						</header>
							<div class="kyss_speaker_info">

								<ul class="kyss_page entry">
									<li class="kyss_label"><h3 class="entry">Speaker Topics</h3></li>
									<li class="kyss_info"><?php the_terms( $post->ID, 'topics', '', ', ', ' ' ); ?></li>
									<li class="kyss_label"><h3 class="entry">Speaker Bio</h3></li>
									<li class="kyss_info"><?php the_content(); ?></li>
								</ul>

								<!-- TODO: add additional meta fields for YouTube videos, speaker website, etc.-->

							</div>
					</article>
				</section>

				<?php endwhile;
				?>
			</div>
			<?php get_sidebar() ?>

		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>