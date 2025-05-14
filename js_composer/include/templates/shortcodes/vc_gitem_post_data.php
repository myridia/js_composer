<?php
/**
 * The template for displaying [vc_gitem_post_data] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_gitem_post_data.php.
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
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Gitem_Post_Data $this
 */
$output = $text = $google_fonts = $font_container = $el_class = $css = $link_html = '';
$font_container_data = [];
$google_fonts_data = [];
extract( $this->getAttributes( $atts ) );

extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );

$data_source = $this->getDataSource( $atts );
if ( isset( $atts['link'] ) && '' !== $atts['link'] && 'none' !== $atts['link'] ) {
	$link_html = vc_gitem_create_link( $atts );
}
$use_custom_fonts = isset( $atts['use_custom_fonts'] ) && 'yes' === $atts['use_custom_fonts'];

$content = '{{ post_data:' . esc_attr( $data_source ) . ' }}';
if ( ! empty( $link_html ) ) {
	$content = '<' . $link_html . '>' . $content . '</a>';
}
$css_class .= ' vc_gitem-post-data';
if ( $data_source ) {
	$css_class .= ' vc_gitem-post-data-source-' . $data_source;
}
if ( $use_custom_fonts ) {
	$this->enqueue_element_font_styles( $google_fonts_data );
}
$output .= '<div class="' . esc_attr( $css_class ) . '" >';
$style = '';
if ( ! empty( $styles ) ) {
	$style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
}
$tag = tag_escape( $font_container_data['values']['tag'] );
$output .= '<' . $tag . ' ' . $style . ' >';
$output .= $content;
$output .= '</' . $tag . '>';
$output .= '</div>';

return $output;
