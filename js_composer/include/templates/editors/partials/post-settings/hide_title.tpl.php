<?php
/**
 * Page hide title section in page settings panel template.
 *
 * @since 8.2
 * @var array $page_settings_data
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$checked = $page_settings_data['is_hide_title'] ? 'checked' : '';
?>

<div class="vc_col-sm-12 vc_column">
	<div class="wpb-toggle-wrapper vc_settings-comments">
		<input type="checkbox" id="wpb_post-hide-title" <?php echo esc_attr( $checked ); ?> />
		<label for="wpb_post-hide-title"></label>
		<div class="wpb_element_label"><?php esc_html_e( 'Hide Page Title', 'js_composer' ); ?></div>
	</div>
</div>
