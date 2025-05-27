<?php
/**
 * Param type 'el_id'.
 *
 * Used to create input text field specifically for element ID.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/#vc_map()-ParametersofparamsArray
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Get output for el_id form field.
 *
 * @param array $settings
 * @param string $value
 *
 * @return string
 * @since 4.5
 */
function vc_el_id_form_field( $settings, $value ) {
	$value_output = sprintf( '<div class="vc-param-el_id"><input name="%s" class="wpb_vc_param_value wpb-textinput %s_field" type="text" value="%s" /></div>', esc_attr( $settings['param_name'] ), esc_attr( $settings['param_name'] . ' ' . $settings['type'] ), $value );

	return apply_filters( 'vc_el_id_render_filter', $value_output );
}
