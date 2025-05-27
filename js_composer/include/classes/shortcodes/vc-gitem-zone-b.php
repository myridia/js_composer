<?php
/**
 * Class that handles specific [vc_gitem_zone_b] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_gitem_zone.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-gitem-zone.php' );

/**
 * Class WPBakeryShortCode_Vc_Gitem_Zone_B
 */
class WPBakeryShortCode_Vc_Gitem_Zone_B extends WPBakeryShortCode_Vc_Gitem_Zone {
	/**
	 * Zone name.
	 *
	 * @var string
	 */
	public $zone_name = 'b';

	/**
	 * Get name.
	 *
	 * @return mixed|string
	 */
	protected function getFileName() {
		return 'vc_gitem_zone';
	}
}
