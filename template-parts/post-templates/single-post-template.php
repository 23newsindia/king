<?php
/**
 * Sible News page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main post-page single-post">
		<?php if ( get_field( 'ads_above_content', 'option' ) && king_add_free_mode() ) : ?>
			<div class="ads-postpage">
				<?php
				$ad_above = get_field( 'ads_above_content', 'options' );
				echo do_shortcode( $ad_above );
				?>
			</div>
		<?php endif; ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( get_field( 'add_sponsor' ) ) : ?>
					<div class="add-sponsor"><a href="<?php echo get_field( 'post_sponsor_link' ); ?>" target="_blank"><img src="<?php echo get_field( 'post_sponsor_logo' ); ?>" /></a><span class="sponsor-label"><?php echo get_field( 'post_sponsor_description' ); ?></span></div>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/post-templates/single-parts/posttitle' ); ?>
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

						<?php if ( have_rows( 'news_list_items' ) ) : ?>

							<div class="king-lists">
								<?php $i = 1; ?>
								<?php
								while ( have_rows( 'news_list_items' ) ) :
									the_row();
									$image   = get_sub_field( 'news_list_image' );
									$media   = get_sub_field( 'news_list_media' );
									$ltitle  = get_sub_field( 'news_list_title' );
									$content = get_sub_field( 'news_list_content' );
									?>
									<div class="list-item">
										<span class="list-item-title">
											<span class="list-item-number">#<?php echo esc_html( $i ); ?></span><h3><?php echo esc_html( $ltitle ); ?></h3></span>
										<?php if ( $image ) : ?>
											<span class="list-item-image">
												<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_html( $image['alt'] ) ?>" />
											</span>
										<?php endif; ?>
										<?php if ( $media ) : ?>
											<span class="list-item-media">
												<?php echo get_sub_field( 'news_list_media' ); ?>
											</span>
										<?php endif; ?>
										<span class="list-item-content">
											<?php echo wp_kses_post( $content ); ?>
										</span>
									</div>
									<?php
									$i++;
								endwhile;
								?>

							</div>

						<?php endif; ?>
					<?php endif; ?>
					<?php get_template_part( 'template-parts/post-templates/single-parts/nextprev' ); ?>
				</div><!-- #post-## -->
				<?php get_template_part( 'template-parts/post-templates/single-parts/single-boxes' ); ?>
				<?php
				if ( comments_open() || get_comments_number() ) :
					comments_template();
			endif;
		endwhile;
		?>
		<?php if ( get_field( 'display_related', 'options' ) ) : ?>
			<?php get_template_part( 'template-parts/related-posts' ); ?>
		<?php endif; ?>
	</main><!-- #main -->
	<?php get_sidebar(); ?> 	

</div><!-- #primary -->
<?php get_footer(); ?>
