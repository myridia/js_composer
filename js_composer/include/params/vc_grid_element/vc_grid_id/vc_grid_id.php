<?php
/**
 * Param type 'vc_grid_id'.
 *
 * Specific param type for vc_grid_id that we use for our grid builder.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/#vc_map()-ParametersofparamsArray
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Render form field for vc_grid_id.
 *
 * @param array $settings
 * @param string $value
 *
 * @return string
 * @since 4.4.3
 */
function vc_vc_grid_id_form_field( $settings, $value ) {
	return sprintf( '<div class="vc_param-vc-grid-id"><input name="%s" class="wpb_vc_param_value wpb-textinput %s_field" type="hidden" value="%s" /></div>', esc_attr( $settings['param_name'] ), esc_attr( $settings['param_name'] . ' ' . $settings['type'] ), $value );
}
