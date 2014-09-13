<?php
/**
 * The Template for displaying all single posts.
 *
 */

get_header(); ?>


	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php
			if ( have_posts() ) :
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

				endwhile;
				// Previous/next post navigation.
				get_registered_nav_menus();

			else :
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' );

			endif;
			?>

		</div> <!--div id content-->
	</div> <!--div id primary-->


<?php
get_sidebar();
get_footer();
?>