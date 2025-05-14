<?php
/**
 * The template for displaying [vc_gutenberg] shortcode output of 'Gutenberg' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_gutenberg.php.
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
 * @var $el_id
 * @var $css_animation
 * @var $css
 * @var $do_blocks - used only for ajax rendering in frontend editor.
 * @var $content - shortcode content
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Gutenberg $this
 */
$el_class = $el_id = $css = $css_animation = $do_blocks = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$element_class = empty( $this->settings['element_default_class'] ) ? '' : $this->settings['element_default_class'];
$class_to_filter = 'vc_gutenberg ' . esc_attr( $element_class ) . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$wrapper_attributes = [];
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$content = 'true' === $do_blocks ? do_blocks( $content ) : $content;

$output = '
	<div class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . '>
		<div class="wpb_wrapper">
			' . wpb_js_remove_wpautop( $content, true ) . '
		</div>
	</div>
';

return $output;
