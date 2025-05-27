<?php
/**
 * Backend editor part template.
 *
 * @var string $part
 * @var string $role
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
vc_include_template( 'pages/partials/vc-roles-parts/_part.tpl.php', [
	'part' => $part,
	'role' => $role,
	'params_prefix' => 'vc_roles[' . $role . '][' . $part . ']',
	'controller' => vc_role_access()->who( $role )->part( $part ),
	'capabilities' => [
		[
			'disabled_ce_editor',
			esc_html__( 'Disable Classic editor', 'js_composer' ),
		],
	],
	'options' => [
		[
			true,
			esc_html__( 'Enabled', 'js_composer' ),
		],
		[
			'default',
			esc_html__( 'Enabled and default', 'js_composer' ),
		],
		[
			false,
			esc_html__( 'Disabled', 'js_composer' ),
		],
	],
	'main_label' => esc_html__( 'Backend editor', 'js_composer' ),
	'custom_label' => esc_html__( 'Backend editor', 'js_composer' ),
	'custom_value' => true,
] );
