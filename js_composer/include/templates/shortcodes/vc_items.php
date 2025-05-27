<?php
/**
 * The template for displaying [vc_items] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_items.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $el_class
 * @var $content - shortcode content
 * Shortcode class
 * @var WPBakeryShortCodesContainer $this
 */
$el_class = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );
$el_class .= ( ! empty( $el_class ) ? ' ' : '' ) . 'wpb_item items_container';

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class, $this->settings['base'], $atts );

$output = '
	<div class="' . esc_attr( $css_class ) . '">
		<div class="wpb_wrapper">
			' . wpb_js_remove_wpautop( $content ) . '
		</div>
	</div>
';

return $output;
