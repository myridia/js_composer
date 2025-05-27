<?php
/**
 * Page settings tab template.
 *
 * @since 8.1
 * @var array $page_settings_data
 * @var array $permalink
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
global $post;
$is_custom_template_module_on = vc_modules_manager()->is_module_on( 'vc-post-custom-layout' );
$is_layout_blank = apply_filters( 'wpb_is_post_custom_layout_blank', false );
?>

<?php
if ( vc_is_frontend_editor() ) {
	vc_include_template(
		'editors/partials/post-settings/post_title.tpl.php',
		[ 'page_settings_data' => $page_settings_data ]
	);

	if ( ! $is_layout_blank ) {
		vc_include_template(
			'editors/partials/post-settings/hide_title.tpl.php',
			[ 'page_settings_data' => $page_settings_data ]
		);
	}
}
if ( $is_custom_template_module_on ) {
	?>
	<div class="vc_col-sm-12 vc_column" id="vc_settings-post_custom_layout">
		<div class="wpb_settings-title">
			<div class="wpb_element_label"><?php esc_html_e( 'Layout Option', 'js_composer' ); ?></div>
			<?php vc_include_template( 'editors/partials/param-info.tpl.php', [ 'description' => sprintf( esc_html__( 'Change the layout of the current %s (Note: selecting Blank Page Layout will remove the header, footer, and sidebar).', 'js_composer' ), esc_html( get_post_type() ) ) ] ); ?>
		</div>
		<?php
		vc_include_template(
			'editors/partials/vc_post_custom_layout.tpl.php',
			[ 'location' => 'settings' ]
		);
		?>
	</div>
	<?php
}

if ( vc_is_frontend_editor() ) {
	vc_include_template(
		'editors/partials/post-settings/wordpress_settings.tpl.php',
		[
			'page_settings_data' => $page_settings_data,
			'is_layout_blank' => $is_layout_blank,
			'permalink' => $permalink,
		]
	);
}
