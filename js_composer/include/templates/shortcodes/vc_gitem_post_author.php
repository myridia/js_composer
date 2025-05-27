<?php
/**
 * The template for displaying [vc_gitem_post_author] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_gitem_post_author.php.
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
 * @var WPBakeryShortCode_Vc_Gitem_Post_Author $this
 */

$atts = $this->getAttributes( $atts );

$styles = $this->getStyles( $atts['el_class'], $atts['css'], $atts['google_fonts_data'], $atts['font_container_data'], $atts );
$google_fonts_data = [];
extract( $this->getAttributes( $atts ) );
if ( ! empty( $atts['link'] ) ) {
	$atts['link'] = 'post_author';
	$link_html = vc_gitem_create_link( $atts );
}

$settings = get_option( 'wpb_js_google_fonts_subsets' );
$subsets = '';
if ( is_array( $settings ) && ! empty( $settings ) ) {
	$subsets = '&subset=' . implode( ',', $settings );
}
$content = '{{ post_author }}';
if ( ! empty( $link_html ) ) {
	$content = '<' . $link_html . '>' . $content . '</a>';
}
$css_class = [
	$styles['css_class'],
	'vc_gitem-post-data',
];
$css_class[] = 'vc_gitem-post-data-source-post_author';

$this->enqueue_element_font_styles( $google_fonts_data );

$output .= '<div class="' . esc_attr( implode( ' ', $css_class ) ) . '" >';
$style = '';
if ( ! empty( $styles['styles'] ) ) {
	$style = 'style="' . esc_attr( implode( ';', $styles['styles'] ) ) . '"';
}
$tag = tag_escape( $atts['font_container_data']['values']['tag'] );
$output .= '<' . $tag . ' ' . $style . ' >';
$output .= $content;
$output .= '</' . $tag . '>';
$output .= '</div>';

return $output;
