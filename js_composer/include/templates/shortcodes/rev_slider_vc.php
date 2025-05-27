<?php
/**
 * The template for displaying [rev_slider_vc] shortcode output of 'Revolution Slider' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/rev_slider_vc.php.
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
 * @var $alias
 * @var $el_class
 * Shortcode class
 * @var WPBakeryShortCode_Rev_Slider_Vc $this
 */
$title = $alias = $el_class = '';
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_revslider_element wpb_content_element' . $el_class, $this->settings['base'], $atts );

$output .= '<div class="' . esc_attr( $css_class ) . '">';
$output .= wpb_widget_title( [
	'title' => $title,
	'extraclass' => 'wpb_revslider_heading',
] );
$output .= apply_filters( 'vc_revslider_shortcode', do_shortcode( '[rev_slider alias="' . $alias . '"]' ) );
$output .= '</div>';

echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
