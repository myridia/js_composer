<?php
/**
 * Manager for new post type for single grid item design with constructor.
 *
 * @package WPBakeryPageBuilder
 * @since 4.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'EDITORS_DIR', 'class-vc-backend-editor.php' );

/**
 * Class Vc_Grid_Item_Editor
 */
class Vc_Grid_Item_Editor extends Vc_Backend_Editor {
	/**
	 * Post type for grid item.
	 *
	 * @var string
	 */
	protected static $post_type = 'vc_grid_item';
	/**
	 * Templates editor instance.
	 *
	 * @var bool|Vc_Templates_Editor_Grid_Item
	 */
	protected $templates_editor = false;

	/**
	 * This method is called to add hooks.
	 *
	 * @since  4.8
	 * @access public
	 */
	public function addHooksSettings() {
		add_action( 'add_meta_boxes', [
			$this,
			'render',
		] );
		add_action( 'vc_templates_render_backend_template', [
			$this,
			'loadTemplate',
		], 10, 2 );
	}

	/**
	 * Add scripts.
	 */
	public function addScripts() {
		$this->render( get_post_type() );
	}

	/**
	 * Render grid item editor.
	 *
	 * @param string $post_type
	 * @throws Exception
	 */
	public function render( $post_type ) {
		if ( $this->isValidPostType( $post_type ) ) {
			$this->registerBackendJavascript();
			$this->registerBackendCss();
			// B.C.
			wpbakery()->registerAdminCss();
			wpbakery()->registerAdminJavascript();
			add_action( 'admin_print_scripts-post.php', [
				$this,
				'printScriptsMessages',
			], 300 );
			add_action( 'admin_print_scripts-post-new.php', [
				$this,
				'printScriptsMessages',
			], 300 );
		}
	}

	/**
	 * Check if editor is enabled.
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function editorEnabled() {
		return vc_user_access()->part( 'grid_builder' )->can()->get();
	}

	/**
	 * Replace templates panel editor js.
	 */
	public function replaceTemplatesPanelEditorJsAction() {
		wp_dequeue_script( 'vc-template-preview-script' );
		$this->templatesEditor()->addScriptsToTemplatePreview();
	}

	/**
	 * Create post type and new item in the admin menu.
	 *
	 * @return void
	 */
	public static function createPostType() {
		register_post_type( self::$post_type, [
			'labels' => self::getPostTypesLabels(),
			'public' => false,
			'has_archive' => false,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => false,
			'query_var' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => [
				'title',
				'editor',
			],
		] );
	}

	/**
	 * Get post type labels.
	 *
	 * @return array
	 */
	public static function getPostTypesLabels() {
		return [
			'add_new_item' => esc_html__( 'Add Grid template', 'js_composer' ),
			'name' => esc_html__( 'Grid Builder', 'js_composer' ),
			'singular_name' => esc_html__( 'Grid template', 'js_composer' ),
			'edit_item' => esc_html__( 'Edit Grid template', 'js_composer' ),
			'view_item' => esc_html__( 'View Grid template', 'js_composer' ),
			'search_items' => esc_html__( 'Search Grid templates', 'js_composer' ),
			'not_found' => esc_html__( 'No Grid templates found', 'js_composer' ),
			'not_found_in_trash' => esc_html__( 'No Grid templates found in Trash', 'js_composer' ),
		];
	}

	/**
	 * Rewrites validation for correct post_type of th post.
	 *
	 * @param string $type
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function isValidPostType( $type = '' ) {
		$type = ! empty( $type ) ? $type : get_post_type();

		return $this->editorEnabled() && $this->postType() === $type;
	}

	/**
	 * Get post type for Vc grid element editor.
	 *
	 * @static
	 * @return string
	 */
	public static function postType() {
		return self::$post_type;
	}

	/**
	 * Calls add_meta_box to create Editor block.
	 *
	 * @access public
	 */
	public function addMetaBox() {
		add_meta_box( 'wpb_wpbakery', esc_html__( 'Grid Builder', 'js_composer' ), [
			$this,
			'renderEditor',
		], $this->postType(), 'normal', 'high' );
	}

	/**
	 * Change order of the controls for shortcodes admin block.
	 *
	 * @return array
	 */
	public function shortcodesControls() {
		return [
			'delete',
			'edit',
		];
	}

	/**
	 * Output html for backend editor meta box.
	 *
	 * @param null|int $post
	 *
	 * @throws Exception
	 */
	public function renderEditor( $post = null ) {
		if ( ! vc_user_access()->part( 'grid_builder' )->can()->get() ) {
			return;
		}

		require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/class-vc-grid-item.php' );
		$this->post = $post;
		vc_include_template( 'params/vc_grid_item/editor/vc_grid_item_editor.tpl.php', [
			'editor' => $this,
			'post' => $this->post,
		] );
		add_action( 'admin_footer', [
			$this,
			'renderEditorFooter',
		] );
		do_action( 'vc_backend_editor_render' );
		do_action( 'vc_vc_grid_item_editor_render' );
		add_action( 'vc_user_access_check-shortcode_edit', [
			$this,
			'accessCheckShortcodeEdit',
		], 10, 2 );
		add_action( 'vc_user_access_check-shortcode_all', [
			$this,
			'accessCheckShortcodeAll',
		], 10, 2 );
	}

