<?php
/**
 * WPBakery Page Builder Content elements refresh.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class VcSharedLibrary
 *
 * Here we will store plugin wise (shared) settings. Colors, Locations, Sizes, etc.
 */
class VcSharedLibrary {
	/**
	 * Available color options.
	 *
	 * @var array
	 */
	private static $colors = [
		'Blue' => 'blue',
		'Turquoise' => 'turquoise',
		'Pink' => 'pink',
		'Violet' => 'violet',
		'Peacoc' => 'peacoc',
		'Chino' => 'chino',
		'Mulled Wine' => 'mulled_wine',
		'Vista Blue' => 'vista_blue',
		'Black' => 'black',
		'Grey' => 'grey',
		'Orange' => 'orange',
		'Sky' => 'sky',
		'Green' => 'green',
		'Juicy pink' => 'juicy_pink',
		'Sandy brown' => 'sandy_brown',
		'Purple' => 'purple',
		'White' => 'white',
	];

	/**
	 * Available icon options.
	 *
	 * @var array
	 */
	public static $icons = [
		'Glass' => 'glass',
		'Music' => 'music',
		'Search' => 'search',
	];

	/**
	 * Available size options.
	 *
	 * @var array
	 */
	public static $sizes = [
		'Mini' => 'xs',
		'Small' => 'sm',
		'Normal' => 'md',
		'Large' => 'lg',
	];

	/**
	 * Available button styles.
	 *
	 * @var array
	 */
	public static $button_styles = [
		'Rounded' => 'rounded',
		'Square' => 'square',
		'Round' => 'round',
		'Outlined' => 'outlined',
		'3D' => '3d',
		'Square Outlined' => 'square_outlined',
	];

	/**
	 * Available message box styles.
	 *
	 * @var array
	 */
	public static $message_box_styles = [
		'Standard' => 'standard',
		'Solid' => 'solid',
		'Solid icon' => 'solid-icon',
		'Outline' => 'outline',
		'3D' => '3d',
	];

	/**
	 * Available toggle styles.
	 *
	 * @var array
	 */
	public static $toggle_styles = [
		'Default' => 'default',
		'Simple' => 'simple',
		'Round' => 'round',
		'Round Outline' => 'round_outline',
		'Rounded' => 'rounded',
		'Rounded Outline' => 'rounded_outline',
		'Square' => 'square',
		'Square Outline' => 'square_outline',
		'Arrow' => 'arrow',
		'Text Only' => 'text_only',
	];

	/**
	 * Available animation styles.
	 *
	 * @var array
	 */
	public static $animation_styles = [
		'Bounce' => 'easeOutBounce',
		'Elastic' => 'easeOutElastic',
		'Back' => 'easeOutBack',
		'Cubic' => 'easeInOutCubic',
		'Quint' => 'easeInOutQuint',
		'Quart' => 'easeOutQuart',
		'Quad' => 'easeInQuad',
		'Sine' => 'easeOutSine',
	];

	/**
	 * Available call to action styles.
	 *
	 * @var array
	 */
	public static $cta_styles = [
		'Rounded' => 'rounded',
		'Square' => 'square',
		'Round' => 'round',
		'Outlined' => 'outlined',
		'Square Outlined' => 'square_outlined',
	];

	/**
	 * Available text align options.
	 *
	 * @var array
	 */
	public static $txt_align = [
		'Left' => 'left',
		'Right' => 'right',
		'Center' => 'center',
		'Justify' => 'justify',
	];

	/**
	 * Available element widths.
	 *
	 * @var array
	 */
	public static $el_widths = [
		'100%' => '',
		'90%' => '90',
		'80%' => '80',
		'70%' => '70',
		'60%' => '60',
		'50%' => '50',
		'40%' => '40',
		'30%' => '30',
		'20%' => '20',
		'10%' => '10',
	];

	/**
	 * Available separator widths.
	 *
	 * @var array
	 */
	public static $sep_widths = [
		'1px' => '',
		'2px' => '2',
		'3px' => '3',
		'4px' => '4',
		'5px' => '5',
		'6px' => '6',
		'7px' => '7',
		'8px' => '8',
		'9px' => '9',
		'10px' => '10',
	];

	/**
	 * Available separator styles.
	 *
	 * @var array
	 */
	public static $sep_styles = [
		'Border' => '',
		'Dashed' => 'dashed',
		'Dotted' => 'dotted',
		'Double' => 'double',
		'Shadow' => 'shadow',
	];

	/**
	 * Available box styles.
	 *
	 * @var array
	 */
	public static $box_styles = [
		'Default' => '',
		'Rounded' => 'vc_box_rounded',
		'Border' => 'vc_box_border',
		'Outline' => 'vc_box_outline',
		'Shadow' => 'vc_box_shadow',
		'Bordered shadow' => 'vc_box_shadow_border',
		'3D Shadow' => 'vc_box_shadow_3d',
	];

