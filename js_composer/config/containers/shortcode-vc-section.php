<?php
/**
 * Configuration file for [vc_section] shortcode of 'Section' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'name' => esc_html__( 'Section', 'js_composer' ),
	'is_container' => true,
	'icon' => 'vc_icon-vc-section',
	'show_settings_on_create' => false,
	'category' => esc_html__( 'Content', 'js_composer' ),
	'as_parent' => [
		'only' => 'vc_row',
	],
	'as_child' => [
		'only' => '', // Only root.
	],
	'class' => 'vc_main-sortable-element',
	'description' => esc_html__( 'Group multiple rows in section', 'js_composer' ),
	'params' => [
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Section stretch', 'js_composer' ),
			'param_name' => 'full_width',
			'value' => [
				esc_html__( 'Default', 'js_composer' ) => '',
				esc_html__( 'Stretch section', 'js_composer' ) => 'stretch_row',
				esc_html__( 'Stretch section and content', 'js_composer' ) => 'stretch_row_content',
			],
			'description' => esc_html__( 'Select stretching options for section and content (Note: stretched may not work properly if parent container has "overflow: hidden" CSS property).', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Minimum height', 'js_composer' ),
			'param_name' => 'min_height',
			'description' => esc_html__( 'Set minimum height for the container.', 'js_composer' ),
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Full height section?', 'js_composer' ),
			'param_name' => 'full_height',
			'description' => esc_html__( 'If checked section will be set to full height.', 'js_composer' ),
			'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Content position', 'js_composer' ),
			'param_name' => 'content_placement',
			'value' => [
				esc_html__( 'Default', 'js_composer' ) => '',
				esc_html__( 'Top', 'js_composer' ) => 'top',
				esc_html__( 'Middle', 'js_composer' ) => 'middle',
				esc_html__( 'Bottom', 'js_composer' ) => 'bottom',
			],
			'description' => esc_html__( 'Select content position within section.', 'js_composer' ),
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Use video background?', 'js_composer' ),
			'param_name' => 'video_bg',
			'description' => esc_html__( 'If checked, video will be used as section background.', 'js_composer' ),
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
			'description' => esc_html__( 'Add parallax type background for section.', 'js_composer' ),
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
			'description' => esc_html__( 'Add parallax type background for section (Note: If no image is specified, parallax will use background image from Design Options).', 'js_composer' ),
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
			'heading' => esc_html__( 'Section ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %1$sw3c specification%2$s).', 'js_composer' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Disable section', 'js_composer' ),
			'param_name' => 'disable_element',
			// Inner param name.
			'description' => esc_html__( 'If checked the section won\'t be visible on the public side of your website. You can switch it back any time.', 'js_composer' ),
			'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
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
	],
	'js_view' => 'VcSectionView',
];
