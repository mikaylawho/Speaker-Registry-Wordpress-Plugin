<?php
/**
 * The Template for displaying all Speaker posts for a specific topic.
 *
 */

defined('ABSPATH') or die("No script kiddies please!");

get_header();

$topics = get_terms( 'topics', array(
		'orderby'    => 'name',
		'order'      => 'ASC'
	)
);

$topic_query = array(
	array(
		'taxonomy' => get_queried_object()->taxonomy,
		'field'    => 'slug',
		'terms'    => get_queried_object()->name,
	),
);

$args = array( 'posts_per_page' => -1, 'orderby'=> 'title', 'order' => 'ASC', 'post_type' => 'speaker', 'tax_query' => $topic_query );

?>




	<div id="primary" class="site-content">
		<div id="content" role="main">
			<section>
				<article>
					<header>
						<h1><?php echo get_option( 'kyss_all_speakers_page_header' ) ?>:
							<?php echo get_queried_object()->name; ?></span></h1>
							<?php include 'topic_select.inc' ?>
						<hr />
					</header>

					<?php include 'speaker_list.inc' ?>

				</article>
			</section>
		</div><!-- #main-content -->

	</div><!-- #primary -->


<?php get_footer(); ?>