<?php
/**
 * Image Loader Component
 * Handles optimized image loading with proper aspect ratio and performance
 */

if (!defined('ABSPATH')) {
    exit;
}

function get_optimized_image_data($attachment_id, $is_above_fold = false) {
    // Get image size for mobile-first approach
    $mobile = wp_get_attachment_image_src($attachment_id, 'mobile-thumbnail');
    
    // Determine if this is potentially an LCP image
    $post_position = get_query_var('paged') > 1 ? -1 : get_query_var('post_position', 1);
    $is_lcp_candidate = $post_position <= 3 && $is_above_fold;
    
    return [
        'mobile' => $mobile ? $mobile[0] : '',
        'width' => $mobile ? $mobile[1] : '',
        'height' => $mobile ? $mobile[2] : '',
        'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
        'loading' => $is_lcp_candidate ? 'eager' : 'lazy',
        'fetchpriority' => $is_lcp_candidate ? 'high' : 'auto'
    ];
}
?>