<?php
/**
 * Class that handles specific [vc_button] shortcode
 *
 * @see js_composer/include/templates/shortcodes/vc_button.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * WPBakery Page Builder shortcodes
 *
 * @package WPBakeryPageBuilder
 */
class WPBakeryShortCode_Vc_Button extends WPBakeryShortCode {
	/**
	 * Get title.
	 *
	 * @param string $title
	 * @return string
	 */
	protected function outputTitle( $title ) {
		$icon = $this->settings( 'icon' );

		return '<h4 class="wpb_element_title"><span class="vc_general vc_element-icon' . ( ! empty( $icon ) ? ' ' . esc_attr( $icon ) : '' ) . '"></span></h4>';
	}
}
