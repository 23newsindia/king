<?php
/**
 * Single music page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<?php
if ( get_field( 'video_tab', get_the_ID() ) ) :
	$videofile = get_field( 'video_upload', get_the_ID() );

		if ( has_post_thumbnail() ) :
			$audio_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
		else :
			$audio_thumb['0'] = '';
		endif;
?>	
	<div class="king-playlist-uo" style="background-image: url(<?php echo esc_url( $audio_thumb['0'] ); ?>);">
		<div class="king-playlist-in" >		
		<?php if ( $videofile ) : ?>
			
			<?php if ( $videofile['type'] === 'audio' ) :  ?>
				
					
					<div class="cd-cover" style="background-image: url(<?php echo esc_url( $audio_thumb['0'] ); ?>);"></div>
	
					<div class="vjs-playlist king-playlist-post" id="king-playlist" style="display:block;"></div>
					
				
				<div class="king-mplaylist">
						<div class="vjs-playlist" id="king-playlist" style="display:none;"></div>	
						<audio id="king-audio" class="video-js vjs-theme-sea"  autoplay controls="controls" preload poster="<?php echo esc_url( $audio_thumb['0'] ); ?>" data-setup="{}">

					<?php
					$out[] = ['name'=> get_the_title(),'sources'=> [['src' => $videofile['url'], 'type' => 'audio/mpeg', ]], 'poster'=>$audio_thumb['0']];
					if ( have_rows('music_list') ) :
						while ( have_rows( 'music_list' ) ) :
							the_row();
							$pl_title  = get_sub_field( 'music_title' );
							$pl_url    = get_sub_field( 'music_file' );
							$out[] = ['name'=>$pl_title,'sources'=> [['src' => $pl_url, 'type' => 'audio/mpeg', ]], 'poster'=>$audio_thumb['0']];
						endwhile;
					endif;
					?>	
					<script type="application/json" class="king-playlist-data"><?php echo wp_json_encode( $out ); ?></script>					
						</audio>
					</div>
			<?php elseif ( $videofile['type'] === 'video' ) : ?>
				<video id="king-video" class="video-js vjs-theme-forest" controls preload="auto" poster="<?php echo esc_url( $audio_thumb['0'] ); ?>" data-setup='{}'>
					<source src="<?php echo esc_url( $videofile['url'] ); ?>" type="video/mp4"></source>
				</video>
			<?php endif; ?>
		<?php endif; ?>
		</div>
</div>
	<?php else : ?>
		<div class="post-video embed-responsive embed-responsive-16by9 floating-video" > 
			<?php echo get_field( 'video-url', get_the_ID() ); ?>
		</div>

<?php endif; ?>


<div id="primary" class="content-area">
	<main id="main" class="site-main post-page single-video">
		<?php if ( get_field( 'ads_above_content', 'option' ) && king_add_free_mode() ) : ?>
			<div class="ads-postpage">
				<?php
				$ad_above = get_field( 'ads_above_content', 'options' );
				echo do_shortcode( $ad_above );
				?>
			</div>
		<?php endif; ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( get_field( 'add_sponsor' ) ) : ?>
					<div class="add-sponsor"><a href="<?php echo get_field( 'post_sponsor_link' ); ?>" target="_blank"><img src="<?php echo get_field( 'post_sponsor_logo' ); ?>" /></a><span class="sponsor-label"><?php echo get_field( 'post_sponsor_description' ); ?></span></div>
				<?php endif; ?>			
				<?php get_template_part( 'template-parts/post-templates/single-parts/posttitle' ); ?>
				<?php get_template_part( 'template-parts/post-templates/single-parts/badges' ); ?>
				<div class="entry-content">
					<?php
					the_content(
						sprintf(
							wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'king' ), array( 'span' => array( 'class' => array() ) ) ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						)
					);

					wp_link_pages(
						array(
							'before' => '<div class="page-links">',
							'after'  => '</div>',
						)
					);
					?>
				</div><!-- .entry-content -->

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
		<?php if ( get_post_status( $post->ID ) === 'pending' ) : ?>
			<div class="king-pending"><?php esc_html_e( 'This Music post will be checked and approved shortly.', 'king' ); ?></div>
		<?php endif; ?>
	</main><!-- #main -->
	<?php get_sidebar(); ?> 	

</div><!-- #primary -->
<?php get_template_part( 'template-parts/post-templates/single-parts/modal-share' ); ?>
<?php get_footer(); ?>
