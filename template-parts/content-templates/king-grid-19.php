<?php
/**
 * Post Templates 01.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<li class="king-post-item">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'template-parts/content-templates/content-parts/content-thumb' ); ?>
	<?php if ( 'audio' === get_post_format() && get_field( 'video_tab', get_the_ID() ) ) : ?>
		<a href="<?php echo esc_url( the_permalink() ); ?>" class="king-listen grid15-button" data-toggle="tooltip" data-placement="right" title="" data-original-title="Listen" aria-describedby="tooltip209867"><i class="fa-solid fa-headphones"></i></a>
	<?php else : ?>	
		<a href="<?php echo esc_url( the_permalink() ); ?>" class="ajax-popup-link grid15-button"><i class="fa-solid fa-play"></i></a>
	<?php endif; ?>
	<header class="entry-header">
		<?php king_entry_cat(); ?>
		<?php
		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>
	</header><!-- .entry-header -->
</article><!--#post-##-->
</li>
