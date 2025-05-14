<?php
/**
 * Class that handles specific [vc_column_text] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_column_text.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Column_Text
 */
class WPBakeryShortCode_Vc_Column_Text extends WPBakeryShortCode {
	/**
	 * Get title.
	 *
	 * @param string $title
	 * @return string
	 */
	protected function outputTitle( $title ) {
		return '';
	}
}
