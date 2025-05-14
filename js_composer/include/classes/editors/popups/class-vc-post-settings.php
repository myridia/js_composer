<?php
/**
 * Post settings like custom css for page are displayed here.
 *
 * @since 4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Post_Settings.
 */
class Vc_Post_Settings {
	/**
	 * Editor type.
	 *
	 * @var mixed
	 */
	protected $editor;

	/**
	 * Post.
	 *
	 * @var WP_Post
	 */
	protected $post;

	/**
	 * Vc_Post_Settings constructor.
	 *
	 * @param mixed $editor
	 * @param WP_Post $post
	 */
	public function __construct( $editor, $post ) {
		$this->editor = $editor;
		$this->post = $post;
	}

	/**
	 * Get editor.
	 *
	 * @return mixed
	 */
	public function editor() {
		return $this->editor;
	}

	/**
	 * Render UI template.
	 */
	public function renderUITemplate() {

		$css_info = vc_get_template( 'editors/partials/param-info.tpl.php', [ 'description' => esc_html__( 'Enter custom CSS (Note: it will be outputted only on this particular page).', 'js_composer' ) ] );
		$js_head_info = vc_get_template( 'editors/partials/param-info.tpl.php', [ 'description' => esc_html__( 'Enter custom JS (Note: it will be outputted only on this particular page inside <head> tag).', 'js_composer' ) ] );
		$js_body_info = vc_get_template( 'editors/partials/param-info.tpl.php', [ 'description' => esc_html__( 'Enter custom JS (Note: it will be outputted only on this particular page before closing', 'js_composer' ) ] );

		vc_include_template( 'editors/popups/vc_ui-panel-post-settings.tpl.php',
		[
			'controls' => $this->getControls(),
			'box' => $this,
			'page_settings_data' => [
				'can_unfiltered_html_cap' =>
					vc_user_access()->part( 'unfiltered_html' )->checkStateAny( true, null )->get(),
				'css_info' => $css_info,
				'js_head_info' => $js_head_info,
				'js_body_info' => $js_body_info,
				'post_title' => $this->post->post_title,
				'is_hide_title' => wpb_is_hide_title( $this->post->ID ),
			],
			'header_tabs_template_variables' => [
				'categories' => $this->get_categories(),
				'templates' => $this->get_tabs_templates(),
				'is_default_tab' => true,
			],
			'permalink' => [
				'post_slug' => $this->post->post_name,
				'post_url_with_slug' => $this->get_post_url_up_to_slug( $this->post->ID, true ),
				'post_url_without_slug' => $this->get_post_url_up_to_slug( $this->post->ID ),
				'can_user_edit_permalink' => $this->can_user_edit_permalink(),
			],
		] );
	}

	/**
	 * Get modal popup template tabs.
	 *
	 * @param array $categories
	 *
	 * @since 8.1
	 * @return array
	 */
	public function get_tabs( $categories ) {
		$tabs = [];

		foreach ( $categories as $key => $name ) {
			$filter = '.js-category-' . md5( $name );

			$tabs[] = [
				'name' => $name,
				'filter' => $filter,
				'active' => 0 === $key,
			];
		}

		return $tabs;
	}

	/**
	 * Get tab categories.
	 *
	 * @sinse 8.1
	 * @return array
	 */
	public function get_categories() {
		return [
			esc_html__( 'Settings', 'js_composer' ),
			esc_html__( 'CSS & JS', 'js_composer' ),
		];
	}

	/**
	 * Get tabs templates.
	 *
	 * @since 8.1
	 * @return array
	 */
	public function get_tabs_templates() {
		return [
			'editors/popups/page-settings/page-settings-tab.tpl.php',
			'editors/popups/page-settings/custom-css-js-tab.tpl.php',
		];
	}

	/**
	 * Check if user can edit permalink, the similar way as in WordPress.
	 *
	 * @since 8.2
	 * @return bool
	 */
	public function can_user_edit_permalink() {
		$structure = get_option( 'permalink_structure' );

		// Plain structure: Slug is not editable.
		if ( empty( $structure ) ) {
			return false;
		}

		// Custom structure: Check if it contains %postname%.
		if ( strpos( $structure, '%postname%' ) !== false ) {
			return true; // Slug is editable.
		}

		// Numeric or custom without %postname%: Slug is not editable.
		return false;
	}

