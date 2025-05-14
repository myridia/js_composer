<?php
/**
 * Autoload lib for default template for post type manager.
 *
 * @note we require our autoload files everytime and everywhere after plugin load.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! function_exists( 'vc_set_default_content_for_post_type_wpb_vc_js_status_filter' ) ) {
	/**
	 * Return true value for filter 'wpb_vc_js_status_filter'.
	 * It allows to start backend editor on load.
	 *
	 * @return string
	 * @since 4.12
	 */
	function vc_set_default_content_for_post_type_wpb_vc_js_status_filter() {
		return 'true';
	}
}

if ( ! function_exists( 'vc_set_default_content_for_post_type' ) ) {
	/**
	 * Set default content by post type in editor.
	 *
	 * Data for post type templates stored in settings.
	 *
	 * @param string|null $post_content
	 * @param WP_Post $post
	 * @return string|null
	 * @throws Exception
	 * @since 4.12
	 */
	function vc_set_default_content_for_post_type( $post_content, $post ) {
		if ( ! empty( $post_content ) || ! vc_backend_editor()->isValidPostType( $post->post_type ) ) {
			return $post_content;
		}
		$template_settings = new Vc_Setting_Post_Type_Default_Template_Field( 'general', 'default_template_post_type' );
		$new_post_content = $template_settings->getTemplateByPostType( $post->post_type );
		if ( null !== $new_post_content ) {
			add_filter( 'wpb_vc_js_status_filter', 'vc_set_default_content_for_post_type_wpb_vc_js_status_filter' );

			return $new_post_content;
		}

		return $post_content;
	}
}