	/**
	 * Available round box styles.
	 *
	 * @var array
	 */
	public static $round_box_styles = [
		'Round' => 'vc_box_circle',
		'Round Border' => 'vc_box_border_circle',
		'Round Outline' => 'vc_box_outline_circle',
		'Round Shadow' => 'vc_box_shadow_circle',
		'Round Border Shadow' => 'vc_box_shadow_border_circle',
	];

	/**
	 * Available circle box styles.
	 *
	 * @var array
	 */
	public static $circle_box_styles = [
		'Circle' => 'vc_box_circle_2',
		'Circle Border' => 'vc_box_border_circle_2',
		'Circle Outline' => 'vc_box_outline_circle_2',
		'Circle Shadow' => 'vc_box_shadow_circle_2',
		'Circle Border Shadow' => 'vc_box_shadow_border_circle_2',
	];

	/**
	 * Get available colors.
	 *
	 * @return array
	 */
	public static function getColors() {
		return self::$colors;
	}

	/**
	 * Get available icons.
	 *
	 * @return array
	 */
	public static function getIcons() {
		return self::$icons;
	}

	/**
	 * Get available sizes.
	 *
	 * @return array
	 */
	public static function getSizes() {
		return self::$sizes;
	}

	/**
	 * Get available button styles.
	 *
	 * @return array
	 */
	public static function getButtonStyles() {
		return self::$button_styles;
	}

	/**
	 * Get available message box styles.
	 *
	 * @return array
	 */
	public static function getMessageBoxStyles() {
		return self::$message_box_styles;
	}

	/**
	 * Get available toggle styles.
	 *
	 * @return array
	 */
	public static function getToggleStyles() {
		return self::$toggle_styles;
	}

	/**
	 * Get available animation styles.
	 *
	 * @return array
	 */
	public static function getAnimationStyles() {
		return self::$animation_styles;
	}

	/**
	 * Get available call to action styles.
	 *
	 * @return array
	 */
	public static function getCtaStyles() {
		return self::$cta_styles;
	}

	/**
	 * Get available text align options.
	 *
	 * @return array
	 */
	public static function getTextAlign() {
		return self::$txt_align;
	}

	/**
	 * Get available element widths.
	 *
	 * @return array
	 */
	public static function getBorderWidths() {
		return self::$sep_widths;
	}

	/**
	 * Get available element widths.
	 *
	 * @return array
	 */
	public static function getElementWidths() {
		return self::$el_widths;
	}

	/**
	 * Get available separator styles.
	 *
	 * @return array
	 */
	public static function getSeparatorStyles() {
		return self::$sep_styles;
	}

	/**
	 * Get list of box styles
	 *
	 * Possible $groups values:
	 * - default
	 * - round
	 * - circle
	 *
	 * @param array $groups Array of groups to include. If not specified, return all.
	 *
	 * @return array
	 */
	public static function getBoxStyles( $groups = [] ) {
		$list = [];
		$groups = (array) $groups;

		if ( ! $groups || in_array( 'default', $groups, true ) ) {
			$list += self::$box_styles;
		}

		if ( ! $groups || in_array( 'round', $groups, true ) ) {
			$list += self::$round_box_styles;
		}

		if ( ! $groups || in_array( 'cirlce', $groups, true ) ) {
			$list += self::$circle_box_styles;
		}

		return $list;
	}

	/**
	 * Get available colors.
	 *
	 * @return array
	 */
	public static function getColorsDashed() {
		$colors = [
			esc_html__( 'Blue', 'js_composer' ) => 'blue',
			esc_html__( 'Turquoise', 'js_composer' ) => 'turquoise',
			esc_html__( 'Pink', 'js_composer' ) => 'pink',
			esc_html__( 'Violet', 'js_composer' ) => 'violet',
			esc_html__( 'Peacoc', 'js_composer' ) => 'peacoc',
			esc_html__( 'Chino', 'js_composer' ) => 'chino',
			esc_html__( 'Mulled Wine', 'js_composer' ) => 'mulled-wine',
			esc_html__( 'Vista Blue', 'js_composer' ) => 'vista-blue',
			esc_html__( 'Black', 'js_composer' ) => 'black',
			esc_html__( 'Grey', 'js_composer' ) => 'grey',
			esc_html__( 'Orange', 'js_composer' ) => 'orange',
			esc_html__( 'Sky', 'js_composer' ) => 'sky',
			esc_html__( 'Green', 'js_composer' ) => 'green',
			esc_html__( 'Juicy pink', 'js_composer' ) => 'juicy-pink',
			esc_html__( 'Sandy brown', 'js_composer' ) => 'sandy-brown',
			esc_html__( 'Purple', 'js_composer' ) => 'purple',
			esc_html__( 'White', 'js_composer' ) => 'white',
		];

		return $colors;
	}
}
