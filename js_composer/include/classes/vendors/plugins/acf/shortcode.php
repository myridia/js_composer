<?php
/**
 * Backward compatibility with "Advanced custom fields" WordPress plugin.
 *
 * @see https://wordpress.org/plugins/advanced-custom-fields/
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'VENDORS_DIR', 'plugins/acf/class-wpb-acf-provider.php' );
$provider = new Wpb_Acf_Provider();

return [
	'name' => esc_html__( 'Advanced Custom Field', 'js_composer' ),
	'base' => 'vc_acf',
	'icon' => 'vc_icon-acf',
	'category' => esc_html__( 'Content', 'js_composer' ),
	'description' => esc_html__( 'Advanced Custom Field', 'js_composer' ),
	'params' => $provider->get_shortcode_params(),
];
