<?php
/**
 * Class that handles specific [vc_gitem_post_categories] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_gitem_post_categories.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-gitem-post-data.php' );

/**
 * Class WPBakeryShortCode_Vc_Gitem_Post_Categories
 */
class WPBakeryShortCode_Vc_Gitem_Post_Categories extends WPBakeryShortCode_Vc_Gitem_Post_Data {
	/**
	 * Get name.
	 *
	 * @return mixed|string
	 */
	protected function getFileName() {
		return 'vc_gitem_post_categories';
	}
}
