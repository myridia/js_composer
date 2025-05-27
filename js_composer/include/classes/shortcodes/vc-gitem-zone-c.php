<?php
/**
 * Class that handles specific [vc_gitem_zone_c] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_gitem_zone_c.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-gitem-zone.php' );

/**
 * Class WPBakeryShortCode_Vc_Gitem_Zone_C
 */
class WPBakeryShortCode_Vc_Gitem_Zone_C extends WPBakeryShortCode_Vc_Gitem_Zone {
	/**
	 * Zone name.
	 *
	 * @var string
	 */
	public $zone_name = 'c';
}
