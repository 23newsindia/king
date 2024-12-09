<?php
/**
 * Submit news page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( 'story' === get_query_var( 'template' ) ) {
	get_template_part( 'template', 'submit-story' );
} elseif ( 'ai' === get_query_var( 'template' ) ) {
	get_template_part( 'template', 'submit-ai' );
} else {
	get_template_part( 'template', 'submit-news' );
}
