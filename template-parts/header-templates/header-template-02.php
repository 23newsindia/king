<?php
/**
 * The header template-02.
 *
 * This is the header template.
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

<?php get_template_part( 'template-parts/header-templates/header-parts/headerstrip' ); ?>
<div id="page" class="site header-template-02">
<header id="masthead" class="site-header lr-padding">
	<div class="king-header header-02 lr-padding">
		<button class="king-head-toggle" data-toggle="dropdown" data-target=".king-leftmenu" aria-expanded="false" aria-label="Toggle menu">
    <i class="fa-solid fa-bars" aria-hidden="true"></i>
</button>
		<?php get_template_part( 'template-parts/header-templates/header-parts/logo' ); ?>
		<?php get_template_part( 'template-parts/header-templates/header-parts/headnav' ); ?>

		<div class="king-header-right">
			<?php get_template_part( 'template-parts/header-templates/header-parts/extraicons' ); ?>
			<div id="searchv2-button" class="head-icons"><i class="fa fa-search fa-lg" aria-hidden="true"></i></div>
			<?php
			if ( get_field( 'enable_bookmarks', 'options' ) && is_user_logged_in() ) :
				echo king_header_bookmark();
			endif;
			?>
			<?php get_template_part( 'template-parts/header-templates/header-parts/notify' ); ?>
			<?php get_template_part( 'template-parts/header-templates/header-parts/submit' ); ?>
			<?php get_template_part( 'template-parts/header-templates/header-parts/user' ); ?>
		</div>
	</div><!-- .king-header -->
	<?php get_template_part( 'template-parts/header-templates/header-parts/search-v2' ); ?>

</header><!-- #masthead -->
	<?php
	$head02 = get_field( 'header_02_options', 'options' );
	if ( $head02['title_under_header'] && is_front_page() && ! $GLOBALS['hide'] ) : ?>
		<div class="header02-text lr-padding" id="responsiveTitle" style="<?php if ( $head02['color_of_title'] ) : ?>color:<?php echo esc_attr( $head02['color_of_title'] ); ?>;<?php endif; ?>opacity: 0;"><?php echo esc_attr( $head02['title_under_header'] ); ?></div>
	<?php endif; ?>
<?php get_template_part( 'template-parts/header-templates/header-parts/leftmenu' ); 