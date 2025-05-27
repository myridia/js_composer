<?php
/**
 *  Add new category section in post settings panel template.
 *
 * @since 8.2
 */

?>
<div id="vc_toggle-add-new-category"><?php esc_html_e( 'Add New Category', 'js_composer' ); ?></div>
<div id="vc_add-new-category" style="display: none;">
	<input type="text" id="vc_new-category" placeholder="Category Name" />
	<select id="vc_new-category-parent">
		<option value=""><?php esc_html_e( '— Parent Category —', 'js_composer' ); ?></option>
		<?php $vc_settings_category_manager->render_category_options_with_indent( null, 0, false ); ?>
	</select>
	<span id="vc_add-new-category-btn" class="vc_general vc_ui-button vc_ui-button-action vc_ui-button-shape-rounded"><?php esc_html_e( 'Add', 'js_composer' ); ?></span>
</div>
