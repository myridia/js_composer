<?php
/**
 * WPBakery Page Builder admin editor
 *
 * @package WPBakeryPageBuilder
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Base functionality for VC editors
 *
 * @package WPBakeryPageBuilder
 * @since 7.4
 */
require_once vc_path_dir( 'EDITORS_DIR', 'class-vc-editor.php' );

/**
 * VC backend editor.
 *
 * This editor is available on default Wp post/page admin edit page. ON admin_init callback adds meta box to
 * edit page.
 *
 * @since 4.2
 */
class Vc_Backend_Editor extends Vc_Editor {

	/**
	 * Stores data about the current post.
	 *
	 * @var mixed
	 */
	public $post = false;

	/**
	 * This method is called by Vc_Manager to register required action hooks for VC backend editor.
	 *
	 * @since  4.2
	 * @access public
	 */
	public function addHooksSettings() {
		if ( ! vc_user_access()->part( 'backend_editor' )->can()->get() ) {
			return;
		}

		// load backend editor.
		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' ); // @todo check is it needed?
		}
		add_action( 'add_meta_boxes', [
			$this,
			'render',
		], 5 );
		add_action( 'admin_print_scripts-post.php', [
			$this,
			'registerScripts',
		] );
		add_action( 'admin_print_scripts-post-new.php', [
			$this,
			'registerScripts',
		] );
		add_action( 'admin_print_scripts-post.php', [
			$this,
			'printScriptsMessages',
		] );
		add_action( 'admin_print_scripts-post-new.php', [
			$this,
			'printScriptsMessages',
		] );

		add_action( 'wp_ajax_wpb_backend_editor_params', [
			$this,
			'process_params',
		] );

		add_action( 'wp_ajax_wpb_single_image_data', [
			$this,
			'process_single_image_data',
		] );

