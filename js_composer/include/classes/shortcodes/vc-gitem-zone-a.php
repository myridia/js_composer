<?php
/**
 * Class that handles specific [vc_gitem_zone_a] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_gitem_zone.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-gitem-zone.php' );

/**
 * Class WPBakeryShortCode_Vc_Gitem_Zone_A
 */
class WPBakeryShortCode_Vc_Gitem_Zone_A extends WPBakeryShortCode_Vc_Gitem_Zone {

	/**
	 * Zone name.
	 *
	 * @var string
	 */
	public $zone_name = 'a';

	/**
	 * Get name.
	 *
	 * @return mixed|string
	 */
	protected function getFileName() {
		return 'vc_gitem_zone';
	}
}
