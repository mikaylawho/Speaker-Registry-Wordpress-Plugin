<?php
/**
 * The Template for displaying all single posts.
 *
 */

get_header(); ?>

	<style type="text/css">


		#content{
			padding-left:20px;
			padding-right:20px;
			padding-bottom:10px;
			padding-top:10px;
		}
	</style>


	<div id="primary" class="site-content">
		<div id="content" role="main">
			<section>
				<article>
					<header>
						<h1>Speakers</h1>
						<hr />
					</header>
					<?php while ( have_posts() ) : the_post(); ?>

					<?php
						if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
							the_post_thumbnail( 'thumbnail' );
						}
						?>
						<a href="<?php the_permalink()  ?>"><p><?php the_title();?></p></a>
						<?php the_terms( $post->ID, 'topics', 'Speaker Topics: ', ', ', ' ' ); ?>

						<hr />

				<?php endwhile; ?>
				</article>
			</section>
		</div><!-- #main-content -->

	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>