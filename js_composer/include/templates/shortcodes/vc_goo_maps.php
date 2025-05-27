<?php
/**
 * The template for displaying [vc_goo_maps] shortcode output of 'Google Maps' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_goo_maps.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 *
 * @var array $atts
 * @var string $title
 * @var string $location
 * @var string $zoom
 * @var string $size
 * @var string $type
 * @var string $el_class
 * @var string $el_id
 * @var string $css
 * @var string $css_animation
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Goo_Maps $this
 */

$location = $height = $zoom = $el_class = $css = $css_animation = $type = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( $atts );

if ( '' === $location ) {
	return '';
}

$element_class = empty( $this->settings['element_default_class'] ) ? '' : $this->settings['element_default_class'];
$class_to_filter = 'wpb_gmaps_widget ' . esc_attr( $element_class ) . ( '' === $height ? ' vc_map_responsive' : '' );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$output .= '<div class="' . esc_attr( $css_class ) . '"' . ( ! empty( $el_id ) ? ' id="' . esc_attr( $el_id ) . '"' : '' ) . '>';

$output .= '<div class="wpb_wrapper"><div class="wpb_map_wraper">' .
		'<iframe loading="lazy" ' .
			'src="' . esc_url( $this->getIframeLink( $atts ) ) . '" ' .
			'title="' . esc_attr( $atts['location'] ) . '" ' .
			'aria-label="' . esc_attr( $atts['location'] ) . '" ' .
			( '' !== $height ? 'height="' . esc_attr( $height ) . '" ' : '' ) .
		'></iframe>';
$output .= '</div></div></div>';

return $output;
