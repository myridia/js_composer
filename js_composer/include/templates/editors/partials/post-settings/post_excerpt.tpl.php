<?php
/**
 * Post excerpt input in page settings panel template.
 *
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
global $post;
$excerpt = $post->post_excerpt;
?>

<div class="vc_col-sm-12 vc_column" id="vc_settings-post_excerpt">
	<div class="wpb_settings-title">
		<div class="wpb_element_label"><?php esc_html_e( 'Excerpt', 'js_composer' ); ?></div>
		<?php
			vc_include_template( 'editors/partials/param-info.tpl.php', [ 'description' => sprintf( esc_html__( 'Add a summary of the current %s content (Note: if left blank, the first few lines of the content will be used automatically)', 'js_composer' ), esc_html( get_post_type() ) ) ] );
		?>
	</div>
	<div class="edit_form_line">
		<?php
		if ( vc_modules_manager()->is_module_on( 'vc-ai' ) ) {
			wpb_add_ai_icon_to_text_field( 'textfield', 'vc_post_excerpt' );
		}
		?>

		<textarea name="post_excerpt" class="wpb_vc_param_value wpb-textarea textarea vc_post_excerpt" id="vc_post_excerpt"><?php echo esc_attr( $excerpt ); ?></textarea>
	</div>
</div>
