<?php
/**
 * The template for displaying [vc_copyright] shortcode output of 'Copyright' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_copyright.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 *
 * @since 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 *
 * @var $atts
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Copyright $this
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->buildTemplate( $atts );
$css_class = trim( 'vc_general ' . esc_attr( implode( ' ', $this->getTemplateVariable( 'css-class' ) ) ) );
$css_class .= ' wpb_copyright_element-align-' . esc_attr( $atts['align'] );
$id = empty( $atts['el_id'] ) ? '' : ' id="' . esc_attr( $atts['el_id'] ) . '"';
$output = '<p ' . $id . ' class="' . esc_attr( $css_class ) . '"';

$output .= '>';
$output .= $atts['prefix'];
$output .= '&copy; ';
$output .= gmdate( 'Y' );
$output .= $atts['postfix'];

$output .= '</p>';

return $output;
