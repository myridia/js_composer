<?php
/**
 * Param type 'href'
 *
 * Use it to create url link button that lets the user insert additional url attributes.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/#vc_map()-ParametersofparamsArray
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Get href form field html.
 *
 * @param array $settings
 * @param string $value
 *
 * @return string
 * @since 4.4
 */
function vc_href_form_field( $settings, $value ) {
	if ( ! is_string( $value ) || strlen( $value ) === 0 ) {
		$value = 'http://';
	}

	return sprintf( '<div class="vc_href-form-field"><input name="%s" class="wpb_vc_param_value wpb-textinput %s %s_field" type="text" value="%s"/></div>', esc_attr( $settings['param_name'] ), esc_attr( $settings['param_name'] ), esc_attr( $settings['type'] ), $value );
}
