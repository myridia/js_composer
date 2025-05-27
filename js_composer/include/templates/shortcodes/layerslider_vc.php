<?php
/**
 * The template for displaying [vc_layerslider] shortcode output of 'LayerSlider' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/layerslider_vc.php.
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
 * @var $id
 * @var $el_class
 * Shortcode class
 * @var WPBakeryShortCode_Layerslider_Vc $this
 */
$el_class = $title = $id = '';
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_layerslider_element wpb_content_element' . $el_class, $this->settings['base'], $atts );

$output .= '<div class="' . esc_attr( $css_class ) . '">';
$output .= wpb_widget_title( [
	'title' => $title,
	'extraclass' => 'wpb_layerslider_heading',
] );
$output .= apply_filters( 'vc_layerslider_shortcode', do_shortcode( '[layerslider id="' . esc_attr( $id ) . '"]' ) );
$output .= '</div>';

echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
