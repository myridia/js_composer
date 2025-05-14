<?php
/**
 * Post categories section in post settings panel template.
 *
 * @since 8.2
 */

// Category Manager Class.
require_once vc_path_dir( 'EDITORS_DIR', 'popups/class-vc-post-settings-category-manager.php' );

$post_id = get_the_ID();
$vc_settings_category_manager = new Vc_Post_Settings_Category_Manager( $post_id );
?>

<div class="vc_col-sm-12 vc_column" id="vc_settings-post-category">
	<div class="wpb_element_label"><?php esc_html_e( 'Select Category', 'js_composer' ); ?></div>
	<div class="edit_form_line">
		<select id="vc_post-category" name="vc_post-category" multiple="multiple">
			<?php $vc_settings_category_manager->render_category_options_with_indent(); ?>
		</select>
	</div>
	<?php
		vc_include_template( 'editors/partials/post-settings/post_add-category.tpl.php', [ 'vc_settings_category_manager' => $vc_settings_category_manager ] );
	?>
</div>
