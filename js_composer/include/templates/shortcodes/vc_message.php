<?php
/**
 * The template for displaying [vc_message] shortcode output of 'Message box' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_message.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 *
 * @todo add $icon_... defaults
 * @todo add $icon_typicons and etc
 *
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $message_box_style
 * @var $style
 * @var $color
 * @var $message_box_color
 * @var $css_animation
 * @var $icon_type
 * @var $icon_fontawesome
 * @var $content - shortcode content
 * @var $css
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Message $this
 */
$el_class = $el_id = $message_box_color = $message_box_style = $style = $css = $color = $css_animation = $icon_type = '';
$icon_fontawesome = $icon_linecons = $icon_openiconic = $icon_typicons = $icon_entypo = '';
$default_icon_class = 'fa fa-adjust';
$atts = $this->convertAttributesToMessageBox2( $atts );
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$element_class_list = [
	'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_message_box', $this->settings['base'], $atts ),
	'style' => 'vc_message_box-' . $message_box_style,
	'shape' => 'vc_message_box-' . $style,
	'color' => ( ( strlen( $color ) > 0 && false === strpos( 'alert', $color ) ) ? ( 'vc_color-' . $color ) : ( 'vc_color-' . $message_box_color ) ),
	'css_animation' => $this->getCSSAnimation( $css_animation ),
];

$element_class = empty( $this->settings['element_default_class'] ) ? '' : $this->settings['element_default_class'];
$class_to_filter = preg_replace( [
	'/\s+/',
	'/^\s|\s$/',
], [
	' ',
	'',
], implode( ' ', $element_class_list ) );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . ' ' . esc_attr( $element_class ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

// Pick up icons.
$icon_class = isset( ${'icon_' . $icon_type} ) ? ${'icon_' . $icon_type} : $default_icon_class;
switch ( $color ) {
	case 'info':
		$icon_type = 'fontawesome';
		$icon_class = 'fas fa-info-circle';
		break;
	case 'alert-info':
		$icon_type = 'pixelicons';
		$icon_class = 'vc_pixel_icon vc_pixel_icon-info';
		break;
	case 'success':
		$icon_type = 'fontawesome';
		$icon_class = 'fas fa-check';
		break;
	case 'alert-success':
		$icon_type = 'pixelicons';
		$icon_class = 'vc_pixel_icon vc_pixel_icon-tick';
		break;
	case 'warning':
		$icon_type = 'fontawesome';
		$icon_class = 'fas fa-exclamation-triangle';
		break;
	case 'alert-warning':
		$icon_type = 'pixelicons';
		$icon_class = 'vc_pixel_icon vc_pixel_icon-alert';
		break;
	case 'danger':
		$icon_type = 'fontawesome';
		$icon_class = 'fas fa-times';
		break;
	case 'alert-danger':
		$icon_type = 'pixelicons';
		$icon_class = 'vc_pixel_icon vc_pixel_icon-explanation';
		break;
	case 'alert-custom':
	default:
		break;
}

// Enqueue needed font for icon element.
if ( 'pixelicons' !== $icon_type ) {
	vc_icon_element_fonts_enqueue( $icon_type );
}
$wrapper_attributes = [];
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output = '';
$output .= '<div class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . '><div class="vc_message_box-icon"><i class="' . esc_attr( $icon_class ) . '"></i></div>' . wpb_js_remove_wpautop( $content, true ) . '</div>';

return $output;
