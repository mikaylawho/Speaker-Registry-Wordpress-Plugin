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

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array( 'posts_per_page' => 10, 'orderby'=> 'title', 'order' => 'ASC', 'post_type' => 'speaker', 'tax_query' => $topic_query, 'paged' => $paged );

?>

<div id="primary" class="site-content">
	<div id="wrap" class="clearfix">

		<section id="content" class="primary" role="main">
				<article>
					<header>
						<h2 class="post-title"><?php echo get_option( 'kyss_all_speakers_page_header' ) ?>:
							<?php echo get_queried_object()->name; ?></span></h2>
							<?php include 'topic_select.inc' ?>
						<hr />
					</header>

					<?php include 'speaker_list.inc' ?>

				</article>
			</section>
		<?php get_sidebar() ?>
	</div><!-- #main-content -->

	</div><!-- #primary -->


<?php get_footer(); ?>