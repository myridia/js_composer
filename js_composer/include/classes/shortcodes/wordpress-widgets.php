<?php
/**
 * Class that handles specific [vc_wp_text] shortcode
 *
 * @see js_composer/include/templates/shortcodes/vc_wp_text.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Wp_Text
 */
class WPBakeryShortCode_Vc_Wp_Text extends WPBakeryShortCode {
	/**
	 * This actually fixes #1537 by converting 'text' to 'content'
	 *
	 * @param array $atts
	 *
	 * @return mixed
	 * @since 4.4
	 */
	public static function convertTextAttributeToContent( $atts ) {
		if ( isset( $atts['text'] ) ) {
			if ( ! isset( $atts['content'] ) || empty( $atts['content'] ) ) {
				$atts['content'] = $atts['text'];
			}
		}

		return $atts;
	}
}
