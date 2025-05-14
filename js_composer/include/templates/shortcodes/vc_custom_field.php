<?php
/**
 * The template for displaying [vc_custom_field] shortcode.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_custom_field.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 *
 * @var array $atts
 * @var string $field_key
 * @var string $custom_field_key
 * @var string $el_class
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$field_key = $custom_field_key = $el_class = '';

extract( shortcode_atts( [
	'field_key' => '',
	'custom_field_key' => '',
	'el_class' => '',
], $atts ) );

$key = strlen( $custom_field_key ) > 0 ? $custom_field_key : $field_key;

$output = '';
if ( strlen( $key ) ) {
	$output .= '<div class="vc_gitem-custom-field-' . esc_attr( $key ) . '">{{ post_meta_value: ' . esc_attr( $key ) . '}}</div>';
}

return $output;
