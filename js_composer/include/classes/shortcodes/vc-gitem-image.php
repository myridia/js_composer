<?php
/**
 * Class that handles specific [vc_gitem_image] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_gitem_image.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-gitem-post-data.php' );

/**
 * Class WPBakeryShortCode_Vc_Gitem_Image
 */
class WPBakeryShortCode_Vc_Gitem_Image extends WPBakeryShortCode_Vc_Gitem_Post_Data {
	/**
	 * Get data_source attribute value
	 *
	 * @param array $atts - list of shortcode attributes.
	 *
	 * @return string
	 */
	public function getDataSource( array $atts ) {
		return 'post_image';
	}
}
