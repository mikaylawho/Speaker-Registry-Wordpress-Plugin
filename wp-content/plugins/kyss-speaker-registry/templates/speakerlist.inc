
<?php while ( have_posts() ) : the_post(); ?>
	<div class="kyss_speaker_search_result">
		<ul>
			<li style="display:inline-block;list-style-type: none;padding-right:10px;vertical-align: bottom">
				<?php
				if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					the_post_thumbnail( 'thumbnail', array('style' => 'float:left;') );
				}
				else {
					?>
					<!-- image placeholder for Speakers with no featured image set. the purpose
					 for this is to line up the text of the Speaker results even if they do not show an image.-->
					<div id="placeholder" style="height:150px; width:150px; border:dotted 1px black"></div>
				<?php } ?>
			</li>

			<li style="display:inline-block;list-style-type: none;padding-right:10px;">
				<a href="<?php the_permalink()  ?>"><h3><?php the_title();?></h3></a></li>

			<li style="display:inline-block;list-style-type: none;padding-right:10px;">
				<?php the_terms( $post->ID, 'topics', 'Topics: ', ', ', ' ' ); ?></li>

		</ul>
	</div>
<?php endwhile; ?>
