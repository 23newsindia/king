<?php
/**
 * Gallery Helper Functions
 *
 * @package King
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get gallery configuration
 *
 * @return array Gallery settings
 */
function king_get_gallery_config() {
    return array(
        'gallery_class' => get_field( 'gallery_layout', 'option' ) ?: 'king-gallery-01',
        'lightbox_enabled' => get_field( 'enable_lightbox_gallery', 'option' ),
        'gallery_items' => get_field( 'images_gallery' )
    );
}

/**
 * Generate srcset for responsive images
 *
 * @param array $image Image array from ACF
 * @return string
 */
function king_get_image_srcset($image) {
    if (empty($image['sizes'])) {
        return '';
    }

    $srcset = array();
    $sizes = array('thumbnail', 'medium', 'medium_large', 'large');
    
    foreach ($sizes as $size) {
        if (!empty($image['sizes'][$size])) {
            $srcset[] = $image['sizes'][$size] . ' ' . $image['sizes'][$size . '-width'] . 'w';
        }
    }

    return implode(', ', $srcset);
}

/**
 * Get image loading attributes
 *
 * @param array $image Image array
 * @param bool $is_first Whether this is the first image
 * @return array
 */
function king_get_image_attributes($image, $is_first = false) {
    return array(
        'loading' => $is_first ? 'eager' : 'lazy',
        'decoding' => 'async',
        'srcset' => king_get_image_srcset($image),
        'sizes' => '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw'
    );
}