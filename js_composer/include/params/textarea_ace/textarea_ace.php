<?php
/**
 * Param type 'textarea_ace'.
 *
 * Used to create text area with Ace Editor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Get param form field html output.
 *
 * @param array $settings
 * @param string $value
 *
 * @return string
 * @since 8.1
 */
function vc_textarea_ace_form_field( $settings, $value ) {
	$unique_id = 'textarea_ace_' . uniqid();
	$decoded_value = rawurldecode( base64_decode( $value ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode

	$output = '<div id="' . esc_attr( $unique_id ) . '" class="textarea_ace_container custom_code" style="width:100%;height:300px;">' . esc_textarea( $decoded_value ) . '</div>';
	$output .= '<input type="hidden" name="content" class="wpb_vc_param_value content  ' . esc_attr( $settings['type'] ) . '" value="' . esc_attr( $value ) . '" />';

	return $output;
}
