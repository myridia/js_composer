<?php
/**
 * Class that handles specific [vc_gitem_zone] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_gitem_zone.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Gitem_Zone
 */
class WPBakeryShortCode_Vc_Gitem_Zone extends WPBakeryShortCodesContainer {
	/**
	 * Zone name.
	 *
	 * @var string
	 */
	public $zone_name = '';

	/**
	 * Data attribute with href value for lightbox.
	 *
	 * @var string
	 */
	public $lightbox_rel;
}
