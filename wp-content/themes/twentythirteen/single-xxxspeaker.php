<?php
/**
 * The Template for displaying all single posts.
 *
 */

get_header(); ?>

<?php
// display a list of terms by taxonomy
$args = array(
	'public'   => true,
	'_builtin' => false,
);
$taxonomies = get_taxonomies( $args, 'objects', 'and' );
if ($taxonomies) {
	foreach ($taxonomies as $taxonomy) {
		echo '<p><strong>' . $taxonomy->labels->name . '</strong>:  ';
		echo get_the_term_list( '' , $taxonomy->name, '', ', ', '' );
		echo '</p>';
	}
}
?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'Speakers' ); ?>

<?php
//list custom field values for this product
				$topics_list = get_post_meta( $post->ID, 'Topics', true );
				if( !empty( $topics_list ) ){ ?>
					<p><strong>Topics: </strong><span class = “metalist Topics”><?php echo $topics_list; ?> . </span></p><?php
				}
				?>

				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
				</nav><!-- .nav-single -->

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. 
			?>

			<?php
			if ( !empty ( $fabric_list ) ) { ?>
				<?php
				//display other products with the same custom field value
				$the_query = new WP_query(
					array(
						'post__not_in' => array($post->ID),
						'post_type' => 'Speaker',
						'meta_key' => 'Topics',
						'meta_value' => $topics_list,
					) );
				if ( $the_query->have_posts() ) { ?>
					<h3>Other speakers on this topic:</h3>
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
						<div class="common-topic-listing">
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</div>
					<?php endwhile;
				}
				wp_reset_postdata();
			}
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>