if ( ! function_exists( 'vc_is_default_content_for_post_type' ) ) {
	/**
	 * Check if default content for post type is set.
	 *
	 * @since 8.2
	 * @param string $post_type
	 * @return bool
	 */
	function vc_is_default_content_for_post_type( $post_type ) {
		$template_settings = new Vc_Setting_Post_Type_Default_Template_Field( 'general', 'default_template_post_type' );
		$option_key = $template_settings->getFieldKey();
		$default_content_post_types = get_option( $option_key, [] );

		if ( isset( $default_content_post_types[ $post_type ] ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'vc_add_backend_editor_param_to_button_link' ) ) {
	/**
	 * Check if default content set and if yes
	 * add backend editor param to 'Add New Post' button links.
	 *
	 * @since 8.2
	 * @param string $url
	 * @param string $path
	 * @return string
	 */
	function vc_add_backend_editor_param_to_button_link( $url, $path ) {
		$is_new_post_path = strpos( $path, 'post-new.php' );

		if ( false === $is_new_post_path ) {
			return $url;
		}

		$post_type = preg_match( '/\bpost_type=([^&]+)/', $url, $matches ) ? $matches[1] : 'post';

		if ( vc_is_default_content_for_post_type( $post_type ) ) {
			$url = add_query_arg( 'wpb-backend-editor', '', $url );
		}

		return $url;
	}
}

if ( ! function_exists( 'vc_redirect_new_post_page_to_back_editor' ) ) {
	/**
	 * Process our redirect to backend editor user role functionality.
	 *
	 * @since 8.4
	 */
	function vc_redirect_new_post_page_to_back_editor() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( isset( $_GET['wpb-backend-editor'] ) ) {
			return;
		}

		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}

		global $pagenow;
		if ( 'post-new.php' !== $pagenow ) {
			return;
		}

		$is_backend_editor_default = vc_user_access()->part( 'backend_editor' )->checkState( 'default' )->get();
		if ( ! $is_backend_editor_default ) {
			return;
		}

		$post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : 'post';
		if ( ! vc_check_post_type( $post_type ) ) {
			return;
		}

		$request_uri = sanitize_url( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		$url = add_query_arg( 'wpb-backend-editor', '', $request_uri );
		wp_safe_redirect( $url );
		exit;
	}
}


if ( ! function_exists( 'vc_add_backend_editor_param_add_post_menu_links' ) ) {
	/**
	 * Check if default content set and if yes
	 * add backend editor param to 'Add New Post' menu links.
	 *
	 * @since 8.2
	 */
	function vc_add_backend_editor_param_add_post_menu_links() {
		global $submenu;

		// Loop through the $menu array to find and modify links.
		foreach ( $submenu as $key => $menu_item ) {
			if ( ! isset( $menu_item[10][2] ) ) {
				continue;
			}

			$is_new_post_path = strpos( $menu_item[10][2], 'post-new.php' );
			if ( false === $is_new_post_path ) {
				continue;
			}

			$post_type = preg_match( '/\bpost_type=([^&]+)/', $menu_item[10][2], $matches ) ? $matches[1] : 'post';

			if ( vc_is_default_content_for_post_type( $post_type ) ) {
				$submenu[ $key ][10][2] = add_query_arg( 'wpb-backend-editor', '', $menu_item[10][2] );
			}
		}
	}
}

/**
 * Default template for post types manager
 *
 * Class Vc_Setting_Post_Type_Default_Template_Field
 *
 * @since 4.12
 */
class Vc_Setting_Post_Type_Default_Template_Field {
	/**
	 * Tab name
	 *
	 * @var string
	 */
	protected $tab;

	/**
	 * Field key
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Post types
	 *
	 * @var bool|array
	 */
	protected $post_types = false;

	/**
	 * Vc_Setting_Post_Type_Default_Template_Field constructor.
	 *
	 * @param string $tab
	 * @param string $key
	 */
	public function __construct( $tab, $key ) {
		$this->tab = $tab;
		$this->key = $key;
		add_action( 'vc_settings_tab-general', [
			$this,
			'addField',
		] );
	}

	/**
	 * Get field name
	 *
	 * @return string
	 */
	protected function getFieldName() {
		return esc_html__( 'Default template for post types', 'js_composer' );
	}

	/**
	 * Get field key
	 *
	 * @return string
	 */
	public function getFieldKey() {
		require_once vc_path_dir( 'SETTINGS_DIR', 'class-vc-settings.php' );

		return Vc_Settings::getFieldPrefix() . $this->key;
	}

	/**
	 * Check if post type is valid
	 *
	 * @param string $type
	 * @return bool
	 */
	protected function isValidPostType( $type ) {
		return post_type_exists( $type );
	}

	/**
	 * Get post types.
	 *
	 * @return array|bool
	 */
	protected function getPostTypes() {
		if ( false === $this->post_types ) {
			require_once vc_path_dir( 'SETTINGS_DIR', 'class-vc-roles.php' );
			$vc_roles = new Vc_Roles();
			$this->post_types = $vc_roles->getPostTypes();
		}

		return $this->post_types;
	}

	/**
	 * Get templates.
	 *
	 * @return array
	 */
	protected function getTemplates() {
		return $this->getTemplatesEditor()->getAllTemplates();
	}

	/**
	 * Get templates editor.
	 *
	 * @return bool|\Vc_Templates_Panel_Editor
	 */
	protected function getTemplatesEditor() {
		return wpbakery()->templatesPanelEditor();
	}

	/**
	 * Get settings data for default templates
	 *
	 * @return array|mixed
	 */
	protected function get() {
		require_once vc_path_dir( 'SETTINGS_DIR', 'class-vc-settings.php' );

		$value = Vc_Settings::get( $this->key );

		return $value ? $value : [];
	}

	/**
	 * Get template's shortcodes string
	 *
	 * @param array $template_data
	 * @return string|null
	 */
	protected function getTemplate( $template_data ) {
		$template = null;
		$template_settings = preg_split( '/\:\:/', $template_data );

		$template_id = $template_settings[1];
		$template_type = $template_settings[0];

		if ( ! isset( $template_id, $template_type ) || '' === $template_id || '' === $template_type ) {
			return $template;
		}
		WPBMap::addAllMappedShortcodes();
		if ( 'my_templates' === $template_type ) {
			$saved_templates = get_option( $this->getTemplatesEditor()->getOptionName() );
			if ( ! isset( $saved_templates[ $template_id ] ) ) {
				return $template;
			}
			$content = trim( $saved_templates[ $template_id ]['template'] );
			$content = str_replace( '\"', '"', $content );
			$pattern = get_shortcode_regex();
			$template = preg_replace_callback( "/{$pattern}/s", 'vc_convert_shortcode', $content );
		} else {
			if ( 'default_templates' === $template_type ) {
				$template_data = $this->getTemplatesEditor()->getDefaultTemplate( (int) $template_id );
				if ( isset( $template_data['content'] ) ) {
					$template = $template_data['content'];
				}
			} else {
				$template_preview = apply_filters( 'vc_templates_render_backend_template_preview', $template_id, $template_type );
				if ( (string) $template_preview !== (string) $template_id ) {
					$template = $template_preview;
				}
			}
		}

		return $template;
	}

	/**
	 * Get template by post type.
	 *
	 * @param string $type
	 * @return string|null
	 */
	public function getTemplateByPostType( $type ) {
		$value = $this->get();

		return isset( $value[ $type ] ) ? $this->getTemplate( $value[ $type ] ) : null;
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $settings
	 * @return mixed
	 */
	public function sanitize( $settings ) {
		foreach ( $settings as $type => $template ) {
			if ( empty( $template ) ) {
				unset( $settings[ $type ] );
			} elseif ( ! $this->isValidPostType( $type ) || ! $this->getTemplate( $template ) ) {
				add_settings_error( $this->getFieldKey(), 1, esc_html__( 'Invalid template or post type.', 'js_composer' ), 'error' );

				return $settings;
			}
		}

		return $settings;
	}

	/**
	 * Include template for default post type.
	 */
	public function render() {
		vc_include_template( 'pages/vc-settings/default-template-post-type.tpl.php', [
			'post_types' => $this->getPostTypes(),
			'templates' => $this->getTemplates(),
			'title' => $this->getFieldName(),
			'value' => $this->get(),
			'field_key' => $this->getFieldKey(),
		] );
	}

	/**
	 * Add field settings page
	 *
	 * Method called by vc hook vc_settings_tab-general.
	 */
	public function addField() {
		vc_settings()->addField( $this->tab, $this->getFieldName(), $this->key, [
			$this,
			'sanitize',
		], [
			$this,
			'render',
		] );
	}
}

/**
 * Start only for admin part with hooks
 */
if ( is_admin() ) {
	/**
	 * Initialize Vc_Setting_Post_Type_Default_Template_Field
	 * Called by admin_init hook
	 */
	function vc_settings_post_type_default_template_field_init() {
		new Vc_Setting_Post_Type_Default_Template_Field( 'general', 'default_template_post_type' );
	}

	add_filter( 'default_content', 'vc_set_default_content_for_post_type', 100, 2 );
	add_action( 'admin_init', 'vc_settings_post_type_default_template_field_init', 8 );
	add_filter( 'admin_url', 'vc_add_backend_editor_param_to_button_link', 10, 2 );
	add_action( 'admin_init', 'vc_redirect_new_post_page_to_back_editor' );
	add_action( 'admin_menu', 'vc_add_backend_editor_param_add_post_menu_links', 10, 2 );
}
