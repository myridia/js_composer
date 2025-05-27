<?php
/**
 * The template for displaying [vc_gitem_block] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_gitem_block.php.
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
 * @var $background_color
 * @var $float
 * @var $content - shortcode content
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Gitem $this
 */
$el_class = $background_color = $float = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( ! empty( $background_color ) ) {
	$background_color = ' vc_bg-' . $background_color;
}

$output = '<div class="vc_gitem-block' . esc_attr( $background_color ) . ( strlen( $el_class ) > 0 ? ' ' . esc_attr( $el_class ) : '' ) . ' vc_gitem-float-' . esc_attr( $float ) . '">' . do_shortcode( $content ) . '</div>';

echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
