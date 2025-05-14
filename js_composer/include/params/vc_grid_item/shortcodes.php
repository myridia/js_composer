<?php
/**
 * Shortcode attributes for vc_grid_item.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_Vc_Gitem_Animated_Block' );

$vc_gitem_add_link_param = apply_filters( 'vc_gitem_add_link_param', [
	'type' => 'dropdown',
	'heading' => esc_html__( 'Add link', 'js_composer' ),
	'param_name' => 'link',
	'value' => [
		esc_html__( 'None', 'js_composer' ) => 'none',
		esc_html__( 'Post link', 'js_composer' ) => 'post_link',
		esc_html__( 'Post author', 'js_composer' ) => 'post_author',
		esc_html__( 'Large image', 'js_composer' ) => 'image',
		esc_html__( 'Large image (prettyPhoto)', 'js_composer' ) => 'image_lightbox',
		esc_html__( 'Full image', 'js_composer' ) => 'image_full',
		esc_html__( 'Full image (prettyPhoto)', 'js_composer' ) => 'image_full_lightbox',
		esc_html__( 'Custom', 'js_composer' ) => 'custom',
	],
	'description' => esc_html__( 'Select link option.', 'js_composer' ),
] );
$vc_gitem_add_link_target_param = apply_filters( 'vc_gitem_add_link_target_param', [
	'type' => 'checkbox',
	'heading' => esc_html__( 'Open link in a new tab', 'js_composer' ),
	'param_name' => 'link_target',
	'value' => '',
	'description' => esc_html__( 'Select link target window.', 'js_composer' ),
	'dependency' => [
		'element' => 'link',
		'value_not_equal_to' => [ 'custom', 'none' ],
	],
] );
$zone_params = [
	$vc_gitem_add_link_param,
	$vc_gitem_add_link_target_param,
	[
		'type' => 'vc_link',
		'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
		'param_name' => 'url',
		'dependency' => [
			'element' => 'link',
			'value' => [ 'custom' ],
		],
		'description' => esc_html__( 'Add custom link.', 'js_composer' ),
	],
	[
		'type' => 'checkbox',
		'heading' => esc_html__( 'Use featured image on background?', 'js_composer' ),
		'param_name' => 'featured_image',
		'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
		'description' => esc_html__( 'Note: Featured image overwrites background image and color from "Design Options".', 'js_composer' ),
	],
	[
		'type' => 'textfield',
		'heading' => esc_html__( 'Image size', 'js_composer' ),
		'param_name' => 'img_size',
		'value' => 'large',
		'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'js_composer' ),
		'dependency' => [
			'element' => 'featured_image',
			'not_empty' => true,
		],
	],
	[
		'type' => 'css_editor',
		'heading' => esc_html__( 'CSS box', 'js_composer' ),
		'param_name' => 'css',
		'group' => esc_html__( 'Design Options', 'js_composer' ),
	],
	[
		'type' => 'textfield',
		'heading' => esc_html__( 'Extra class name', 'js_composer' ),
		'param_name' => 'el_class',
		'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
	],
];
$post_data_params = [
	$vc_gitem_add_link_param,
	$vc_gitem_add_link_target_param,
	[
		'type' => 'vc_link',
		'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
		'param_name' => 'url',
		'dependency' => [
			'element' => 'link',
			'value' => [ 'custom' ],
		],
		'description' => esc_html__( 'Add custom link.', 'js_composer' ),
	],
	[
		'type' => 'css_editor',
		'heading' => esc_html__( 'CSS box', 'js_composer' ),
		'param_name' => 'css',
		'group' => esc_html__( 'Design Options', 'js_composer' ),
	],
];
$custom_fonts_params = [
	[
		'type' => 'font_container',
		'param_name' => 'font_container',
		'value' => '',
		'settings' => [
			'fields' => [
				'tag' => 'div',
				// default value h2.
				'text_align',
				'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
				'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
				'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
				'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
				'color_description' => esc_html__( 'Select color for your element.', 'js_composer' ),
			],
		],
	],
	[
		'type' => 'checkbox',
		'heading' => esc_html__( 'Use custom fonts?', 'js_composer' ),
		'param_name' => 'use_custom_fonts',
		'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
		'description' => esc_html__( 'Enable custom font option.', 'js_composer' ),
	],
	[
		'type' => 'font_container',
		'param_name' => 'block_container',
		'value' => '',
		'settings' => [
			'fields' => [
				'font_size',
				'line_height',
				'color',
				'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
				'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
				'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
				'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
				'color_description' => esc_html__( 'Select color for your element.', 'js_composer' ),
			],
		],
		'group' => esc_html__( 'Custom fonts', 'js_composer' ),
		'dependency' => [
			'element' => 'use_custom_fonts',
			'value' => [ 'yes' ],
		],
	],
	[
		'type' => 'checkbox',
		'heading' => esc_html__( 'Yes theme default font family?', 'js_composer' ),
		'param_name' => 'use_theme_fonts',
		'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
		'description' => esc_html__( 'Yes font family from the theme.', 'js_composer' ),
		'group' => esc_html__( 'Custom fonts', 'js_composer' ),
		'dependency' => [
			'element' => 'use_custom_fonts',
			'value' => [ 'yes' ],
		],
	],
	[
		'type' => 'google_fonts',
		'param_name' => 'google_fonts',
		'value' => '',
		// Not recommended, this will override 'settings'.
		// 'font_family:'.rawurlencode('Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic').'|font_style:'.rawurlencode('900 bold italic:900:italic').
		'settings' => [
			'fields' => [
				// Default font style. Name:weight:style, example: "800 bold regular:800:normal".
				'font_family_description' => esc_html__( 'Select font family.', 'js_composer' ),
				'font_style_description' => esc_html__( 'Select font styling.', 'js_composer' ),
			],
		],
		'group' => esc_html__( 'Custom fonts', 'js_composer' ),
		'dependency' => [
			'element' => 'use_theme_fonts',
			'value_not_equal_to' => 'yes',
		],
	],
];
$list = [
	'vc_gitem' => [
		'name' => esc_html__( 'Grid Item', 'js_composer' ),
		'base' => 'vc_gitem',
		'is_container' => true,
		'icon' => 'icon-wpb-gitem',
		'content_element' => false,
		'show_settings_on_create' => false,
		'category' => esc_html__( 'Content', 'js_composer' ),
		'description' => esc_html__( 'Main grid item', 'js_composer' ),
		'params' => [
			[
				'type' => 'css_editor',
				'heading' => esc_html__( 'CSS box', 'js_composer' ),
				'param_name' => 'css',
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			],
		],
		'js_view' => 'VcGitemView',
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_animated_block' => [
		'base' => 'vc_gitem_animated_block',
		'name' => esc_html__( 'A/B block', 'js_composer' ),
		'content_element' => false,
		'is_container' => true,
		'show_settings_on_create' => false,
		'icon' => 'icon-wpb-gitem-block',
		'category' => esc_html__( 'Content', 'js_composer' ),
		'controls' => [],
		'as_parent' => [
			'only' => [
				'vc_gitem_zone_a',
				'vc_gitem_zone_b',
			],
		],
		'params' => [
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Animation', 'js_composer' ),
				'param_name' => 'animation',
				'value' => WPBakeryShortCode_Vc_Gitem_Animated_Block::animations(),
			],
		],
		'js_view' => 'VcGitemAnimatedBlockView',
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_zone' => [
		'name' => esc_html__( 'Zone', 'js_composer' ),
		'base' => 'vc_gitem_zone',
		'content_element' => false,
		'is_container' => true,
		'show_settings_on_create' => false,
		'icon' => 'icon-wpb-gitem-zone',
		'category' => esc_html__( 'Content', 'js_composer' ),
		'controls' => [ 'edit' ],
		'as_parent' => [ 'only' => 'vc_gitem_row' ],
		'js_view' => 'VcGitemZoneView',
		'params' => $zone_params,
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_zone_a' => [
		'name' => esc_html__( 'Normal', 'js_composer' ),
		'base' => 'vc_gitem_zone_a',
		'content_element' => false,
		'is_container' => true,
		'show_settings_on_create' => false,
		'icon' => 'icon-wpb-gitem-zone',
		'category' => esc_html__( 'Content', 'js_composer' ),
		'controls' => [ 'edit' ],
		'as_parent' => [ 'only' => 'vc_gitem_row' ],
		'js_view' => 'VcGitemZoneView',
		'params' => array_merge( [
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Height mode', 'js_composer' ),
				'param_name' => 'height_mode',
				'value' => [
					'1:1' => '1-1',
					esc_html__( 'Original', 'js_composer' ) => 'original',
					'4:3' => '4-3',
					'3:4' => '3-4',
					'16:9' => '16-9',
					'9:16' => '9-16',
					esc_html__( 'Custom', 'js_composer' ) => 'custom',
				],
				'description' => esc_html__( 'Sizing proportions for height and width. Select "Original" to scale image without cropping.', 'js_composer' ),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Height', 'js_composer' ),
				'param_name' => 'height',
				'dependency' => [
					'element' => 'height_mode',
					'value' => [ 'custom' ],
				],
				'description' => esc_html__( 'Enter custom height.', 'js_composer' ),
			],
		], $zone_params ),
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_zone_b' => [
		'name' => esc_html__( 'Hover', 'js_composer' ),
		'base' => 'vc_gitem_zone_b',
		'content_element' => false,
		'is_container' => true,
		'show_settings_on_create' => false,
		'icon' => 'icon-wpb-gitem-zone',
		'category' => esc_html__( 'Content', 'js_composer' ),
		'controls' => [ 'edit' ],
		'as_parent' => [ 'only' => 'vc_gitem_row' ],
		'js_view' => 'VcGitemZoneView',
		'params' => $zone_params,
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_zone_c' => [
		'name' => esc_html__( 'Additional', 'js_composer' ),
		'base' => 'vc_gitem_zone_c',
		'content_element' => false,
		'is_container' => true,
		'show_settings_on_create' => false,
		'icon' => 'icon-wpb-gitem-zone',
		'category' => esc_html__( 'Content', 'js_composer' ),
		'controls' => [
			'move',
			'delete',
			'edit',
		],
		'as_parent' => [ 'only' => 'vc_gitem_row' ],
		'js_view' => 'VcGitemZoneCView',
		'params' => [
			[
				'type' => 'css_editor',
				'heading' => esc_html__( 'CSS box', 'js_composer' ),
				'param_name' => 'css',
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			],
		],
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_row' => [
		'name' => esc_html__( 'Row', 'js_composer' ),
		'base' => 'vc_gitem_row',
		'content_element' => false,
		'is_container' => true,
		'icon' => 'icon-wpb-row',
		'weight' => 1000,
		'show_settings_on_create' => false,
		'controls' => [
			'layout',
			'delete',
		],
		'allowed_container_element' => 'vc_gitem_col',
		'category' => esc_html__( 'Content', 'js_composer' ),
		'description' => esc_html__( 'Place content elements inside the row', 'js_composer' ),
		'params' => [
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			],
		],
		'js_view' => 'VcGitemRowView',
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_col' => [
		'name' => esc_html__( 'Column', 'js_composer' ),
		'base' => 'vc_gitem_col',
		'icon' => 'icon-wpb-row',
		'weight' => 1000,
		'is_container' => true,
		'allowed_container_element' => false,
		'content_element' => false,
		'controls' => [ 'edit' ],
		'description' => esc_html__( 'Place content elements inside the column', 'js_composer' ),
		'params' => [
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
				],
				'description' => esc_html__( 'Select column width.', 'js_composer' ),
				'std' => '1/1',
			],
			[
				'type' => 'checkbox',
				'heading' => esc_html__( 'Use featured image on background?', 'js_composer' ),
				'param_name' => 'featured_image',
				'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
				'description' => esc_html__( 'Note: Featured image overwrites background image and color from "Design Options".', 'js_composer' ),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Image size', 'js_composer' ),
				'param_name' => 'img_size',
				'value' => 'large',
				'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'js_composer' ),
				'dependency' => [
					'element' => 'featured_image',
					'not_empty' => true,
				],
			],
			[
				'type' => 'css_editor',
				'heading' => esc_html__( 'CSS box', 'js_composer' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design Options', 'js_composer' ),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			],
		],
		'js_view' => 'VcGitemColView',
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_post_title' => [
		'name' => esc_html__( 'Post Title', 'js_composer' ),
		'base' => 'vc_gitem_post_title',
		'icon' => 'vc_icon-vc-gitem-post-title',
		'category' => esc_html__( 'Post', 'js_composer' ),
		'description' => esc_html__( 'Title of current post', 'js_composer' ),
		'params' => array_merge( $post_data_params, $custom_fonts_params, [
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			],
		] ),
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_post_excerpt' => [
		'name' => esc_html__( 'Post Excerpt', 'js_composer' ),
		'base' => 'vc_gitem_post_excerpt',
		'icon' => 'vc_icon-vc-gitem-post-excerpt',
		'category' => esc_html__( 'Post', 'js_composer' ),
		'description' => esc_html__( 'Excerpt or manual excerpt', 'js_composer' ),
		'params' => array_merge( $post_data_params, $custom_fonts_params, [
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			],
		] ),
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_post_author' => [
		'name' => esc_html__( 'Post Author', 'js_composer' ),
		'base' => 'vc_gitem_post_author',
		'icon' => 'vc_icon-vc-gitem-post-author',
		// @todo change icon ?
		'category' => esc_html__( 'Post', 'js_composer' ),
		'description' => esc_html__( 'Author of current post', 'js_composer' ),
		'params' => array_merge( [
			[
				'type' => 'checkbox',
				'heading' => esc_html__( 'Add link', 'js_composer' ),
				'param_name' => 'link',
				'value' => '',
				'description' => esc_html__( 'Add link to author?', 'js_composer' ),
			],
			[
				'type' => 'css_editor',
				'heading' => esc_html__( 'CSS box', 'js_composer' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design Options', 'js_composer' ),
			],
		], $custom_fonts_params, [
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			],
		] ),
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_post_categories' => [
		'name' => esc_html__( 'Post Categories', 'js_composer' ),
		'base' => 'vc_gitem_post_categories',
		'icon' => 'vc_icon-vc-gitem-post-categories',
		// @todo change icon ?
		'category' => esc_html__( 'Post', 'js_composer' ),
		'description' => esc_html__( 'Categories of current post', 'js_composer' ),
		'params' => [
			[
				'type' => 'checkbox',
				'heading' => esc_html__( 'Add link', 'js_composer' ),
				'param_name' => 'link',
				'value' => '',
				'description' => esc_html__( 'Add link to category?', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Alignment', 'js_composer' ),
				'param_name' => 'alignment',
				'value' => [
					esc_html__( 'Left', 'js_composer' ) => 'left',
					esc_html__( 'Right', 'js_composer' ) => 'right',
					esc_html__( 'Center', 'js_composer' ) => 'center',
				],
				'std' => 'center',
				'description' => esc_html__( 'Select image alignment.', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Style', 'js_composer' ),
				'param_name' => 'category_style',
				'value' => [
					esc_html__( 'None', 'js_composer' ) => ' ',
					esc_html__( 'Comma', 'js_composer' ) => ', ',
					esc_html__( 'Rounded', 'js_composer' ) => 'filled vc_grid-filter-filled-round-all',
					esc_html__( 'Less Rounded', 'js_composer' ) => 'filled vc_grid-filter-filled-rounded-all',
					esc_html__( 'Border', 'js_composer' ) => 'bordered',
					esc_html__( 'Rounded Border', 'js_composer' ) => 'bordered-rounded vc_grid-filter-filled-round-all',
					esc_html__( 'Less Rounded Border', 'js_composer' ) => 'bordered-rounded-less vc_grid-filter-filled-rounded-all',
				],
				'description' => esc_html__( 'Select category display style.', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Color', 'js_composer' ),
				'param_name' => 'category_color',
				'value' => vc_get_shared( 'colors' ),
				'std' => 'grey',
				'param_holder_class' => 'vc_colored-dropdown',
				'dependency' => [
					'element' => 'category_style',
					'value_not_equal_to' => [
						' ',
						', ',
					],
				],
				'description' => esc_html__( 'Select category color.', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Category size', 'js_composer' ),
				'param_name' => 'category_size',
				'value' => vc_get_shared( 'sizes' ),
				'std' => 'md',
				'description' => esc_html__( 'Select category size.', 'js_composer' ),
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
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_image' => [
		'name' => esc_html__( 'Post Image', 'js_composer' ),
		'base' => 'vc_gitem_image',
		'icon' => 'vc_icon-vc-gitem-image',
		'category' => esc_html__( 'Post', 'js_composer' ),
		'description' => esc_html__( 'Featured image', 'js_composer' ),
		'params' => [
			$vc_gitem_add_link_param,
			$vc_gitem_add_link_target_param,
			[
				'type' => 'vc_link',
				'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
				'param_name' => 'url',
				'dependency' => [
					'element' => 'link',
					'value' => [ 'custom' ],
				],
				'description' => esc_html__( 'Add custom link.', 'js_composer' ),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Image size', 'js_composer' ),
				'param_name' => 'img_size',
				'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)). Leave parameter empty to use "thumbnail" by default.', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Image alignment', 'js_composer' ),
				'param_name' => 'alignment',
				'value' => [
					esc_html__( 'Left', 'js_composer' ) => '',
					esc_html__( 'Right', 'js_composer' ) => 'right',
					esc_html__( 'Center', 'js_composer' ) => 'center',
				],
				'description' => esc_html__( 'Select image alignment.', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Image style', 'js_composer' ),
				'param_name' => 'style',
				'value' => vc_get_shared( 'single image styles' ),
				'description' => esc_html__( 'Select image display style.', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Border color', 'js_composer' ),
				'param_name' => 'border_color',
				'value' => vc_get_shared( 'colors' ),
				'std' => 'grey',
				'dependency' => [
					'element' => 'style',
					'value' => [
						'vc_box_border',
						'vc_box_border_circle',
						'vc_box_outline',
						'vc_box_outline_circle',
					],
				],
				'description' => esc_html__( 'Border color.', 'js_composer' ),
				'param_holder_class' => 'vc_colored-dropdown',
			],
			vc_map_add_css_animation(),
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
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_post_date' => [
		'name' => esc_html__( 'Post Date', 'js_composer' ),
		'base' => 'vc_gitem_post_date',
		'icon' => 'vc_icon-vc-gitem-post-date',
		'category' => esc_html__( 'Post', 'js_composer' ),
		'description' => esc_html__( 'Post publish date', 'js_composer' ),
		'params' => array_merge( $post_data_params, $custom_fonts_params, [
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			],
		] ),
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
	'vc_gitem_post_meta' => [
		'name' => esc_html__( 'Custom Field', 'js_composer' ),
		'base' => 'vc_gitem_post_meta',
		'icon' => 'vc_icon-vc-gitem-post-meta',
		'category' => [
			esc_html__( 'Elements', 'js_composer' ),
		],
		'description' => esc_html__( 'Custom fields data from meta values of the post.', 'js_composer' ),
		'params' => [
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Field key name', 'js_composer' ),
				'param_name' => 'key',
				'description' => esc_html__( 'Enter custom field name to retrieve meta data value.', 'js_composer' ),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Label', 'js_composer' ),
				'param_name' => 'label',
				'description' => esc_html__( 'Enter label to display before key value.', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Alignment', 'js_composer' ),
				'param_name' => 'align',
				'value' => [
					esc_html__( 'Left', 'js_composer' ) => 'left',
					esc_html__( 'Right', 'js_composer' ) => 'right',
					esc_html__( 'Center', 'js_composer' ) => 'center',
					esc_html__( 'Justify', 'js_composer' ) => 'justify',
				],
				'description' => esc_html__( 'Select alignment.', 'js_composer' ),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			],
		],
		'post_type' => Vc_Grid_Item_Editor::postType(),
	],
];
$shortcode_vc_column_text = WPBMap::getShortCode( 'vc_column_text' );
if ( is_array( $shortcode_vc_column_text ) && isset( $shortcode_vc_column_text['base'] ) ) {
	$list['vc_column_text'] = $shortcode_vc_column_text;
	$list['vc_column_text']['post_type'] = Vc_Grid_Item_Editor::postType();
	$remove = [ 'el_id' ];
	foreach ( $list['vc_column_text']['params'] as $k => $v ) {
		if ( in_array( $v['param_name'], $remove, true ) ) {
			unset( $list['vc_column_text']['params'][ $k ] );
		}
	}
}
$shortcode_vc_separator = WPBMap::getShortCode( 'vc_separator' );
if ( is_array( $shortcode_vc_separator ) && isset( $shortcode_vc_separator['base'] ) ) {
	$list['vc_separator'] = $shortcode_vc_separator;
	$list['vc_separator']['post_type'] = Vc_Grid_Item_Editor::postType();
	$remove = [ 'el_id' ];
	foreach ( $list['vc_separator']['params'] as $k => $v ) {
		if ( in_array( $v['param_name'], $remove, true ) ) {
			unset( $list['vc_separator']['params'][ $k ] );
		}
	}
}
$shortcode_vc_text_separator = WPBMap::getShortCode( 'vc_text_separator' );
if ( is_array( $shortcode_vc_text_separator ) && isset( $shortcode_vc_text_separator['base'] ) ) {
	$list['vc_text_separator'] = $shortcode_vc_text_separator;
	$list['vc_text_separator']['post_type'] = Vc_Grid_Item_Editor::postType();

	$remove = [ 'el_id' ];
	foreach ( $list['vc_text_separator']['params'] as $k => $v ) {
		if ( in_array( $v['param_name'], $remove, true ) ) {
			unset( $list['vc_text_separator']['params'][ $k ] );
		}
	}
}
$shortcode_vc_icon = WPBMap::getShortCode( 'vc_icon' );
if ( is_array( $shortcode_vc_icon ) && isset( $shortcode_vc_icon['base'] ) ) {
	$list['vc_icon'] = $shortcode_vc_icon;
	$list['vc_icon']['post_type'] = Vc_Grid_Item_Editor::postType();
	$list['vc_icon']['params'] = vc_map_integrate_shortcode( 'vc_icon', '', '', [
		'exclude' => [
			'link',
			'el_id',
		],
	] );
}
$list['vc_single_image'] = [
	'name' => esc_html__( 'Single Image', 'js_composer' ),
	'base' => 'vc_single_image',
	'icon' => 'icon-wpb-single-image',
	'category' => esc_html__( 'Content', 'js_composer' ),
	'description' => esc_html__( 'Simple image with CSS animation', 'js_composer' ),
	'params' => [
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => esc_html__( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Image source', 'js_composer' ),
			'param_name' => 'source',
			'value' => [
				esc_html__( 'Media library', 'js_composer' ) => 'media_library',
				esc_html__( 'External link', 'js_composer' ) => 'external_link',
			],
			'std' => 'media_library',
			'description' => esc_html__( 'Select image source.', 'js_composer' ),
		],
		[
			'type' => 'attach_image',
			'heading' => esc_html__( 'Image', 'js_composer' ),
			'param_name' => 'image',
			'value' => '',
			'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
			'dependency' => [
				'element' => 'source',
				'value' => 'media_library',
			],
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'External link', 'js_composer' ),
			'param_name' => 'custom_src',
			'description' => esc_html__( 'Select external link.', 'js_composer' ),
			'dependency' => [
				'element' => 'source',
				'value' => 'external_link',
			],
		],
		vc_map_add_css_animation(),
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Image size', 'js_composer' ),
			'param_name' => 'img_size',
			'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)). Leave parameter empty to use "thumbnail" by default.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Image alignment', 'js_composer' ),
			'param_name' => 'alignment',
			'value' => [
				esc_html__( 'Left', 'js_composer' ) => '',
				esc_html__( 'Right', 'js_composer' ) => 'right',
				esc_html__( 'Center', 'js_composer' ) => 'center',
			],
			'description' => esc_html__( 'Select image alignment.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Image style', 'js_composer' ),
			'param_name' => 'style',
			'value' => vc_get_shared( 'single image styles' ),
			'description' => esc_html__( 'Select image display style.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Border color', 'js_composer' ),
			'param_name' => 'border_color',
			'value' => vc_get_shared( 'colors' ),
			'std' => 'grey',
			'dependency' => [
				'element' => 'style',
				'value' => [
					'vc_box_border',
					'vc_box_border_circle',
					'vc_box_outline',
					'vc_box_outline_circle',
				],
			],
			'description' => esc_html__( 'Border color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown',
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
	'post_type' => Vc_Grid_Item_Editor::postType(),
];
$shortcode_vc_button2 = WPBMap::getShortCode( 'vc_button2' );
if ( is_array( $shortcode_vc_button2 ) && isset( $shortcode_vc_button2['base'] ) ) {
	$list['vc_button2'] = $shortcode_vc_button2;
	$list['vc_button2']['post_type'] = Vc_Grid_Item_Editor::postType();
}

$shortcode_vc_btn = WPBMap::getShortCode( 'vc_btn' );
if ( is_array( $shortcode_vc_btn ) && isset( $shortcode_vc_btn['base'] ) ) {
	$list['vc_btn'] = $shortcode_vc_btn;
	$list['vc_btn']['post_type'] = Vc_Grid_Item_Editor::postType();
	unset( $list['vc_btn']['params'][1] );
	$remove = [ 'el_id' ];
	foreach ( $list['vc_btn']['params'] as $k => $v ) {
		if ( in_array( $v['param_name'], $remove, true ) ) {
			unset( $list['vc_btn']['params'][ $k ] );
		}
	}
}
$shortcode_vc_custom_heading = WPBMap::getShortCode( 'vc_custom_heading' );
if ( is_array( $shortcode_vc_custom_heading ) && isset( $shortcode_vc_custom_heading['base'] ) ) {
	$list['vc_custom_heading'] = $shortcode_vc_custom_heading;
	$list['vc_custom_heading']['post_type'] = Vc_Grid_Item_Editor::postType();

	$remove = [
		'link',
		'source',
		'el_id',
	];
	foreach ( $list['vc_custom_heading']['params'] as $k => $v ) {
		if ( in_array( $v['param_name'], $remove, true ) ) {
			unset( $list['vc_custom_heading']['params'][ $k ] );
		}

		// text depends on source. remove dependency so text is always saved.
		if ( 'text' === $v['param_name'] ) {
			unset( $list['vc_custom_heading']['params'][ $k ]['dependency'] );
		}
	}
}
$shortcode_vc_empty_space = WPBMap::getShortCode( 'vc_empty_space' );
if ( is_array( $shortcode_vc_empty_space ) && isset( $shortcode_vc_empty_space['base'] ) ) {
	$list['vc_empty_space'] = $shortcode_vc_empty_space;
	$list['vc_empty_space']['post_type'] = Vc_Grid_Item_Editor::postType();
	$remove = [ 'el_id' ];
	foreach ( $list['vc_empty_space']['params'] as $k => $v ) {
		if ( in_array( $v['param_name'], $remove, true ) ) {
			unset( $list['vc_empty_space']['params'][ $k ] );
		}
	}
}
foreach (
	[
		'vc_icon',
		'vc_button2',
		'vc_btn',
		'vc_custom_heading',
		'vc_single_image',
	] as $key
) {
	if ( isset( $list[ $key ] ) ) {
		if ( ! isset( $list[ $key ]['params'] ) ) {
			$list[ $key ]['params'] = [];
		}
		if ( 'vc_button2' === $key ) {
			// change settings for vc_link in dropdown. Add dependency.
			$list[ $key ]['params'][0] = [
				'type' => 'vc_link',
				'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
				'param_name' => 'url',
				'dependency' => [
					'element' => 'link',
					'value' => [ 'custom' ],
				],
				'description' => esc_html__( 'Add custom link.', 'js_composer' ),
			];
		} else {
			array_unshift( $list[ $key ]['params'], [
				'type' => 'vc_link',
				'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
				'param_name' => 'url',
				'dependency' => [
					'element' => 'link',
					'value' => [ 'custom' ],
				],
				'description' => esc_html__( 'Add custom link.', 'js_composer' ),
			] );
		}
		// Add link dropdown.
		array_unshift( $list[ $key ]['params'], $vc_gitem_add_link_target_param );
		array_unshift( $list[ $key ]['params'], $vc_gitem_add_link_param );
	}
}
foreach ( $list as $key => $value ) {
	if ( isset( $list[ $key ]['params'] ) ) {
		$list[ $key ]['params'] = array_values( $list[ $key ]['params'] );
	}
}

return $list;
