<?php
/**
 * The template for displaying [vc_gitem_zone] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_gitem_zone.php.
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
 * @var $css
 * @var $render
 * @var $content - shortcode content
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Gitem_Zone $this
 */
$el_class = $css = $render = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

extract( $atts );

if ( 'no' === $render ) {
	echo '';

	return;
}
$css_class = 'vc_gitem-zone' . ( strlen( $this->zone_name ) ? ' vc_gitem-zone-' . $this->zone_name : '' ) . $this->getExtraClass( $el_class );

$css_class_mini = 'vc_gitem-zone-mini';
$css_class .= vc_shortcode_custom_css_class( $css, ' ' );

$output = '';

$output .= '<div class="' . esc_attr( $css_class ) . '"><div class="' . esc_attr( $css_class_mini ) . '">' . do_shortcode( $content ) . '</div></div>';

echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
