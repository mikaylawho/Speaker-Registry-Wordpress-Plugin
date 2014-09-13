
<?php while ( have_posts() ) : the_post(); ?>
<div class="kyss_speaker_search_result">
	<?php
	if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
		the_post_thumbnail( 'thumbnail' );
	}
	?>

	<a href="<?php the_permalink()  ?>"><h3><?php the_title();?></h3></a>

	<?php the_terms( $post->ID, 'topics', 'Topics: ', ', ', ' ' ); ?>

	<hr />
</div>
<?php endwhile; ?>
