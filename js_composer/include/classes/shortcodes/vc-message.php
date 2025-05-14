<?php
/**
 * Class that handles specific [vc_message] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_message.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Message
 */
class WPBakeryShortCode_Vc_Message extends WPBakeryShortCode {

	/**
	 * Convert attributes to message box.
	 *
	 * @param array $atts
	 * @return mixed
	 */
	public static function convertAttributesToMessageBox2( $atts ) {
		if ( isset( $atts['style'] ) ) {
			if ( '3d' === $atts['style'] ) {
				$atts['message_box_style'] = '3d';
				$atts['style'] = 'rounded';
			} elseif ( 'outlined' === $atts['style'] ) {
				$atts['message_box_style'] = 'outline';
				$atts['style'] = 'rounded';
			} elseif ( 'square_outlined' === $atts['style'] ) {
				$atts['message_box_style'] = 'outline';
				$atts['style'] = 'square';
			}
		}

		return $atts;
	}

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
