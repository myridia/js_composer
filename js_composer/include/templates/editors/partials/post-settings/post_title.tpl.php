<?php
/**
 * Page title section in page settings panel template.
 *
 * @since 8.2
 * @var array $page_settings_data
 */

?>

<div class="vc_col-sm-12 vc_column" id="vc_settings-title-container">
	<div class="wpb_settings-title">
		<div class="wpb_element_label"><?php esc_html_e( 'Page title', 'js_composer' ); ?></div>
	</div>
	<div class="edit_form_line">
		<?php
		if ( vc_modules_manager()->is_module_on( 'vc-ai' ) ) {
			wpb_add_ai_icon_to_text_field( 'textfield', 'vc_page-title-field' );
		}
		?>
		<input name="post_title" class="wpb-textinput vc_title_name" type="text" value="<?php echo esc_attr( $page_settings_data['post_title'] ); ?>" id="vc_page-title-field" placeholder="<?php esc_attr_e( 'Please enter page title', 'js_composer' ); ?>">
	</div>
</div>
