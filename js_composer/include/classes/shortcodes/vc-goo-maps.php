<?php
/**
 * Class that handles specific [vc_goo_maps] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_goo_maps.php
 *
 * @since 8.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Goo_Maps
 *
 * @since 8.3
 */
class WPBakeryShortCode_Vc_Goo_Maps extends WPBakeryShortCode {

	/**
	 * Create iframe link base on user input attributes.
	 *
	 * @since 8.3
	 * @param array $atts
	 * @return string
	 */
	public function getIframeLink( $atts ) {

		$zoom = absint( $atts['zoom'] );
		if ( $zoom < 0 ) {
			$zoom = 0;
		} elseif ( $zoom > 22 ) {
			$zoom = 22;
		}

		$params = [
			rawurlencode( $atts['location'] ),
			esc_attr( $atts['type'] ),
			$zoom,
		];

		$url = 'https://maps.google.com/maps?q=%1$s&amp;t=%2$s&amp;z=%3$d&amp;output=embed&amp;iwloc=near';

		return vsprintf( $url, $params );
	}
}