	/**
	 * Get post URL up to slug.
	 *
	 * @since 8.2
	 * @param int $post_id
	 * @param bool $include_slug
	 * @return string
	 */
	public function get_post_url_up_to_slug( $post_id, $include_slug = false ) {
		list( $base_url, $slug ) = get_sample_permalink( $post_id );

		$post_type = get_post_type( $post_id );
		// %pagename% is used for pages and custom post types, %postname% for posts.
		$placeholder = ( 'post' === $post_type ) ? '%postname%' : '%pagename%';

		if ( $include_slug ) {
			return str_replace( $placeholder, $slug, $base_url );
		}

		return str_replace( $placeholder . '/', '', $base_url );
	}

	/**
	 * Get controls of the post Settings panel, based on condition.
	 *
	 * @since 8.1
	 * @return array
	 */
	public function getControls() {
		$post = $this->post;
		$post_type = get_post_type_object( $post->post_type );
		$can_publish = current_user_can( $post_type->cap->publish_posts );

		// Initialize controls array.
		$controls = [
			[
				'name'  => 'close',
				'label' => esc_html__( 'Close', 'js_composer' ),
			],
		];

		// Add conditional save controls based on post status and user capabilities.
		if ( ! in_array( $post->post_status, [ 'publish', 'future', 'private' ], true ) ) {
			if ( 'draft' === $post->post_status || 'auto-draft' === $post->post_status ) {
				$controls[] = [
					'name'        => 'save-draft',
					'label'       => esc_html__( 'Save Draft', 'js_composer' ),
					'css_classes' => 'vc_ui-button-fw',
					'style'       => 'action',
				];
			} elseif ( 'pending' === $post->post_status && $can_publish ) {
				$controls[] = [
					'name'        => 'save-pending',
					'label'       => esc_html__( 'Save as Pending', 'js_composer' ),
					'css_classes' => 'vc_ui-button-fw',
					'style'       => 'action',
				];
			}
			if ( $can_publish ) {
				$controls[] = [
					'name'               => 'publish',
					'label'              => esc_html__( 'Publish', 'js_composer' ),
					'css_classes'        => 'vc_ui-button-fw',
					'style'              => 'action-secondary',
					'data_change_status' => 'publish',
				];
			} else {
				$controls[] = [
					'name'               => 'submit-review',
					'label'              => esc_html__( 'Submit for Review', 'js_composer' ),
					'css_classes'        => 'vc_ui-button-fw',
					'style'              => 'action',
					'data_change_status' => 'pending',
				];
			}
		} else {
			$controls[] = [
				'name'        => 'update',
				'label'       => esc_html__( 'Update', 'js_composer' ),
				'css_classes' => 'vc_ui-button-fw',
				'style'       => 'action',
			];
		}
		return $this->add_preview_control_button( $controls, $post );
	}

	/**
	 * Add preview button to control list.
	 *
	 * @since 8.3
	 * @param array $controls
	 * @param WP_Post $post
	 * @return array
	 */
	protected function add_preview_control_button( $controls, $post ) {
		$post_type_object = get_post_type_object( $post->post_type );
		if ( ! is_post_type_viewable( $post_type_object ) ) {
			return $controls;
		}
		$nonce = wp_create_nonce( 'post_preview_' . $post->ID );

		$settings = [
			'name'  => 'preview',
			'label' => esc_html__( 'Preview', 'js_composer' ),
			'id'  => 'wpb-settings-preview',
		];

		$post_type_object = get_post_type_object( $post->post_type );
		if ( is_post_type_viewable( $post_type_object ) ) {
			$link = get_preview_post_link( $post, [
				'preview_id' => $post->ID,
				'preview_nonce' => $nonce,
			] );
			$settings['link'] = $link;
		}

		$controls[] = $settings;

		return $controls;
	}
}
