<?php
/**
 * Autoload hooks related plugin initial pointers in frontend editor.
 *
 * @note we require our autoload files everytime and everywhere after plugin load.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Add WP ui pointers to backend editor.
 */
function vc_frontend_editor_pointer() {
	vc_is_frontend_editor() && add_filter( 'vc-ui-pointers', 'vc_frontend_editor_register_pointer' );
}

add_action( 'admin_init', 'vc_frontend_editor_pointer' );

/**
 * Register pointer.
 *
 * @param array $pointers
 * @return mixed
 */
function vc_frontend_editor_register_pointer( $pointers ) {
	global $post;
	if ( is_object( $post ) && ! strlen( $post->post_content ) ) {
		$pointers['vc_pointers_frontend_editor'] = [
			'name' => 'vcPointerController',
			'messages' => [
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
					'closeEvent' => 'click .vc_post-custom-layout.control-btn',
				],
				[
					'target' => '#vc_add-new-element',
					'options' => [
						'content' => sprintf( '<h3> %s </h3> <p> %s </p>', esc_html__( 'Add Elements', 'js_composer' ), esc_html__( 'Add new element or start with a template.', 'js_composer' ) ),
						'position' => [
							'edge' => 'top',
							'align' => 'left',
						],
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					],
					'closeEvent' => 'shortcodes:add',
				],
				[
					'target' => '.vc_controls-out-tl:first',
					'options' => [
						'content' => sprintf( '<h3> %s </h3> <p> %s </p>', esc_html__( 'Rows and Columns', 'js_composer' ), esc_html__( 'This is a row container. Divide it into columns and style it. You can add elements into columns.', 'js_composer' ) ),
						'position' => [
							'edge' => 'left',
							'align' => 'center',
						],
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					],
					'closeCallback' => 'vcPointersCloseInIFrame',
					'showCallback' => 'vcPointersSetInIFrame',
				],
				[
					'target' => '.vc_controls-cc:not(.vc_controls-title):first-of-type',
					'options' => [
						'content' => sprintf( '<h3> %s </h3> <p> %s <br/><br/> %s</p>', esc_html__( 'Control Elements', 'js_composer' ), esc_html__( 'You can edit your element at any time and drag it around your layout.', 'js_composer' ), sprintf( esc_html__( 'P.S. Learn more at our %1$sKnowledge Base%2$s.', 'js_composer' ), '<a href="https://kb.wpbakery.com" target="_blank">', '</a>' ) ),
						'position' => [
							'edge' => 'left',
							'align' => 'center',
						],
						'buttonsEvent' => 'vcPointersEditorsTourEvents',
					],
					'closeCallback' => 'vcPointersCloseInIFrame',
					'showCallback' => 'vcPointersSetInIFrame',
				],
			],
		];
	}
	return $pointers;
}

/**
 * Enqueue pointer scripts.
 */
function vc_page_editable_enqueue_pointer_scripts() {
	if ( vc_is_page_editable() ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}
}

add_action( 'wp_enqueue_scripts', 'vc_page_editable_enqueue_pointer_scripts' );
