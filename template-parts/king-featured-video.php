<?php
/**
 * Featured Video.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="featured-video">
	<video autoplay muted loop id="myVideo">
		<source src="<?php echo get_field( 'slider_video', 'option' ); ?>" type="video/mp4">
		<?php echo esc_html__( 'Your browser does not support HTML5 video.', 'king' ); ?>
	</video>

		<div class="featured-video-content">
			<h1><?php echo get_field( 'slider_video_header', 'option' ); ?></h1>
			<span class="featured-video-description"><?php echo get_field( 'slider_video_description', 'option' ); ?></span>
			<?php if ( get_field( 'slider_video_button_text', 'option' ) ) : ?>
				<a class="featured-video-link" href="<?php echo get_field( 'slider_video_button_link', 'option' ); ?>"><?php echo get_field( 'slider_video_button_text', 'option' ); ?></a>
			<?php endif; ?>
		</div>

</div>
