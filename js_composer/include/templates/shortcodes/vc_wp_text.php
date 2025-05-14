<?php
/**
 * The template for displaying [vc_wp_text] shortcode output of 'WP Text' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_wp_text.php.
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
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Wp_Text $this
 */
$el_class = $el_id = '';
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$atts['filter'] = true; // Hack to make sure that <p> added.
extract( $atts );

$el_class = $this->getExtraClass( $el_class );
$wrapper_attributes = [];
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output = '<div ' . implode( ' ', $wrapper_attributes ) . ' class="vc_wp_text wpb_content_element' . esc_attr( $el_class ) . '">';
$type = 'WP_Widget_Text';
$args = [];
$content = apply_filters( 'vc_wp_text_widget_shortcode', $content );
if ( strlen( $content ) > 0 ) {
	$atts['text'] = $content;
}
global $wp_widget_factory;
// to avoid unwanted warnings let's check before using widget.
if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
	ob_start();
	the_widget( $type, $atts, $args );
	$output .= ob_get_clean();

	$output .= '</div>';

	return $output;
}
