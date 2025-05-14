<?php
/**
 * Configuration file for [vc_flickr] shortcode of 'Flickr Widget' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'base' => 'vc_flickr',
	'name' => esc_html__( 'Flickr Widget', 'js_composer' ),
	'icon' => 'icon-wpb-flickr',
	'element_default_class' => 'wpb_content_element',
	'category' => esc_html__( 'Content', 'js_composer' ),
	'description' => esc_html__( 'Image feed from Flickr account', 'js_composer' ),
	'params' => [
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => esc_html__( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Flickr ID', 'js_composer' ),
			'param_name' => 'flickr_id',
			'value' => '95572727@N00',
			'admin_label' => true,
			'description' => sprintf( esc_html__( 'To find your flickID visit %s.', 'js_composer' ), '<a href="https://www.webfx.com/tools/idgettr/" target="_blank">idGettr</a>' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Number of photos', 'js_composer' ),
			'param_name' => 'count',
			'value' => [
				20,
				19,
				18,
				17,
				16,
				15,
				14,
				13,
				12,
				11,
				10,
				9,
				8,
				7,
				6,
				5,
				4,
				3,
				2,
				1,
			],
			'std' => 9, // bc.
			'description' => esc_html__( 'Select number of photos to display.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Type', 'js_composer' ),
			'param_name' => 'type',
			'value' => [
				esc_html__( 'User', 'js_composer' ) => 'user',
				esc_html__( 'Group', 'js_composer' ) => 'group',
			],
			'description' => esc_html__( 'Select photo stream type.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Display order', 'js_composer' ),
			'param_name' => 'display',
			'value' => [
				esc_html__( 'Latest first', 'js_composer' ) => 'latest',
				esc_html__( 'Random', 'js_composer' ) => 'random',
			],
			'description' => esc_html__( 'Select photo display order.', 'js_composer' ),
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
