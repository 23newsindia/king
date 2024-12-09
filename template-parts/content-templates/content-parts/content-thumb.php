<?php
/**
 * Optimized Thumbnail Template
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include the image loader component
require_once get_template_directory() . '/template-parts/components/image-loader.php';

if (get_field('nsfw_post') && !is_user_logged_in()) {
    get_template_part('template-parts/components/nsfw-overlay');
} else {
    if (has_post_thumbnail()) :
        $attachment_id = get_post_thumbnail_id(get_the_ID());
        $image_meta = wp_get_attachment_metadata($attachment_id);
        $original_width = $image_meta['width'];
        $original_height = $image_meta['height'];
        
        // Get post position in the loop
        global $wp_query;
        $post_position = $wp_query->current_post + 1;
        set_query_var('post_position', $post_position);
        
        // Determine if this is above the fold
        $is_above_fold = $post_position <= 3;
        
        // Get optimized image data
        $image_data = get_optimized_image_data($attachment_id, $is_above_fold);
        ?>
        <a href="<?php the_permalink(); ?>" 
           class="entry-image-link" 
           aria-label="<?php the_title_attribute(); ?>">
            <div class="image-container" style="aspect-ratio: <?php echo $original_width; ?>/<?php echo $original_height; ?>;">
                <?php if ($is_above_fold) : ?>
                    <link rel="preload" 
                          as="image" 
                          href="<?php echo esc_url($image_data['mobile']); ?>">
                <?php endif; ?>
                
                <img 
                    alt="<?php echo esc_attr($image_data['alt']); ?>"
                    src="<?php echo esc_url($image_data['mobile']); ?>"
                    width="<?php echo esc_attr($image_data['width']); ?>"
                    height="<?php echo esc_attr($image_data['height']); ?>"
                    class="entry-image"
                    loading="<?php echo esc_attr($image_data['loading']); ?>"
                    decoding="async"
                    fetchpriority="<?php echo esc_attr($image_data['fetchpriority']); ?>"
                />
            </div>
        </a>
    <?php endif;
}

get_template_part('template-parts/content-templates/content-parts/content-ft');
