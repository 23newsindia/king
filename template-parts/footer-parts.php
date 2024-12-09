<?php
/**
 * Footer Parts
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php wp_footer(); ?>
<?php get_template_part( 'template-parts/king-header-login' ); ?>

<?php
if ( get_field( 'enable_bookmarks', 'options' ) || ! get_field( 'disable_link', 'options' ) ) :
	get_template_part( 'template-parts/header-templates/header-parts/readlater' );
endif;
?>
<?php
if ( get_field( 'enable_newsletter_popup', 'options' ) ) :
	get_template_part( 'template-parts/header-templates/header-parts/newsletter' );
endif;
?>
<?php if ( get_field( 'enable_gdpr_cookie', 'options' ) ) : ?>
	<aside id="king-cookie" class="king-cookie" style="display: none;">
		<p class="king-cookie-content"><?php echo get_field( 'cookie_popup_content', 'options' ); ?></a></p>
		<div class="king-cookie-footer">
			<a id="king-cookie-accept" class="king-cookie-accept" href="#"><?php echo get_field( 'cookie_button_text', 'options' ); ?></a>
		</div>
	</aside>
<?php endif; ?>
