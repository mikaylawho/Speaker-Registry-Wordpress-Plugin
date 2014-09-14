<?php
/**
 * The Template for displaying all single posts.
 *
 */

get_header(); ?>

<script type="text/javascript">

	function redirectSpeakerList(){
		window.location = '../';
	}

	function goToRequestForm(){
		document.getElementById('kyss_request_speaker_form').style.display = 'block';
		document.location.hash = '#speakerform';

	}
</script>

<style type="text/css">

	.kyss_header h1, input, img{
		margin-top:0;
		padding:0;
		display:block;
	}


	ul.kyss_page{
		margin:0;
		padding:0;
	}

	ul.kyss_page{
		list-style-type: none;
	}

	li.kyss_page{
		margin-bottom: 3px;
		indent:none;
	}

	li.kyss_label{
		font-weight:bold;
	}

	.kyss_speaker_info
	{
		float:left;
		clear:both;
	}

	#content{
		padding-left:20px;
		padding-right:20px;
		padding-bottom:10px;
		padding-top:10px;
	}

	.kyss_speaker_form_div{
		display:hidden;
		float:left;
		clear:both;
	}

	/*override of WP image thumbnail style*/
	img.alignleft.wp-post-image {
		margin-top: 0;
		margin-right: 10px;
		padding-top: 0;
	}



</style>

	<div id="primary" class="site-content">
		<div id="content" role="main">
			<div id="kyss_speaker_div" class="kyss_speaker_div">

				<?php

				$current_speaker = '';

				while ( have_posts() ) : the_post();

					$current_speaker = get_the_title();

					?>

				<section>
					<article>
						<header class="kyss_header">
							<?php
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail( 'thumbnail', array( 'class' => 'alignleft' ) );
							}
							?>
							<h1><?php the_title();?></h1>
							<input value="Request This Speaker" type="button" width="100px" onclick="goToRequestForm();">
							<input value="View All Speakers" type="button" width="100px" onclick="redirectSpeakerList();">
						</header>
							<div class="kyss_speaker_info">

								<ul class="kyss_page">
									<li class="kyss_label"><h3>Speaker Topics</h3></li>
									<li class="kyss_info"><?php the_terms( $post->ID, 'topics', '', ', ', ' ' ); ?></li>
									<li class="kyss_label"><h3>Speaker Bio</h3></li>
									<li class="kyss_info"><?php the_content(); ?></li>
								</ul>

							</div>
					</article>
				</section>

				<?php endwhile;
				?>
			</div>

			<a name="speakerform"></a>
			<div id="kyss_request_speaker_form" class="kyss_speaker_form_div" style="display:none;">

				<h3>Request this Speaker</h3>

				<?php

				html_form_code();

				function html_form_code() {
					echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
					echo '<p>';
					echo 'Your Name (required) <br/>';
					echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
					echo '</p>';
					echo '<p>';
					echo 'Your Email (required) <br/>';
					echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
					echo '</p>';
					echo '<p>';
					echo 'Your Message (required) <br/>';
					echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
					echo '</p>';
					echo '<p><input type="submit" name="cf-submitted" value="Send"></p>';
					echo '</form>';
				}

				function deliver_mail() {
					global $current_speaker;

					// if the submit button is clicked, send the email
					if ( isset( $_POST['cf-submitted'] ) ) {

						// sanitize form values
						$name    = sanitize_text_field( $_POST["cf-name"] );
						$email   = sanitize_email( $_POST["cf-email"] );
						$subject = 'Speaker Request:' . $current_speaker;
						$message = esc_textarea( $_POST["cf-message"] );

						// get the blog administrator's email address
						$to = get_option( 'admin_email' );

						$headers = "From: $name <$email>" . "\r\n";

						// If email has been process for sending, display a success message
						if ( wp_mail( $to, $subject, $message, $headers ) ) {
							echo '<div>';
							echo '<p>Thanks for contacting us, expect a response soon.</p>';
							echo '</div>';
						} else {
							echo 'An unexpected error occurred';
						}
					}
				}

				?>

			</div>


		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>