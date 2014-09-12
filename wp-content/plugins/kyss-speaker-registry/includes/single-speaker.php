<?php
/**
 * The Template for displaying all single posts.
 *
 */

get_header(); ?>

<style type="text/css">

	.kyss_header h1, input, img{
		margins:0;
		padding:0;
		text-align: bottom;
		display:inline;
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

	#content{
		padding-left:20px;
		padding-right:20px;
		padding-bottom:10px;
		padding-top:10px;
	}


</style>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

			<section>
				<article>
					<header class="kyss_header">
						<ul class="kyss_page">
							<li class="kyss_header"><?php
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail( 'thumbnail' );
							}
							?></li>
							<li class="kyss_header"><h1><?php the_title();?></h1><input value="Request This Speaker" type="button" width="100px">
							</li>
						</ul>
					</header>
						<div>

							<ul class="kyss_page">
								<li class="kyss_label"><h3>Speaker Topics</h3></li>
								<li class="kyss_info"><?php the_terms( $post->ID, 'topics', '', ', ', ' ' ); ?></li>
								<li class="kyss_label"><h3>Speaker Bio</h3></li>
								<li class="kyss_info"><?php the_content(); ?></li>
							</ul>

						</div>
				</article>

				<a href="../">View All Speakers</a>
			</section>

			<?php endwhile;
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>