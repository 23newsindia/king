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
if (get_post_format()) {
	$class = get_post_format();
} else {
	$class = 'cposts';
}
?>

<li class="king-post-item <?php echo $class; ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php get_template_part( 'template-parts/content-templates/content-parts/content-thumb' ); ?>
			<a href="<?php echo esc_url( the_permalink() ); ?>" class="ajax-popup-link grid15-button"><i class="fa-solid fa-magnifying-glass"></i></a>
			<div class="article-meta">
				<?php get_template_part( 'template-parts/content-templates/content-parts/content-head' ); ?>
				<?php get_template_part( 'template-parts/content-templates/content-parts/content-meta' ); ?>
			</div><!-- .article-meta -->	
		</article><!--#post-##-->
	</li>
