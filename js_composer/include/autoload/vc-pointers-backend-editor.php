<?php
/**
 * Autoload hooks related plugin initial pointers in backend editor.
 *
 * @note we require our autoload files everytime and everywhere after plugin load.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Add WP ui pointers to backend editor.
 */
function vc_add_admin_pointer() {
	if ( is_admin() ) {
		foreach ( vc_editor_post_types() as $post_type ) {
			add_filter( 'vc_ui-pointers-' . $post_type, 'vc_backend_editor_register_pointer' );
		}
	}
}

add_action( 'admin_init', 'vc_add_admin_pointer' );

/**
 * Register pointers for backend editor.
 *
 * @param array $pointers
 * @return mixed
 */
function vc_backend_editor_register_pointer( $pointers ) {
	$screen = get_current_screen();
	$block = false;
	if ( method_exists( $screen, 'is_block_editor' ) ) {
		if ( $screen->is_block_editor() ) {
			$block = true;
		}
	}
	if ( ! $block || 'add' === $screen->action ) {
		$pointers['vc_pointers_backend_editor'] = [
			'name' => 'vcPointerController',
			'messages' => [
				[
					'target' => '.composer-switch',
					'options' => [
						'content' => sprintf( '<h3> %s </h3> <p> %s </p>', esc_html__( 'Welcome to WPBakery Page Builder', 'js_composer' ), esc_html__( 'Choose Backend or Frontend editor.', 'js_composer' ) ),
						'position' => [
							'edge' => 'left',
							'align' => 'center',
						],
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					],
				],
				[
					'target' => '#vc_ui-panel-post-custom-layout',
					'options' => [
						'content' => sprintf( '<h3> %s </h3> <p> %s </p>', esc_html__( 'Layout selection', 'js_composer' ), esc_html__( 'Select the layout to be used for this post/page.', 'js_composer' ) ),
						'position' => [
							'edge' => 'bottom',
							'align' => 'center',
						],
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					],
					'showEvent' => 'backendEditor.show',
					'closeEvent' => 'click .vc_post-custom-layout.control-btn',
				],
				[
					'target' => '#vc_templates-editor-button, #vc-templatera-editor-button',
					'options' => [
						'content' => sprintf( '<h3> %s </h3> <p> %s </p>', esc_html__( 'Add Elements', 'js_composer' ), esc_html__( 'Add new element or start with a template.', 'js_composer' ) ),
						'position' => [
							'edge' => 'left',
							'align' => 'center',
						],
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					],
					'closeEvent' => 'shortcodes:vc_row:add',
				],
				[
					'target' => '[data-vc-control="add"]:first',
					'options' => [
						'content' => sprintf( '<h3> %s </h3> <p> %s </p>', esc_html__( 'Rows and Columns', 'js_composer' ), esc_html__( 'This is a row container. Divide it into columns and style it. You can add elements into columns.', 'js_composer' ) ),
						'position' => [
							'edge' => 'left',
							'align' => 'center',
						],
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					],
					'closeEvent' => 'click #wpb_wpbakery',
					'showEvent' => 'shortcodeView:ready',
				],
				[
					'target' => '.wpb_column_container:first .wpb_content_element:first .vc_controls-cc',
					'options' => [
						'content' => sprintf( '<h3> %s </h3> <p> %s <br/><br/> %s</p>', esc_html__( 'Control Elements', 'js_composer' ), esc_html__( 'You can edit your element at any time and drag it around your layout.', 'js_composer' ), sprintf( esc_html__( 'P.S. Learn more at our %1$sKnowledge Base%2$s.', 'js_composer' ), '<a href="https://kb.wpbakery.com" target="_blank">', '</a>' ) ),
						'position' => [
							'edge' => 'left',
							'align' => 'center',
						],
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					],
					'showCallback' => 'vcPointersShowOnContentElementControls',
					'closeEvent' => 'click #wpb_wpbakery',
				],
			],
		];

	}

	return $pointers;
}
