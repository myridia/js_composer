<?php
/**
 * Page settings tab template.
 *
 * @since 8.1
 * @var array $page_settings_data
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<?php
if ( vc_modules_manager()->is_module_on( 'vc-custom-css' ) ) {
	?>
	<div class="vc_col-sm-12 vc_column">
		<div class="wpb_settings-title">
			<div class="wpb_element_label">
				<?php esc_html_e( 'Custom CSS settings', 'js_composer' ); ?>
			</div>
			<?php
			if ( is_string( $page_settings_data['css_info'] ) ) {
				echo $page_settings_data['css_info']; // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
		<div class="edit_form_line">
			<div class="vc_ui-settings-text-wrapper">
				<p class="wpb-code-editor-tag"><?php esc_html_e( '<style>' ); ?></p>
				<?php
				if ( vc_modules_manager()->is_module_on( 'vc-ai' ) ) {
					wpb_add_ai_icon_to_code_field( 'custom_css', 'wpb_css_editor' );
				}
				?>
			</div>
			<pre id="wpb_css_editor" class="wpb_content_element custom_code wpb_frontend" data-ace-location="page-settings"></pre>
			<p class="wpb-code-editor-tag"><?php esc_html_e( '</style>' ); ?></p>
		</div>
	</div>
	<?php
}
?>
<?php
if ( vc_modules_manager()->is_module_on( 'vc-custom-js' ) ) {
	?>
	<div class="vc_col-sm-12 vc_column">
		<div class="wpb_settings-title">
			<div class="wpb_element_label">
				<?php esc_html_e( 'Custom JavaScript in <head>', 'js_composer' ); ?>
			</div>
			<?php
			if ( is_string( $page_settings_data['js_head_info'] ) ) {
				echo $page_settings_data['js_head_info']; // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
		<div class="edit_form_line">
			<div class="vc_ui-settings-text-wrapper">
				<p class="wpb-code-editor-tag"><?php esc_html_e( '<script>' ); ?></p>
				<?php
				if ( vc_modules_manager()->is_module_on( 'vc-ai' ) ) {
					wpb_add_ai_icon_to_code_field( 'custom_js', 'wpb_js_header_editor' );
				}
				?>
			</div>
                <?php // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<pre id="wpb_js_header_editor" class="wpb_content_element custom_code wpb_frontend <?php echo $page_settings_data['can_unfiltered_html_cap'] ?: 'wpb_missing_unfiltered_html'; ?>" data-ace-location="page-settings"><?php echo $page_settings_data['can_unfiltered_html_cap'] ? '' : wpbakery()->getEditorsLocale()['unfiltered_html_access']; ?></pre>
			<p class="wpb-code-editor-tag"><?php esc_html_e( '</script>' ); ?></p>
		</div>
	</div>
	<div class="vc_col-sm-12 vc_column">
		<div class="wpb_settings-title">
			<div class="wpb_element_label">
				<?php esc_html_e( 'Custom JavaScript before </body>', 'js_composer' ); ?>
			</div>
			<?php
			if ( is_string( $page_settings_data['js_body_info'] ) ) {
				echo $page_settings_data['js_body_info']; // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
		<div class="edit_form_line">
			<div class="vc_ui-settings-text-wrapper">
				<p class="wpb-code-editor-tag"><?php esc_html_e( '<script>' ); ?></p>
				<?php
				if ( vc_modules_manager()->is_module_on( 'vc-ai' ) ) {
					wpb_add_ai_icon_to_code_field( 'custom_js', 'wpb_js_footer_editor' );
				}
				?>
			</div>
							<?php // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<pre id="wpb_js_footer_editor" class="wpb_content_element custom_code wpb_frontend <?php echo $page_settings_data['can_unfiltered_html_cap'] ?: 'wpb_missing_unfiltered_html'; ?>" data-ace-location="page-settings"><?php echo $page_settings_data['can_unfiltered_html_cap'] ? '' : wpbakery()->getEditorsLocale()['unfiltered_html_access']; ?></pre>
			<p class="wpb-code-editor-tag"><?php esc_html_e( '</script>' ); ?></p>
		</div>
	</div>
	<?php
}
?>
