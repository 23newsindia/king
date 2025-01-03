<?php
/**
 * Single link template.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main post-page single-post sing-template-1">
		<?php if ( get_field( 'ads_above_content', 'option' ) && king_add_free_mode() ) : ?>
		<div class="ads-postpage"><?php $ad_above = get_field( 'ads_above_content','options' ); echo do_shortcode( $ad_above ); ?></div>
	<?php endif; ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if ( get_field( 'add_sponsor' ) ) : ?>
				<div class="add-sponsor"><a href="<?php echo get_field( 'post_sponsor_link' ); ?>" target="_blank"><img src="<?php echo get_field( 'post_sponsor_logo' ); ?>" /></a><span class="sponsor-label"><?php echo get_field( 'post_sponsor_description' ); ?></span></div>
			<?php endif; ?>			
			<?php get_template_part( 'template-parts/post-templates/single-parts/share' ); ?>
			<header class="entry-header">
				<?php echo get_the_category_list( ' ' ); ?>
				<?php
				if ( is_single() ) {
					the_title( '<h1 class="entry-title">', '</h1>' );
				} else {
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				}
				?>
			</header><!-- .entry-header -->
			<?php get_template_part( 'template-parts/post-templates/single-parts/badges' ); ?>
			<?php if ( get_field( 'nsfw_post' ) && ! is_user_logged_in() ) : ?>
			<div class="post-video nsfw-post-page">
				<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>">
					<i class="fa fa-paw fa-3x"></i>
					<div><h1><?php echo esc_html_e( 'Not Safe For Work', 'king' ); ?></h1></div>
					<span><?php echo esc_html_e( 'Click to view this post.', 'king' ); ?></span>
				</a>	
			</div>
		<?php else : ?>		
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="single-post-image"><?php the_post_thumbnail(); ?></div>
			<?php else : ?>
				<span class="single-post-no-thumb"></span>
			<?php endif; ?>
			<div class="king-affi-link">
				<?php if ( get_field( 'enable_affiliate' ) && get_field( 'sale_price' ) ) : ?>
					<span class="affi-price">
						<i class="fa-solid fa-tags"></i> <s><?php echo ( !empty(get_field( 'regular_price' )) ? esc_html_e( '$', 'king' ) . get_field( 'regular_price' ) : '' ); ?></s> <b><?php echo esc_html_e( '$', 'king' ); ?><?php echo get_field( 'sale_price' ); ?></b>
					</span>
					<a class="king-link" href="<?php echo esc_url( add_query_arg( array( 'template' => 'redirect', 'orderby' => get_the_ID() ), site_url() . '/' . $GLOBALS['king_dashboard'] ) ); ?>" target="_blank"><i class="fa-brands fa-hubspot"></i> <?php echo esc_html_e( 'CHECK IT OUT', 'king' ); ?></a>
				<?php else : ?>
					<a class="king-link" href="<?php echo esc_url( add_query_arg( array( 'template' => 'redirect', 'orderby' => get_the_ID() ), site_url() . '/' . $GLOBALS['king_dashboard'] ) ); ?>" target="_blank"><i class="fa-brands fa-hubspot"></i> <?php echo esc_html_e( 'Visit Link', 'king' ); ?></a>
				<?php endif; ?>
			</div>
			<div class="entry-content">
				<?php
				the_content( sprintf(
					/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'king' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">',
					'after'  => '</div>',
				) );
				?>
			</div><!-- .entry-content -->
		<?php endif; ?>
		<?php get_template_part( 'template-parts/post-templates/single-parts/nextprev' ); ?>
	</div><!-- #post-## -->
	<?php get_template_part( 'template-parts/post-templates/single-parts/single-boxes' ); ?>

	<?php
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

		endwhile; // End of the loop.
		?>

		<?php if ( get_field( 'display_related', 'options' ) ) : ?>
			<?php get_template_part( 'template-parts/related-posts' ); ?>
		<?php endif; ?>
	</main><!-- #main -->	

</div><!-- #primary -->
<?php get_footer(); ?>
<?php get_template_part( 'template-parts/post-templates/single-parts/modal-share' ); ?>