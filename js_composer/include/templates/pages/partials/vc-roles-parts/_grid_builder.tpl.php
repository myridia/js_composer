<?php
/**
 * Grid Builder part template.
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
		[
			true,
			esc_html__( 'Enabled', 'js_composer' ),
		],
		[
			false,
			esc_html__( 'Disabled', 'js_composer' ),
		],
	],
	'main_label' => esc_html__( 'Grid Builder', 'js_composer' ),
	'custom_label' => esc_html__( 'Grid Builder', 'js_composer' ),
	'description' => esc_html__( 'Control user access to Grid Builder and Grid Builder Elements.', 'js_composer' ),
] );
