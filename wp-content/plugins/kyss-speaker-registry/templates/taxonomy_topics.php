<?php
/**
 * The Template for displaying all Speaker posts for a specific topic.
 *
 */

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
							<?php include 'topic_select.inc' ?>
						<hr />
					</header>

					<?php include 'speakerlist.inc' ?>

				</article>
			</section>
		</div><!-- #main-content -->

	</div><!-- #primary -->


<?php get_footer(); ?>