<?php
/**
 * Class that handles specific [vc_gutenberg] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_gutenberg.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Gutenberg
 */
class WPBakeryShortCode_Vc_Gutenberg extends WPBakeryShortCode {
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
