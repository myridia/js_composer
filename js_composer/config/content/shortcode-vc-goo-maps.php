<?php
/**
 * Configuration file for [wpb_goo_maps] shortcode of 'Google Maps' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'name' => esc_html__( 'Google Maps', 'js_composer' ),
	'base' => 'vc_goo_maps',
	'icon' => 'icon-wpb-map-pin',
	'element_default_class' => 'wpb_content_element',
	'category' => esc_html__( 'Content', 'js_composer' ),
	'description' => esc_html__( 'Map block', 'js_composer' ),
	'params' => [
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Location', 'js_composer' ),
			'param_name' => 'location',
			'value' => 'London Eye, London, United Kingdom',
			'admin_label' => true,
			'description' => esc_html__( 'Enter a location for the map. You can specify an address, city, country, or coordinates.', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Map height', 'js_composer' ),
			'param_name' => 'height',
			'value' => '',
			'admin_label' => true,
			'description' => esc_html__( 'Enter map height (in pixels or leave empty for responsive map).', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Zoom', 'js_composer' ),
			'param_name' => 'zoom',
			'value' => '10',
			'admin_label' => true,
			'description' => esc_html__( 'Adjust the map zoom level (0–22). You can set it to a specific value to control how close or far the view appears.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Map Type', 'js_composer' ),
			'param_name' => 'type',
			'value' => [
				esc_html__( 'Default', 'js_composer' ) => 'm',
				esc_html__( 'Satellite', 'js_composer' ) => 'k',
				esc_html__( 'Hybrid', 'js_composer' ) => 'h',
			],
			'admin_label' => true,
			'description' => esc_html__( 'Select the type of map to display.', 'js_composer' ),
		],
		vc_map_add_css_animation(),
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
		[
			'type' => 'css_editor',
			'heading' => esc_html__( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => esc_html__( 'Design Options', 'js_composer' ),
			'value' => [
				'margin-bottom' => '35px',
			],
		],
	],
];
