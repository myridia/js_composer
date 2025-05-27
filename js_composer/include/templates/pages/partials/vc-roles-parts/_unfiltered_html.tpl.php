<?php
/**
 * Unfiltered HTML part template.
 *
 * @var string $role
 * @var string $part
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
	'main_label' => esc_html__( 'Unfiltered HTML', 'js_composer' ),
	'description' => esc_html__( 'Allow to use Custom HTML in WPBakery Page Builder.', 'js_composer' ),
] );
