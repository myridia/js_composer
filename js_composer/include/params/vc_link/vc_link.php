<?php
/**
 * Param type 'vc_link'.
 *
 * Use it to create Link selection field.
 *
 * @note in shortcodes html output, use $href = vc_build_link( $href );
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/#vc_map()-ParametersofparamsArray
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Get link form field html.
 *
 * @param array $settings
 * @param string $value
 *
 * @return string
 * @since 4.2
 */
function vc_vc_link_form_field( $settings, $value ) {
	$link = vc_build_link( $value );

	return sprintf( '<div class="vc_link"><input name="%s" class="wpb_vc_param_value  %s_field" type="hidden" value="%s" data-json="%s" /><a href="#" class="button vc_link-build %s_button">%s</a> <span class="vc_link_label_title vc_link_label">%s:</span> <span class="title-label">%s</span> <span class="vc_link_label">%s:</span> <span class="url-label">%s %s</span></div>',
		esc_attr( $settings['param_name'] ),
		esc_attr( $settings['param_name'] . ' ' . $settings['type'] ),
		is_string( $value ) ? htmlentities( $value, ENT_QUOTES, 'utf-8' ) : '',
		htmlentities( wp_json_encode( $link ), ENT_QUOTES, 'utf-8' ),
		esc_attr( $settings['param_name'] ),
		esc_html__( 'Select URL', 'js_composer' ),
		esc_html__( 'Title', 'js_composer' ),
		$link['title'], esc_html__( 'URL', 'js_composer' ),
		$link['url'],
		$link['target']
	);
}

/**
 * Get link form field attributes.
 *
 * @param array|string $value
 *
 * @return array
 * @since 4.2
 */
function vc_build_link( $value ) {
	return vc_parse_multi_attribute( $value, [
		'url' => '',
		'title' => '',
		'target' => '',
		'rel' => '',
	] );
}
