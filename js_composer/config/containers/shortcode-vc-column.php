<?php
/**
 * Configuration file for [vc_column] shortcode of 'Column' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'name' => esc_html__( 'Column', 'js_composer' ),
	'icon' => 'icon-wpb-row',
	'is_container' => true,
	'content_element' => false,
	'description' => esc_html__( 'Place content elements inside the column', 'js_composer' ),
	'params' => [
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Use video background?', 'js_composer' ),
			'param_name' => 'video_bg',
			'description' => esc_html__( 'If checked, video will be used as row background.', 'js_composer' ),
			'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'YouTube link', 'js_composer' ),
			'param_name' => 'video_bg_url',
			'value' => 'https://www.youtube.com/watch?v=lMJXxhRFO1k',
			// default video url.
			'description' => esc_html__( 'Add YouTube link.', 'js_composer' ),
			'dependency' => [
				'element' => 'video_bg',
				'not_empty' => true,
			],
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Parallax', 'js_composer' ),
			'param_name' => 'video_bg_parallax',
			'value' => [
				esc_html__( 'None', 'js_composer' ) => '',
				esc_html__( 'Simple', 'js_composer' ) => 'content-moving',
				esc_html__( 'With fade', 'js_composer' ) => 'content-moving-fade',
			],
			'description' => esc_html__( 'Add parallax type background for row.', 'js_composer' ),
			'dependency' => [
				'element' => 'video_bg',
				'not_empty' => true,
			],
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Parallax', 'js_composer' ),
			'param_name' => 'parallax',
			'value' => [
				esc_html__( 'None', 'js_composer' ) => '',
				esc_html__( 'Simple', 'js_composer' ) => 'content-moving',
				esc_html__( 'With fade', 'js_composer' ) => 'content-moving-fade',
			],
			'description' => esc_html__( 'Add parallax type background for row (Note: If no image is specified, parallax will use background image from Design Options).', 'js_composer' ),
			'dependency' => [
				'element' => 'video_bg',
				'is_empty' => true,
			],
		],
		[
			'type' => 'attach_image',
			'heading' => esc_html__( 'Image', 'js_composer' ),
			'param_name' => 'parallax_image',
			'value' => '',
			'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
			'dependency' => [
				'element' => 'parallax',
				'not_empty' => true,
			],
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Parallax speed', 'js_composer' ),
			'param_name' => 'parallax_speed_video',
			'value' => '1.5',
			'description' => esc_html__( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'js_composer' ),
			'dependency' => [
				'element' => 'video_bg_parallax',
				'not_empty' => true,
			],
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Parallax speed', 'js_composer' ),
			'param_name' => 'parallax_speed_bg',
			'value' => '1.5',
			'description' => esc_html__( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'js_composer' ),
			'dependency' => [
				'element' => 'parallax',
				'not_empty' => true,
			],
		],
		vc_map_add_css_animation( false ),
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
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Width', 'js_composer' ),
			'param_name' => 'width',
			'value' => [
				esc_html__( '1/12 - 1 column', 'js_composer' ) => '1/12',
				esc_html__( '1/6 - 2 columns', 'js_composer' ) => '1/6',
				esc_html__( '1/4 - 3 columns', 'js_composer' ) => '1/4',
				esc_html__( '1/3 - 4 columns', 'js_composer' ) => '1/3',
				esc_html__( '5/12 - 5 columns', 'js_composer' ) => '5/12',
				esc_html__( '1/2 - 6 columns', 'js_composer' ) => '1/2',
				esc_html__( '7/12 - 7 columns', 'js_composer' ) => '7/12',
				esc_html__( '2/3 - 8 columns', 'js_composer' ) => '2/3',
				esc_html__( '3/4 - 9 columns', 'js_composer' ) => '3/4',
				esc_html__( '5/6 - 10 columns', 'js_composer' ) => '5/6',
				esc_html__( '11/12 - 11 columns', 'js_composer' ) => '11/12',
				esc_html__( '1/1 - 12 columns', 'js_composer' ) => '1/1',
				esc_html__( '1/5 - 20%', 'js_composer' ) => '1/5',
				esc_html__( '2/5 - 40%', 'js_composer' ) => '2/5',
				esc_html__( '3/5 - 60%', 'js_composer' ) => '3/5',
				esc_html__( '4/5 - 80%', 'js_composer' ) => '4/5',
			],
			'group' => esc_html__( 'Responsive Options', 'js_composer' ),
			'description' => esc_html__( 'Select column width.', 'js_composer' ),
			'std' => '1/1',
		],
		[
			'type' => 'column_offset',
			'heading' => esc_html__( 'Responsiveness', 'js_composer' ),
			'param_name' => 'offset',
			'group' => esc_html__( 'Responsive Options', 'js_composer' ),
			'description' => esc_html__( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'js_composer' ),
		],
	],
	'js_view' => 'VcColumnView',
];
