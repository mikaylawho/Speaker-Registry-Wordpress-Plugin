<?php
/**
 * The Template for displaying all single posts.
 *
 */

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
		<div id="content" role="main">
			<div id="kyss_speaker_div" class="kyss_speaker_div">

				<?php while ( have_posts() ) : the_post() ?>

				<section>
					<article>
						<header class="kyss_header">
							<?php
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail( 'thumbnail', array( 'class' => 'alignleft' ) );
							}
							?>
							<h1><?php the_title();?></h1>
							<!--TODO: set a configuration value in the admin screen for the speaker request page url-->
							<form action="<?php echo site_url() ?>/speaker-request-form" method="get">
								<input name="current_speaker" type="hidden" value="<?php echo get_the_title(); ?>">
								<input value="Request This Speaker" type="submit" width="100px">
							</form>
							<input value="View All Speakers" type="button" width="100px" onclick="redirectSpeakerList();">
						</header>
							<div class="kyss_speaker_info">

								<ul class="kyss_page">
									<li class="kyss_label"><h3>Speaker Topics</h3></li>
									<li class="kyss_info"><?php the_terms( $post->ID, 'topics', '', ', ', ' ' ); ?></li>
									<li class="kyss_label"><h3>Speaker Bio</h3></li>
									<li class="kyss_info"><?php the_content(); ?></li>
								</ul>

								<!-- TODO: add additional meta fields for YouTube videos, speaker website, etc.-->

							</div>
					</article>
				</section>

				<?php endwhile;
				?>
			</div>


		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>