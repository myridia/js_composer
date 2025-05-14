<?php
/**
 * Backward compatibility with "Woocommerce" WordPress plugin.
 *
 * @see https://wordpress.org/plugins/woocommerce
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'vc_gitem_wocommerce' => [
		'name' => esc_html__( 'WooCommerce field', 'js_composer' ),
		'base' => 'vc_gitem_wocommerce',
		'icon' => 'icon-wpb-woocommerce',
		'category' => esc_html__( 'Content', 'js_composer' ),
		'description' => esc_html__( 'Woocommerce', 'js_composer' ),
		'php_class_name' => 'Vc_Gitem_Woocommerce_Shortcode',
		'params' => [
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Content type', 'js_composer' ),
				'param_name' => 'post_type',
				'value' => [
					esc_html__( 'Product', 'js_composer' ) => 'product',
					esc_html__( 'Order', 'js_composer' ) => 'order',
				],
				'save_always' => true,
				'description' => esc_html__( 'Select Woo Commerce post type.', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Product field name', 'js_composer' ),
				'param_name' => 'product_field_key',
				'value' => Vc_Vendor_Woocommerce::getProductsFieldsList(),
				'dependency' => [
					'element' => 'post_type',
					'value' => [ 'product' ],
				],
				'save_always' => true,
				'description' => esc_html__( 'Choose field from product.', 'js_composer' ),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Product custom key', 'js_composer' ),
				'param_name' => 'product_custom_key',
				'description' => esc_html__( 'Enter custom key.', 'js_composer' ),
				'dependency' => [
					'element' => 'product_field_key',
					'value' => [ '_custom_' ],
				],
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order fields', 'js_composer' ),
				'param_name' => 'order_field_key',
				'value' => Vc_Vendor_Woocommerce::getOrderFieldsList(),
				'dependency' => [
					'element' => 'post_type',
					'value' => [ 'order' ],
				],
				'save_always' => true,
				'description' => esc_html__( 'Choose field from order.', 'js_composer' ),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__( 'Order custom key', 'js_composer' ),
				'param_name' => 'order_custom_key',
				'dependency' => [
					'element' => 'order_field_key',
					'value' => [ '_custom_' ],
				],
				'description' => esc_html__( 'Enter custom key.', 'js_composer' ),
			],
			[
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show label', 'js_composer' ),
				'param_name' => 'show_label',
				'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
				'save_always' => true,
				'description' => esc_html__( 'Enter label to display before key value.', 'js_composer' ),
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Align', 'js_composer' ),
				'param_name' => 'align',
				'value' => [
					esc_attr__( 'left', 'js_composer' ) => 'left',
					esc_attr__( 'right', 'js_composer' ) => 'right',
					esc_attr__( 'center', 'js_composer' ) => 'center',
					esc_attr__( 'justify', 'js_composer' ) => 'justify',
				],
				'save_always' => true,
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
