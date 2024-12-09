<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9101284402640935"
     crossorigin="anonymous"></script>
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
	
	<?php if ( get_field( 'custom_html_head', 'options' ) ) : ?>
		<?php echo wp_kses_post( get_field( 'custom_html_head', 'options' ) ); ?>
	<?php endif; ?>
	<?php
	if ( get_field( 'enable_meta_tags', 'options' ) ) :
		king_meta_tags();
	endif;
	?>
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
// Header templates.
$headtemplate = get_field( 'header_templates', 'options' );
if ( $headtemplate ) :
	get_template_part( 'template-parts/header-templates/' . $headtemplate . '' );
else :
	get_template_part( 'template-parts/header-templates/header-template-01' );
endif;

$hidnav = get_field( 'hide_navbar', 'options' ) ? ' hidden-nav' : '';

?>

	<div id="content" class="site-content<?php echo esc_attr( $hidnav ); ?>">