	/**
	 * Check if user has access to edit shortcode.
	 *
	 * @param null $nullable
	 * @param string $shortcode
	 * @return bool
	 * @throws Exception
	 */
	public function accessCheckShortcodeEdit( $nullable, $shortcode ) {
		if ( ! vc_user_access()->part( 'grid_builder' )->can()->get() ) {
			return false;
		}

		$params = vc_get_shortcode( $shortcode );
		if ( ! empty( $params['category'] ) && 'Post' === $params['category'] ) {
				return true;
		} else {
			return vc_get_user_shortcode_access( $shortcode, 'edit' );
		}
	}

	/**
	 * Check if user has access to all shortcodes.
	 *
	 * @param null $nullable
	 * @param string $shortcode
	 * @return bool
	 * @throws Exception
	 */
	public function accessCheckShortcodeAll( $nullable, $shortcode ) {
		if ( ! vc_user_access()->part( 'grid_builder' )->can()->get() ) {
			return false;
		}

		$params = vc_get_shortcode( $shortcode );
		if ( ! empty( $params['category'] ) && 'Post' === $params['category'] ) {
			return true;
		} else {
			return vc_get_user_shortcode_access( $shortcode );
		}
	}

	/**
	 * Output required html and js content for VC editor.
	 *
	 * Here comes panels, modals and js objects with data for mapped shortcodes.
	 */
	public function renderEditorFooter() {
		vc_include_template( 'params/vc_grid_item/editor/partials/vc_grid_item_editor_footer.tpl.php', [
			'editor' => $this,
			'post' => $this->post,
		] );
		do_action( 'vc_backend_editor_footer_render' );
	}

	/**
	 * Register and localize backend javascript.
	 */
	public function registerBackendJavascript() {
		parent::registerBackendJavascript();
		wp_register_script( 'vc_grid_item_editor', vc_asset_url( 'js/dist/grid-builder.min.js' ), [ 'vc-backend-min-js' ], WPB_VC_VERSION, true );
		wp_localize_script( 'vc_grid_item_editor', 'i18nLocaleGItem', [
			'preview' => esc_html__( 'Preview', 'js_composer' ),
			'builder' => esc_html__( 'Builder', 'js_composer' ),
			'add_template_message' => esc_html__( 'If you add this template, all your current changes will be removed. Are you sure you want to add template?', 'js_composer' ),
		] );
	}

	/**
	 * Enqueue js.
	 */
	public function enqueueJs() {
		parent::enqueueJs();
		wp_enqueue_script( 'vc_grid_item_editor' );
	}

	/**
	 * Set templates editor instance.
	 *
	 * @return bool|Vc_Templates_Editor_Grid_Item
	 */
	public function templatesEditor() {
		if ( false === $this->templates_editor ) {
			require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/editor/popups/class-vc-templates-editor-grid-item.php' );
			$this->templates_editor = new Vc_Templates_Editor_Grid_Item();
		}

		return $this->templates_editor;
	}

	/**
	 * Load predefined template.
	 *
	 * @param int $template_id
	 * @param string $template_type
	 * @return false|string
	 */
	public function loadPredefinedTemplate( $template_id, $template_type ) {
		ob_start();
		$this->templatesEditor()->load( $template_id );

		return ob_get_clean();
	}

	/**
	 * Load template.
	 *
	 * @param int $template_id
	 * @param string $template_type
	 * @return false|string
	 */
	public function loadTemplate( $template_id, $template_type ) {
		if ( 'grid_templates' === $template_type ) {
			return $this->loadPredefinedTemplate( $template_id, $template_type );
		} elseif ( 'grid_templates_custom' === $template_type ) {
			return $this->templatesEditor()->loadCustomTemplate( $template_id );
		}

		return $template_id;
	}

	/**
	 * Get template preview path.
	 *
	 * @param string $path
	 * @return string
	 */
	public function templatePreviewPath( $path ) {
		return 'params/vc_grid_item/editor/vc_ui-template-preview.tpl.php';
	}

	/**
	 * Render template preview.
	 */
	public function renderTemplatePreview() {
		vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie()->part( 'grid_builder' )->can()->validateDie();

		add_action( 'vc_templates_render_backend_template_preview', [
			$this,
			'loadTemplate',
		], 10, 2 );
		add_filter( 'vc_render_template_preview_include_template', [
			$this,
			'templatePreviewPath',
		] );
		wpbakery()->templatesPanelEditor()->renderTemplatePreview();
	}
}
