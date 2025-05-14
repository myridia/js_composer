<?php
/**
 * Class that handles specific [vc_line_chart] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_line_chart.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Line_Chart
 */
class WPBakeryShortCode_Vc_Line_Chart extends WPBakeryShortCode {
	/**
	 * WPBakeryShortCode_Vc_Line_Chart constructor.
	 *
	 * @param array $settings
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->jsScripts();
	}

	/**
	 * Register scripts.
	 */
	public function jsScripts() {
		wp_register_script( 'vc_waypoints', vc_asset_url( 'lib/vc/vc_waypoints/vc-waypoints.min.js' ), [ 'jquery-core' ], WPB_VC_VERSION, true );
		wp_register_script( 'ChartJS', vc_asset_url( 'lib/vendor/node_modules/chart.js/dist/chart.min.js' ), [], WPB_VC_VERSION, true );
		wp_register_script( 'vc_line_chart', vc_asset_url( 'lib/vc/vc_line_chart/vc_line_chart.min.js' ), [
			'jquery-core',
			'vc_waypoints',
			'ChartJS',
		], WPB_VC_VERSION, true );
	}
}
