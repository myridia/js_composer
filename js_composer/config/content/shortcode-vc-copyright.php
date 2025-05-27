<?php
/**
 * Configuration file for [vc_copyright] shortcode of 'Copyright' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 *
 * @since 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'CONFIG_DIR', 'content/vc-custom-heading-element.php' );

$params = array_merge(
	[
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Prefix', 'js_composer' ),
			'value' => esc_html__( 'Copyright ', 'js_composer' ),
			'param_name' => 'prefix',
			'admin_label' => true,
			'description' => esc_html__( 'Text in the beginning', 'js_composer' ),
			'edit_field_class' => 'vc_col-sm-9',
		],
	],
	[
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Postfix', 'js_composer' ),
			'value' => esc_html__( ' All rights reserved', 'js_composer' ),
			'param_name' => 'postfix',
			'admin_label' => true,
			'description' => esc_html__( 'Text in the end', 'js_composer' ),
			'edit_field_class' => 'vc_col-sm-9',
		],
	],
	[
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Alignment', 'js_composer' ),
			'param_name' => 'align',
			'value' => [
				esc_html__( 'Left', 'js_composer' ) => 'left',
				esc_html__( 'Center', 'js_composer' ) => 'center',
				esc_html__( 'Right', 'js_composer' ) => 'right',
			],
			'description' => esc_html__( 'Select icon alignment.', 'js_composer' ),
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
	]
);

return [
	'name' => esc_html__( 'Copyright', 'js_composer' ),
	'base' => 'vc_copyright',
	'icon' => 'icon-wpb-copyright',
	'element_default_class' => 'wpb_copyright_element',
	'category' => esc_html__( 'Content', 'js_composer' ),
	'description' => esc_html__( 'Copyright with dynamic year', 'js_composer' ),
	'params' => $params,
];
