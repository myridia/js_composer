<?php
/**
 * Menu section in page settings panel template.
 *
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<div class="vc_col-sm-12 vc_column" id="vc_settings-post_menu">
	<div class="wpb_settings-title">
		<div class="wpb_element_label"><?php esc_html_e( 'Manage menu', 'js_composer' ); ?></div>
	</div>
	<div class="edit_form_line">
		<a class="wpb_form-link" href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Manage your site menus', 'js_composer' ); ?></a>
		<span class="wpb_form-description"><?php esc_html_e( ' in the WordPress dashboard', 'js_composer' ); ?></span>
	</div>
</div>
