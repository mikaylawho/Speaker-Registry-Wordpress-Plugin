<?php
/**
 * The Template for displaying all Speaker posts.
 *
 */

defined('ABSPATH') or die("No script kiddies please!");

get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array( 'posts_per_page' => 10, 'orderby'=> 'title', 'order' => 'ASC', 'post_type' => 'speaker', 'paged' => $paged );
?>

<div id="primary" class="site-content">
	<div id="wrap" class="clearfix">
		<section id="content" class="primary" role="main">
			<article>
				<header>
					<h2 class="post-title"><?php echo get_option( 'kyss_all_speakers_page_header' ) ?></h2>
					<?php include 'topic_select.inc' ?>
					<hr />
				</header>

				<?php include 'speaker_list.inc' ?>

			</article>
		</section>
		<?php get_sidebar(); ?>
	</div><!-- #main-content -->

</div><!-- #primary -->

<?php get_footer(); ?>