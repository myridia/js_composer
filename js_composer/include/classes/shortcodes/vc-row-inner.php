<?php
/**
 * Class that handles specific [vc_row_inner] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_row_inner.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-row.php' );

/**
 * Class WPBakeryShortCode_Vc_Row_Inner
 */
class WPBakeryShortCode_Vc_Row_Inner extends WPBakeryShortCode_Vc_Row {

	/**
	 * Get template.
	 *
	 * @param string $content
	 * @return string
	 * @throws \Exception
	 */
	public function template( $content = '' ) {
		return $this->contentAdmin( $this->atts );
	}
}
