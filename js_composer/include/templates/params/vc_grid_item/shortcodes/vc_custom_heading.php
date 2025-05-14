<?php
/**
 * Custom heading grid builder shortcode element.
 *
 * @var WPBakeryShortCode_Vc_Custom_heading $this
 * @var $atts
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

extract( $this->getAttributes( $atts ) );
extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );


$link = vc_gitem_create_link( $atts );
if ( ! empty( $link ) ) {
	$text = '<' . $link . '>' . $text . '</a>';
}

$this->enqueue_element_font_styles( $google_fonts_data );

if ( ! empty( $styles ) ) {
	$style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
} else {
	$style = '';
}

if ( isset( $atts['source'] ) && 'post_title' === $atts['source'] ) {
	$text = get_the_title( get_the_ID() );
}

$output = '';
$tag = tag_escape( $font_container_data['values']['tag'] );
if ( apply_filters( 'vc_custom_heading_template_use_wrapper', false ) ) {
	$output .= '<div class="' . esc_attr( $css_class ) . '" >';
	$output .= '<' . $tag . ' ' . $style . ' >';
	$output .= $text;
	$output .= '</' . $tag . '>';
	$output .= '</div>';
} else {
	$output .= '<' . $tag . ' ' . $style . ' class="' . esc_attr( $css_class ) . '">';
	$output .= $text;
	$output .= '</' . $tag . '>';
}

return $output;
