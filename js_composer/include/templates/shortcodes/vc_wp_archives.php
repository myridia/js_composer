<?php
/**
 * The template for displaying [vc_wp_archives] shortcode output of 'WP Archives' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_wp_archives.php.
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
 * @var $title
 * @var $options
 * @var $el_class
 * @var $el_id
 * Shortcode class
 * @var WPBakeryShortCode $this
 */
$title = $el_class = $el_id = $options = '';
$output = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$options = explode( ',', $options );
if ( in_array( 'dropdown', $options, true ) ) {
	$atts['dropdown'] = true;
}
if ( in_array( 'count', $options, true ) ) {
	$atts['count'] = true;
}

$el_class = $this->getExtraClass( $el_class );
$wrapper_attributes = [];
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output = '<div ' . implode( ' ', $wrapper_attributes ) . ' class="vc_wp_archives wpb_content_element' . esc_attr( $el_class ) . '">';
$type = 'WP_Widget_Archives';
$args = [];
global $wp_widget_factory;
// to avoid unwanted warnings let's check before using widget.
if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
	ob_start();
	the_widget( $type, $atts, $args );
	$output .= ob_get_clean();

	$output .= '</div>';

	return $output;
}
