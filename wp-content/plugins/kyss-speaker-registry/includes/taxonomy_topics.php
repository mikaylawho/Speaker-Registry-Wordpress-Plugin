<?php
/**
 * The Template for displaying all Speaker posts for a specific topic.
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
						<h1><?php echo get_queried_object()->taxonomy;?>:&nbsp<?php echo get_queried_object()->name; ?></h1>
						<hr />
					</header>

					<?php include 'speakerlist.inc' ?>

				</article>
			</section>
		</div><!-- #main-content -->

	</div><!-- #primary -->


<?php get_footer(); ?>