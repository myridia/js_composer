<?php
/**
 * UI Panel Post Settings template.
 *
 * @var array $page_settings_data
 * @var Vc_Post_Settings $box
 * @var array $header_tabs_template_variables
 * @var array $controls
 * @var array $permalink
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="vc_ui-font-open-sans vc_ui-panel-window vc_media-xs vc_ui-panel" data-vc-panel=".vc_ui-panel-header-header" data-vc-ui-element="panel-post-settings" id="vc_ui-panel-post-settings">
	<div class="vc_ui-panel-window-inner">
	<?php
	// First collect all tab contents and determine which tabs have content.
	$tab_contents = [];
	$categories = $header_tabs_template_variables['categories'];
	$has_any_content = false;
	$original_templates = $header_tabs_template_variables['templates'];

	foreach ( $original_templates as $key => $template_name ) {
		ob_start();
		vc_include_template(
			$template_name,
			[
				'page_settings_data' => $page_settings_data,
				'permalink' => $permalink,
			]
		);
		$content = ob_get_clean();
		if ( ! empty( trim( $content ) ) ) {
			$tab_contents[ $key ] = [
				'content' => $content,
				'template' => $template_name,
			];
			$has_any_content = true;
		}
	}

	if ( $has_any_content ) {
		vc_include_template('editors/popups/vc_ui-header.tpl.php', [
			'title' => esc_html__( 'Page Settings', 'js_composer' ),
			'controls' => [ 'minimize', 'close' ],
			'header_css_class' => 'vc_ui-post-settings-header-container',
			'header_tabs_template' => 'editors/partials/add_element_tabs.tpl.php',
			'header_tabs_template_variables' => [
				'categories' => array_values(array_filter($categories, function ( $key ) use ( $tab_contents ) {
					return isset( $tab_contents[ $key ] );
				}, ARRAY_FILTER_USE_KEY)),
				'templates' => array_map( function ( $tab ) {
					return $tab['template']; }, $tab_contents ),
				'is_default_tab' => true,
			],
			'box' => $box,
		]);
		?>
		<div class="vc_ui-panel-content-container">
			<div class="vc_ui-panel-content vc_properties-list vc_edit_form_elements" data-vc-ui-element="panel-content">
				<div class="vc_panel-tabs">
					<?php
					foreach ( $tab_contents as $key => $tab ) {
						$active_class = array_key_first( $tab_contents ) === $key ? ' vc_active' : '';
						echo '<div id="vc_page-settings-tab-' . esc_attr( $key ) . '" class="vc_panel-tab vc_row' . esc_attr( $active_class ) . '" data-tab-index="' . esc_attr( $key ) . '">';
						echo '<div class="vc_row">';
						echo $tab['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '</div>';
						echo '</div>';
					}
					?>
				</div>
			</div>
		</div>
		<?php
		// Include the template with the dynamic controls array.
		vc_include_template(
			'editors/popups/vc_ui-footer.tpl.php',
			[
				'controls' => $controls,
			]
		);
	}
	?>
	</div>
</div>
