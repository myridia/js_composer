<?php
/**
 * Settings part template.
 *
 * @var string $part
 * @var string $role
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$tabs = [];
foreach ( vc_settings()->getTabs() as $tab => $title ) {
	$tabs[] = [ $tab . '-tab', $title ];
}
vc_include_template( 'pages/partials/vc-roles-parts/_part.tpl.php', [
	'part' => $part,
	'role' => $role,
	'params_prefix' => 'vc_roles[' . $role . '][' . $part . ']',
	'controller' => vc_role_access()->who( $role )->part( $part ),
	'custom_value' => 'custom',
	'capabilities' => $tabs,
	'options' => [
		[ true, esc_html__( 'All', 'js_composer' ) ],
		[ 'custom', esc_html__( 'Custom', 'js_composer' ) ],
		[ false, esc_html__( 'Disabled', 'js_composer' ) ],
	],
	'main_label' => esc_html__( 'Settings options', 'js_composer' ),
	'custom_label' => esc_html__( 'Settings options', 'js_composer' ),
	'description' => esc_html__( 'Control access rights to WPBakery Page Builder admin settings tabs (e.g. General Settings, Shortcode Mapper, ...)', 'js_composer' ),
] );
