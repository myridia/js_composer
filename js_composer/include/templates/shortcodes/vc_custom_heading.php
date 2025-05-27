<?php
/**
 * The template for displaying [vc_custom_heading] shortcode output of 'Custom Heading' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_custom_heading.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 *
 * Shortcode attributes
 * @var $atts
 * @var $source
 * @var $text
 * @var $link
 * @var $google_fonts
 * @var $font_container
 * @var $el_class
 * @var $el_id
 * @var $css
 * @var $css_animation
 * @var $font_container_data - returned from $this->getAttributes
 * @var $google_fonts_data - returned from $this->getAttributes
 * @var $css_class
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Custom_heading $this
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$source = $text = $link = $google_fonts = $font_container = $el_id = $el_class = $css = $css_animation = $font_container_data = $google_fonts_data = [];
// This is needed to extract $font_container_data and $google_fonts_data.
extract( $this->getAttributes( $atts ) );

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$element_class = empty( $this->settings['element_default_class'] ) ? '' : $this->settings['element_default_class'];
extract( $this->getStyles( $element_class . ' ' . $el_class . $this->getCSSAnimation( $css_animation ), $css, $google_fonts_data, $font_container_data, $atts ) );

$this->enqueue_element_font_styles( $google_fonts_data, $atts );

if ( ! empty( $styles ) ) {
	$style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
} else {
	$style = '';
}

if ( 'post_title' === $source ) {
	$text = get_the_title( get_the_ID() );
}

if ( ! empty( $link ) ) {
	$link = vc_build_link( $link );
	$text = '<a href="' . esc_url( $link['url'] ) . '"' . ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' ) . ( $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : '' ) . ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' ) . '>' . $text . '</a>';
}
$wrapper_attributes = [];
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output = '';
$tag = tag_escape( $font_container_data['values']['tag'] );
if ( apply_filters( 'vc_custom_heading_template_use_wrapper', false ) ) {
	$output .= '<div class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . '>';
	$output .= '<' . $tag . ' ' . $style . ' >';
	$output .= $text;
	$output .= '</' . $tag . '>';
	$output .= '</div>';
} else {
	$output .= '<' . $tag . ' ' . $style . ' class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . '>';
	$output .= $text;
	$output .= '</' . $tag . '>';
}

return $output;
