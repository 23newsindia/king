<?php
/**
 * Gallery Item Template
 *
 * @package King
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param array $item Gallery item
 * @param string $gclass Gallery class
 * @param bool $is_first Whether this is the first item
 */
function king_render_gallery_item($item, $gclass, $is_first = false) {
    if ($item['type'] === 'image') {
        $attrs = king_get_image_attributes($item, $is_first);
        
        if ('king-gallery-03' === $gclass) {
            printf(
                '<span class="images-item-span" style="background-image: url(%s);" data-bg-srcset="%s" data-sizes="%s"></span>',
                esc_url($item['sizes']['medium']), // Smaller initial image
                esc_attr($attrs['srcset']),
                esc_attr($attrs['sizes'])
            );
        } else {
            printf(
                '<img src="%s" alt="%s" width="%s" height="%s" loading="%s" decoding="%s" srcset="%s" sizes="%s">',
                esc_url($item['sizes']['medium']), // Smaller initial image
                esc_attr($item['alt']),
                esc_attr($item['width']),
                esc_attr($item['height']),
                esc_attr($attrs['loading']),
                esc_attr($attrs['decoding']),
                esc_attr($attrs['srcset']),
                esc_attr($attrs['sizes'])
            );
        }
    } elseif ($item['type'] === 'video') {
        printf(
            '<video width="720" height="480" controls preload="none" poster="%s"><source src="%s" type="video/mp4">%s</video>',
            esc_url($item['sizes']['medium']), // Use thumbnail as poster
            esc_url($item['url']),
            esc_html__('Your browser does not support the video tag.', 'king')
        );
    }
}