<?php
/**
 * Text AI part template.
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
	'options' => [
		[ true, esc_html__( 'Enabled', 'js_composer' ) ],
		[ false, esc_html__( 'Disabled', 'js_composer' ) ],
	],
	'main_label' => esc_html__( 'Text AI access', 'js_composer' ),
	'description' => esc_html__( 'Control access to WPBakery Page Builder AI assistant for text fields', 'js_composer' ),
] );
