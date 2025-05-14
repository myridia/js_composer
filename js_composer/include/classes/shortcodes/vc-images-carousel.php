<?php
/**
 * Class that handles specific [vc_images_carousel] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_images_carousel.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-gallery.php' );

/**
 * Class WPBakeryShortCode_Vc_images_carousel
 */
class WPBakeryShortCode_Vc_Images_Carousel extends WPBakeryShortCode_Vc_Gallery {

	/**
	 * Carousel index.
	 *
	 * @var int
	 */
	protected static $carousel_index = 1;

	/**
	 * WPBakeryShortCode_Vc_images_carousel constructor.
	 *
	 * @param array $settings
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->jsCssScripts();
	}

	/**
	 * Register element specific assets.
	 */
	public function jsCssScripts() {
		wp_register_script( 'vc_transition_bootstrap_js', vc_asset_url( 'lib/vc/vc_carousel/js/transition.min.js' ), [], WPB_VC_VERSION, true );
		wp_register_script( 'vc_carousel_js', vc_asset_url( 'lib/vc/vc_carousel/js/vc_carousel.min.js' ), [ 'vc_transition_bootstrap_js' ], WPB_VC_VERSION, true );
		wp_register_style( 'vc_carousel_css', vc_asset_url( 'lib/vc/vc_carousel/css/vc_carousel.min.css' ), [], WPB_VC_VERSION );
	}

	/**
	 * Get carousel index.
	 *
	 * @return string
	 */
	public static function getCarouselIndex() {
		return ( self::$carousel_index++ ) . '-' . time();
	}

	/**
	 * Get element slider width.
	 *
	 * @param string $size
	 * @return string
	 */
	protected function getSliderWidth( $size ) {
		global $_wp_additional_image_sizes;
		$width = '100%';
		if ( in_array( $size, get_intermediate_image_sizes(), true ) ) {
			if ( in_array( $size, [
				'thumbnail',
				'medium',
				'large',
			], true ) ) {
				$width = get_option( $size . '_size_w' ) . 'px';
			} elseif ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$width = $_wp_additional_image_sizes[ $size ]['width'] . 'px';
			}
		} else {
			preg_match_all( '/\d+/', $size, $matches );
			if ( count( $matches[0] ) > 1 ) {
				$width = $matches[0][0] . 'px';
			}
		}

		return $width;
	}
}
