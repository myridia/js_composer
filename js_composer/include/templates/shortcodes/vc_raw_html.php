<?php
/**
 * The template for displaying [vc_raw_html] shortcode output of 'Raw HTML' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_raw_html.php.
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
 * @var $el_id
 * @var $content - shortcode content
 * @var $css
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Raw_html $this
 */
$el_class = $el_id = $css = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

// phpcs:ignore
$content = rawurldecode( base64_decode( wp_strip_all_tags( $content ) ) );
$content = wpb_js_remove_wpautop( apply_filters( 'vc_raw_html_module_content', $content ) );

// template is also used by 'Raw JS' shortcode which doesn't have Design Options.
if ( ! isset( $css ) ) {
	$css = '';
}

$element_class = empty( $this->settings['element_default_class'] ) ? '' : $this->settings['element_default_class'];
$class_to_filter = 'wpb_raw_code ' . ( ( 'vc_raw_html' === $this->settings['base'] ) ? 'wpb_raw_html ' . esc_attr( $element_class ) : 'wpb_raw_js' );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$wrapper_attributes = [];
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output = '
	<div class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . '>
		<div class="wpb_wrapper">
			' . $content . '
		</div>
	</div>
';

return $output;
