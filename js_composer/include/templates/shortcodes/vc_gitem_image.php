<?php
/**
 * The template for displaying [vc_gitem_image] shortcode of output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_gitem_image.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return '{{ featured_image: ' . http_build_query( $atts ) . ' }}';
