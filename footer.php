<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</div><!-- #content -->
<footer id="colophon" class="site-footer">
	<div class="lr-padding">
		<?php if ( get_field( 'ad_in_footer', 'options' ) && king_add_free_mode() ) : ?>
			<div class="king-ads footer-ad">
				<?php
				$ad_footer = get_field( 'ad_in_footer', 'options' );
				echo do_shortcode( $ad_footer );
				?>
			</div>
		<?php endif; ?>		
<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
	<aside class="fatfooter" role="complementary">
<?php else : ?>
	<aside class="fatfooter footer-single" role="complementary">
<?php endif; ?>		
		<div class="king-footer-social">
			<?php if ( get_field( 'footer_page_logo', 'options' ) ) : ?>
				<div><img data-king-img-src="<?php echo wp_kses_post( get_field('footer_page_logo', 'options') ); ?>" class="king-lazy" /></div>
			<?php endif; ?>
			<?php if ( get_field( 'footer_page_description', 'options' ) ) : ?>
				<div><?php echo wp_kses_post( get_field('footer_page_description', 'options') ); ?></div>
			<?php endif; ?>
			<ul>
				<?php if ( get_field( 'footer_facebook_link', 'options' ) ) : ?>
					<li><a href="<?php echo wp_kses_post( get_field( 'footer_facebook_link', 'options') ); ?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'footer_linkedin_link', 'options' ) ) : ?>
					<li><a href="<?php echo wp_kses_post( get_field( 'footer_linkedin_link', 'options') ); ?>"><i class="fab fa-linkedin"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'footer_twitter_link', 'options' ) ) : ?>
					<li><a href="<?php echo wp_kses_post( get_field( 'footer_twitter_link', 'options') ); ?>"><i class="fab fa-x-twitter"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'footer_instagram_link', 'options' ) ) : ?>
					<li><a href="<?php echo wp_kses_post( get_field( 'footer_instagram_link', 'options') ); ?>"><i class="fab fa-instagram"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'footer_custom_link', 'options' ) ) : ?>
					<?php echo wp_kses_post( get_field( 'footer_custom_link', 'options') ); ?>
				<?php endif; ?>			
			</ul>
		</div>
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
				<div class="first widget-area">
					<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
				</div><!-- .first .widget-area -->
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
				<div class="second widget-area">
					<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
				</div><!-- .second .widget-area -->
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
				<div class="third widget-area">
					<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
				</div><!-- .third .widget-area -->
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div class="fourth widget-area">
					<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
				</div><!-- .fourth .widget-area -->
			<?php endif; ?>
	</aside><!-- #fatfooter -->

	<div class="footer-info">
		<div class="site-info">
			<?php echo wp_kses_post( get_field( 'footer_copyright', 'options' ) ); ?>
		</div><!-- .site-info -->

	</div>
</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php get_template_part( 'template-parts/footer-parts' ); ?>
</body>
</html>