		add_action( 'wp_ajax_wpb_get_gallery_html', [
			$this,
			'process_gallery_html',
		] );
	}

	/**
	 * Registers required JavaScript and CSS files.
	 */
	public function registerScripts() {
		$this->registerBackendJavascript();
		$this->registerBackendCss();
		// B.C.
		wpbakery()->registerAdminCss();
		wpbakery()->registerAdminJavascript();
	}

	/**
	 * Renders the meta box for the backend editor.
	 *
	 * @param string $post_type
	 * @throws \Exception
	 * @since  4.2
	 * @access public
	 */
	public function render( $post_type ) {
		if ( $this->isValidPostType( $post_type ) ) {
			// meta box to render.
			add_meta_box( 'wpb_wpbakery', esc_html__( 'WPBakery Page Builder', 'js_composer' ), [
				$this,
				'renderEditor',
			], $post_type, 'normal', 'high' );
		}
	}

	/**
	 * Output html for backend editor meta box.
	 *
	 * @param null|Wp_Post $post
	 *
	 * @return bool
	 */
	public function renderEditor( $post = null ) {
		/**
		 * TODO: setter/getter for $post
		 */
		if ( ! is_object( $post ) || 'WP_Post' !== get_class( $post ) || ! isset( $post->ID ) ) {
			return false;
		}
		$this->post = $post;
		$this->set_post_meta( $post );

		vc_include_template( 'editors/backend_editor.tpl.php', [
			'editor' => $this,
			'post' => $this->post,
			'wpb_vc_status' => $this->getEditorPostStatus(),
		] );
		add_action( 'admin_footer', [
			$this,
			'renderEditorFooter',
		] );
		do_action( 'vc_backend_editor_render' );

		return true;
	}

	/**
	 * Check if current post is edited lastly by our editor.
	 *
	 * @since 7.8
	 * @return mixed
	 */
	public function getEditorPostStatus() {
		$post_editor_status = wpb_get_post_editor_status( $this->post->ID );
		$get_param_status = vc_get_param( 'wpb_vc_js_status', $post_editor_status );
		$wpb_vc_status = apply_filters( 'wpb_vc_js_status_filter', $get_param_status );

		if ( '' === $wpb_vc_status || ! isset( $wpb_vc_status ) ) {
			$wpb_vc_status = vc_user_access()->part( 'backend_editor' )->checkState( 'default' )->get() ? 'true' : 'false';
		}

		return $wpb_vc_status;
	}


	/**
	 * Output required html and js content for VC editor.
	 *
	 * Here comes panels, modals and js objects with data for mapped shortcodes.
	 */
	public function renderEditorFooter() {
		if ( vc_is_gutenberg_editor() ) {
			return;
		}
		vc_include_template( 'editors/partials/backend_editor_footer.tpl.php', [
			'editor' => $this,
			'post' => $this->post,
		] );
		do_action( 'vc_backend_editor_footer_render' );
	}

	/**
	 * Check is post type is valid for rendering VC backend editor.
	 *
	 * @param string $type
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function isValidPostType( $type = '' ) {
		$type = ! empty( $type ) ? $type : get_post_type();
		if ( 'vc_grid_item' === $type ) {
			return false;
		}

		return apply_filters( 'vc_is_valid_post_type_be', vc_check_post_type( $type ), $type );
	}

	/**
	 * Enqueue required javascript libraries and css files.
	 *
	 * This method also setups reminder about license activation.
	 *
	 * @since  4.2
	 * @access public
	 */
	public function printScriptsMessages() {
		if ( ! vc_is_frontend_editor() && $this->isValidPostType( get_post_type() ) ) {
			$this->enqueueEditorScripts();
		}
	}

	/**
	 * Enqueue required javascript libraries and css files.
	 *
	 * @since  4.8
	 * @access public
	 */
	public function enqueueEditorScripts() {
		if ( $this->editorEnabled() ) {
			$this->enqueueJs();
			$this->enqueueCss();
			WPBakeryShortCodeFishBones::enqueueCss();
			WPBakeryShortCodeFishBones::enqueueJs();
		} else {
			wp_enqueue_script( 'vc-backend-actions-js' );
			$this->enqueueCss(); // needed for navbar @todo split.
		}
		do_action( 'vc_backend_editor_enqueue_js_css' );
	}

	/**
	 * Registers JavaScript files needed for the backend editor.
	 */
	public function registerBackendJavascript() {
		// editor can be disabled but fe can be enabled. so we currently need this file. @todo maybe make backend-disabled.min.js.
		wp_register_script( 'vc-backend-actions-js', vc_asset_url( 'js/dist/backend-actions.min.js' ), [
			'jquery-core',
			'backbone',
			'underscore',
		], WPB_VC_VERSION, true );
		// used in tta shortcodes, and panels.
		wp_register_script( 'vc_accordion_script', vc_asset_url( 'lib/vc/vc_accordion/vc-accordion.min.js' ), [ 'jquery-core' ], WPB_VC_VERSION, true );
		wp_register_script( 'vc-image-drop', vc_asset_url( 'js/dist/image-drop.min.js' ), [ 'jquery-core' ], WPB_VC_VERSION, true );
		wp_register_script( 'vc-backend-min-js', vc_asset_url( 'js/dist/backend.min.js' ), [
			'vc-backend-actions-js',
			'vc_accordion_script',
			'wp-color-picker',
		], WPB_VC_VERSION, true );
		wp_register_script( 'wpb_php_js', vc_asset_url( 'lib/vendor/php.default/php.default.min.js' ), [ 'jquery-core' ], WPB_VC_VERSION, true );
		// used as polyfill for JSON.stringify and etc.
		wp_register_script( 'wpb_json-js', vc_asset_url( 'lib/vendor/node_modules/json-js/json2.min.js' ), [], WPB_VC_VERSION, true );
		// used in post settings editor.
		wp_register_script( 'ace-editor', vc_asset_url( 'lib/vendor/node_modules/ace-builds/src-min-noconflict/ace.js' ), [ 'jquery-core' ], WPB_VC_VERSION, true );
		wp_register_script( 'wpb-code-editor', vc_asset_url( 'js/dist/post-code-editor.min.js' ), [ 'jquery-core' ], WPB_VC_VERSION, true );
		wp_register_script( 'webfont', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', [], WPB_VC_VERSION, true ); // Google Web Font CDN.
		wp_register_script( 'wpb-popper', vc_asset_url( 'lib/vendor/node_modules/@popperjs/core/dist/umd/popper.min.js' ), [], WPB_VC_VERSION, true );
		wp_register_script( 'pickr', vc_asset_url( 'lib/vendor/node_modules/@simonwep/pickr/dist/pickr.es5.min.js' ), [], WPB_VC_VERSION, true );

		vc_modules_manager()->register_modules_script();

		wp_localize_script( 'vc-backend-actions-js', 'i18nLocale', wpbakery()->getEditorsLocale() );
		wp_localize_script( 'vc-backend-actions-js', 'wpbData', wpbakery()->getEditorsWpbData() );

		do_action( 'wpb_after_register_backend_editor_js', $this );
	}

	/**
	 * Registers CSS files needed for the backend editor.
	 */
	public function registerBackendCss() {
		wp_register_style( 'js_composer', vc_asset_url( 'css/js_composer_backend_editor.min.css' ), [], WPB_VC_VERSION, false );
		wp_register_style( 'wpb_modules_css', vc_asset_url( 'css/modules.min.css' ), [], WPB_VC_VERSION, false );

		if ( $this->editorEnabled() ) {
			/**
			 * Used for accordions/tabs/tours.
			 *
			 * @deprecated
			 */
			wp_register_style( 'ui-custom-theme', vc_asset_url( 'css/jquery-ui-less.custom.min.css' ), [], WPB_VC_VERSION );

			/**
			 * Also used in vc_icon shortcode.
			 *
			 * @todo check vc_add-element-deprecated-warning for fa icon usage ( set to our font )
			 */
			wp_register_style( 'vc_font_awesome_5_shims', vc_asset_url( 'lib/vendor/node_modules/@fortawesome/fontawesome-free/css/v4-shims.min.css' ), [], WPB_VC_VERSION );
			wp_register_style( 'vc_font_awesome_6', vc_asset_url( 'lib/vendor/node_modules/@fortawesome/fontawesome-free/css/all.min.css' ), [ 'vc_font_awesome_5_shims' ], WPB_VC_VERSION );
			/**
			 * Definitely used in edit form param: css_animation, but currently vc_add_shortcode_param doesn't accept css.
			 *
			 * @todo check for usages
			 */
			wp_register_style( 'vc_animate-css', vc_asset_url( 'lib/vendor/node_modules/animate.css/animate.min.css' ), [], WPB_VC_VERSION );
			wp_register_style( 'pickr', vc_asset_url( 'lib/vendor/node_modules/@simonwep/pickr/dist/themes/classic.min.css' ), [], WPB_VC_VERSION, false );
			wp_register_style( 'vc_google_fonts', 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,500&display=swap', [], WPB_VC_VERSION );
		}

		do_action( 'wpb_after_register_backend_editor_css', $this );
	}

	/**
	 * Enqueues JavaScript files for the backend editor.
	 */
	public function enqueueJs() {
		$wp_dependencies = [
			'jquery-core',
			'underscore',
			'backbone',
			'media-views',
			'media-editor',
			'wp-pointer',
			'mce-view',
			'wp-color-picker',
			'jquery-ui-sortable',
			'jquery-ui-droppable',
			'jquery-ui-draggable',
			'jquery-ui-autocomplete',
			'jquery-ui-resizable',
			// used in @deprecated tabs.
			'jquery-ui-tabs',
			'jquery-ui-accordion',
		];
		$dependencies = [
			'vc_accordion_script',
			'wpb_php_js',
			// used in our files [e.g. edit form saving sprintf].
			'wpb_json-js',
			'webfont',
			'wpb-popper',
			'vc-backend-min-js',
			'wpb-modules-js',
			'pickr',
			'ace-editor',
		];

		// Enqueue image drop script only if it is allowed via Role Manager.
		if (
			vc_user_access()->part( 'shortcodes' )->getState() === true ||
			vc_user_access()->part( 'shortcodes' )->can( 'vc_single_image_all' )->get() === true
		) {
			$dependencies[] = 'vc-image-drop';
		}

		$common = apply_filters( 'wpb_enqueue_backend_editor_js', array_merge( $wp_dependencies, $dependencies ) );

		// This workaround will allow to disable any of dependency on-the-fly.
		foreach ( $common as $dependency ) {
			wp_enqueue_script( $dependency );
		}
	}

	/**
	 * Enqueues CSS files for the backend editor.
	 */
	public function enqueueCss() {
		$wp_dependencies = [
			'wp-color-picker',
			'farbtastic',
			// deprecated for tabs/accordion.
			'ui-custom-theme',
			// used in deprecated message and also in vc-icon shortcode.
			'vc_font_awesome_6',
			// used in css_animation edit form param.
			'vc_animate-css',
		];
		$dependencies = [
			'js_composer',
			'wpb_modules_css',
			'pickr',
			'vc_google_fonts',
		];

		$common = apply_filters( 'wpb_enqueue_backend_editor_css', array_merge( $wp_dependencies, $dependencies ) );

		// This workaround will allow to disable any of dependency on-the-fly.
		foreach ( $common as $dependency ) {
			wp_enqueue_style( $dependency );
		}
	}

	/**
	 * Checks if the backend editor is enabled.
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function editorEnabled() {
		return vc_user_access()->part( 'backend_editor' )->can()->get();
	}

	/**
	 * Process params.
	 * As we parse back editor content with our shortcode in js side
	 * We need additional ajax request to get some specific params options that are not available in editor content.
	 *
	 * @since 8.3
	 */
	public function process_params() {
		vc_user_access()->checkAdminNonce()->validateDie();

		$elements = vc_post_param( 'elements', [] );
		$response_data = [];

		foreach ( $elements as $element_id => $element_data ) {
			if ( empty( $element_data['action'] ) || ! method_exists( $this, $element_data['action'] ) ) {
				continue;
			}

			$response_data[ $element_id ] = $this->{$element_data['action']}( $element_data );
			$response_data[ $element_id ]['action'] = $element_data['action'];
			if ( ! empty( $element_data['paramName'] ) ) {
				$response_data[ $element_id ]['paramName'] = $element_data['paramName'];
			}
		}

		wp_send_json_success( $response_data );
	}

	/**
	 * Get attach_image param element data.
	 * We use it to get 'Single Image' element data when editing elements.
	 *
	 * @since 8.3
	 */
	public function process_single_image_data() {
		vc_user_access()->checkAdminNonce()->validateDie();

		$image_id = (int) vc_post_param( 'content' );
		$params = vc_post_param( 'params' );
		$post_id = (int) vc_post_param( 'postId' );

		$source = empty( $params['source'] ) ? 'media_library' : $params['source'];

		$image_data = wpb_get_image_data_by_source( $source, $post_id, $image_id, null );
		wp_send_json_success( $image_data );
	}

	/**
	 * Get 'Single Image' element data.
	 * We use it to get 'Single Image' attach_image param thumbnail data
	 * when process ajax request common for all elements in backend editor.
	 *
	 * @param array $element_data
	 *
	 * @return array
	 * @see $this->process_params()
	 * @since 8.3
	 */
	public function get_attach_image( $element_data ) {
		if ( ! isset( $element_data['source'], $element_data['value'] ) ) {
			return [];
		}
		$post_id = (int) vc_post_param( 'post_id' );

		return wpb_get_image_data_by_source( $element_data['source'], $post_id, $element_data['value'], null );
	}

	/**
	 * Get attach_images element param data.
	 * We use it to get gallery elements data when editing elements.
	 *
	 * @since 8.3
	 */
	/**
	 * Get gallery element html.
	 *
	 * @since 8.3
	 */
	public function process_gallery_html() {
		$images = vc_post_param( 'content' );
		if ( empty( $images ) ) {
			wp_send_json_error();
		}

		wp_send_json_success( vc_field_attached_images( explode( ',', $images ) ) );
	}

	/**
	 * Get gallery elements data.
	 * We use it to get gallery elements attach_images param thumbnails data
	 * when process ajax request common for all elements in backend editor.
	 *
	 * @param array $element_data
	 *
	 * @return array
	 * @see $this->process_params()
	 * @since 8.3
	 */
	public function get_gallery_images( $element_data ) {
		if ( ! isset( $element_data['source'], $element_data['value'] ) ) {
			return [];
		}

		return [ 'html' => vc_field_attached_images( explode( ',', $element_data['value'] ) ) ];
	}
}
