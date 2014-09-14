<?php
/**
 * The Template for displaying all Speaker posts.
 *
 */

get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" role="main">
		<section>
			<article>
				<header>
					<h1>Speakers</h1>
					<hr />
				</header>

					<?php include 'speakerlist.inc' ?>

			</article>
		</section>
	</div><!-- #main-content -->

</div><!-- #primary -->

<?php get_footer(); ?>