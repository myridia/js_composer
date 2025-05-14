<?php
/**
 * Configuration file for [vc_raw_js] shortcode of 'Raw JS' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$custom_tag = 'script';

return [
	'name' => esc_html__( 'Raw JS', 'js_composer' ),
	'base' => 'vc_raw_js',
	'icon' => 'icon-wpb-raw-javascript',
	'category' => esc_html__( 'Structure', 'js_composer' ),
	'wrapper_class' => 'clearfix',
	'description' => esc_html__( 'Output raw JavaScript code on your page', 'js_composer' ),
	'params' => [
		[
			'type'        => 'textarea_ace',
			'heading' => esc_html__( 'JavaScript Code', 'js_composer' ),
			'param_name'  => 'content',
			'mode'        => 'html',
			'holder' => 'div',
			'value' => base64_encode( '<' . $custom_tag . '> alert( "' . esc_attr__( 'Enter your js here!', 'js_composer' ) . '" ) </' . $custom_tag . '>' ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			'description' => esc_html__( 'Enter your JavaScript code.', 'js_composer' ),
		],
		[
			'type' => 'el_id',
			'heading' => esc_html__( 'Element ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %1$sw3c specification%2$s).', 'js_composer' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		],
	],
];
