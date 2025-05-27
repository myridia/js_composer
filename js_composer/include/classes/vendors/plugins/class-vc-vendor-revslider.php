<?php
/**
 * Backward compatibility with "Revolution Slider" WordPress plugin.
 *
 * @see https://www.sliderrevolution.com/
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * RevSlider loader.
 *
 * @since 4.3
 */
class Vc_Vendor_Revslider {
	/**
	 * Instance index.
	 *
	 * @since 4.3
	 * @var int - index of revslider
	 */
	protected static $instance_index = 1;

	/**
	 * Add shortcode to WPBakery Page Builder also add fix for frontend to regenerate id of revslider.
	 *
	 * @since 4.3
	 */
	public function load() {
		add_action( 'vc_after_mapping', [
			$this,
			'buildShortcode',
		] );
	}

	/**
	 * Build shortcode.
	 *
	 * @since 4.3
	 */
	public function buildShortcode() {
		if ( class_exists( 'RevSlider' ) ) {
			vc_lean_map( 'rev_slider_vc', [
				$this,
				'addShortcodeSettings',
			] );
			if ( vc_is_frontend_ajax() || vc_is_frontend_editor() ) {
				add_filter( 'vc_revslider_shortcode', [
					$this,
					'setId',
				] );
			}
		}
	}

	/**
	 * Map shortcode.
	 *
	 * @param array $revsliders
	 *
	 * @since 4.4
	 *
	 * @deprecated 4.9
	 */
	public function mapShortcode( $revsliders = [] ) {
		vc_map( [
			'base' => 'rev_slider_vc',
			'name' => esc_html__( 'Revolution Slider', 'js_composer' ),
			'icon' => 'icon-wpb-revslider',
			'category' => esc_html__( 'Content', 'js_composer' ),
			'description' => esc_html__( 'Place Revolution slider', 'js_composer' ),
			'params' => [
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Widget title', 'js_composer' ),
					'param_name' => 'title',
					'description' => esc_html__( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Revolution Slider', 'js_composer' ),
					'param_name' => 'alias',
					'admin_label' => true,
					'value' => $revsliders,
					'save_always' => true,
					'description' => esc_html__( 'Select your Revolution Slider.', 'js_composer' ),
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'js_composer' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
				],
			],
		] );
	}

	/**
	 * Replaces id of revslider for frontend editor.
	 *
	 * @param string $output
	 *
	 * @return string
	 * @since 4.3
	 */
	public function setId( $output ) {
		return preg_replace( '/rev_slider_(\d+)_(\d+)/', 'rev_slider_$1_$2' . time() . '_' . self::$instance_index++, $output );
	}

	/**
	 * Mapping settings for lean method.
	 *
	 * @param string $tag
	 *
	 * @return array
	 * @since 4.9
	 */
	public function addShortcodeSettings( $tag ) {
		$slider = new RevSlider();
		$sliders = $slider->getArrSliders();

		$revsliders = [];
		if ( $sliders ) {
			foreach ( $sliders as $slider ) {
				// RevSlider $slider.
				$revsliders[ $slider->getTitle() ] = $slider->getAlias();
			}
		} else {
			$revsliders[ esc_html__( 'No sliders found', 'js_composer' ) ] = 0;
		}

		// Add fixes for frontend editor to regenerate id.
		return [
			'base' => $tag,
			'name' => esc_html__( 'Revolution Slider', 'js_composer' ),
			'icon' => 'icon-wpb-revslider',
			'category' => esc_html__( 'Content', 'js_composer' ),
			'description' => esc_html__( 'Place Revolution slider', 'js_composer' ),
			'params' => [
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Widget title', 'js_composer' ),
					'param_name' => 'title',
					'description' => esc_html__( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Revolution Slider', 'js_composer' ),
					'param_name' => 'alias',
					'admin_label' => true,
					'value' => $revsliders,
					'save_always' => true,
					'description' => esc_html__( 'Select your Revolution Slider.', 'js_composer' ),
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'js_composer' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
				],
			],
		];
	}
}
