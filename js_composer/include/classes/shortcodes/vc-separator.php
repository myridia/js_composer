<?php
/**
 * Class that handles specific [vc_separator] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_separator.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Separator
 */
class WPBakeryShortCode_Vc_Separator extends WPBakeryShortCode {

	/**
	 * Override default title.
	 *
	 * @param string $title
	 * @return string
	 */
	public function outputTitle( $title ) {
		return '';
	}
}
