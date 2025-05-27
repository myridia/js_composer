<?php
/**
 * Frontend editor part template.
 *
 * @var string $part
 * @var string $role
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( vc_frontend_editor()->inlineEnabled() ) {
	vc_include_template( 'pages/partials/vc-roles-parts/_part.tpl.php', [
		'part' => $part,
		'role' => $role,
		'params_prefix' => 'vc_roles[' . $role . '][' . $part . ']',
		'controller' => vc_role_access()->who( $role )->part( $part ),
		'custom_value' => 'custom',
		'options' => [
			[
				true,
				esc_html__( 'Enabled', 'js_composer' ),
			],
			[
				false,
				esc_html__( 'Disabled', 'js_composer' ),
			],
		],
		'main_label' => esc_html__( 'Frontend editor', 'js_composer' ),
		'custom_label' => esc_html__( 'Frontend editor', 'js_composer' ),
	] );
}
