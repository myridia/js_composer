<?php
/**
 * Module Name: Custom CSS
 * Description: Allow implement custom CSS code to the whole site and individual pages.
 *
 * @since 7.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_manager()->path( 'MUTUAL_MODULES_DIR', 'class-module.php' );
require_once vc_manager()->path( 'MODULES_DIR', 'custom-css/class-vc-custom-css-module-settings.php' );

/**
 * Module entry point.
 *
 * @since 7.7
 */
class Vc_Custom_Css_Module extends Vc_Module {

	/**
	 * Module settings that specify functionality common for some modules.
	 *
	 * @note We can use object functionality $this->get_module_functionality( 'post-meta' )->foo();
	 *
	 * @since 8.4
	 * @var array
	 */
	public $module_common_functionality = [ 'post-meta' ];

	/**
	 * Post meta key.
	 *
	 * @since 8.4
	 * @var string
	 */
	public $post_meta_key = 'post_custom_css';

	/**
	 * Post meta slug.
	 *
	 * @since 8.4
	 * @var string
	 */
	public $post_meta_slug = 'custom_css';

	/**
	 * Settings object.
	 *
	 * @var Vc_Custom_Css_Module_Settings
	 */
	public $settings;

	/**
	 * Module meta key.
	 *
	 * @since 7.7
	 * @var string
	 */
	const CUSTOM_CSS_META_KEY = '_wpb_post_custom_css';

	/**
	 * Vc_Custom_Css_Module constructor.
	 *
	 * @since 8.0
	 */
	public function __construct() {
		// We initialize the common modules functionality in parent constructor.
		parent::__construct();
		$this->settings = new Vc_Custom_Css_Module_Settings();
		$this->settings->init();
	}

	/**
	 * Init module implementation.
	 *
	 * @since 7.7
	 */
	public function init() {
		add_action( 'vc_build_page', [ $this, 'add_custom_css_to_page' ] );

		add_action( 'vc_base_register_front_css', [ $this, 'register_global_custom_css' ] );

		add_action( 'vc_load_iframe_jscss', [ $this, 'enqueue_global_custom_css_to_page' ] );

		add_action('vc_base_register_front_css', function () {
			add_action( 'wp_enqueue_scripts', [
				$this,
				'enqueue_global_custom_css_to_page',
			] );
		});

		add_action( 'update_option_wpb_js_custom_css', [
			$this,
			'build_custom_css',
		] );

		add_action( 'add_option_wpb_js_custom_css', [
			$this,
			'build_custom_css',
		] );

		add_filter( 'wpb_enqueue_backend_editor_js', [
			$this,
			'enqueue_editor_js',
		]);

		add_filter( 'vc_enqueue_frontend_editor_js', [
			$this,
			'enqueue_editor_js',
		]);
	}

	/**
	 * Get module post meta.
	 *
	 * @since 8.4
	 * @param array $post_custom_meta
	 * @param int $post_id
	 * @return mixed
	 */
	public function get_module_meta( $post_custom_meta, $post_id ) {
		return get_post_meta( $post_id, self::CUSTOM_CSS_META_KEY, true );
	}

	/**
	 * Add custom css to page.
	 *
	 * @since 7.7
	 */
	public function add_custom_css_to_page() {
		add_action( 'wp_head', [ $this, 'output_custom_css_to_page' ] );
	}

	/**
	 * Hooked class method by wp_head WP action to output post custom css.
	 *
	 * Method gets post meta value for page by key '_wpb_post_custom_css' and
	 * outputs css string wrapped into style tag.
	 *
	 * @param int|null $id
	 * @since  7.7
	 */
	public function output_custom_css_to_page( $id = null ) {
		$id = $id ?: wpb_get_post_id_for_custom_output();

		if ( ! $id ) {
			return;
		}

		$id = wpb_update_id_with_preview_id( $id );

		$post_custom_css = get_metadata( 'post', $id, self::CUSTOM_CSS_META_KEY, true );
		$post_custom_css = apply_filters( 'vc_post_custom_css', $post_custom_css, $id );
		if ( ! empty( $post_custom_css ) ) {
			$post_custom_css = wp_strip_all_tags( $post_custom_css );
			echo '<style data-type="vc_custom-css">';
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $post_custom_css;
			echo '</style>';
		}
	}

	/**
	 * Register global custom css.
	 *
	 * @since 7.7
	 */
	public function register_global_custom_css() {
		$upload_dir = wp_upload_dir();
		$vc_upload_dir = vc_upload_dir();

		$custom_css_path = $upload_dir['basedir'] . '/' . $vc_upload_dir . '/custom.css';
		if ( is_file( $upload_dir['basedir'] . '/' . $vc_upload_dir . '/custom.css' ) && filesize( $custom_css_path ) > 0 ) {
			$custom_css_url = $upload_dir['baseurl'] . '/' . $vc_upload_dir . '/custom.css';
			$custom_css_url = vc_str_remove_protocol( $custom_css_url );
			wp_register_style( 'js_composer_custom_css', $custom_css_url, [], WPB_VC_VERSION );
		}
	}

	/**
	 * Enqueue global custom css to page.
	 *
	 * @since 7.7
	 */
	public function enqueue_global_custom_css_to_page() {
		wp_enqueue_style( 'js_composer_custom_css' );
	}

	/**
	 * Builds custom css file using css options from vc settings.
	 *
	 * @return bool
	 */
	public function build_custom_css() {
		/**
		 * Filesystem API init.
		 * */
		$url = wp_nonce_url( 'admin.php?page=vc-color&build_css=1', 'wpb_js_settings_save_action' );
		vc_settings()::getFileSystem( $url );

		/**
		 * Filesystem API object.
		 *
		 * @var WP_Filesystem_Direct $wp_filesystem
		 */
		global $wp_filesystem;

		/**
		 * Building css file.
		 */
		$js_composer_upload_dir = vc_settings()::checkCreateUploadDir( $wp_filesystem, 'custom_css', 'custom.css' );
		if ( ! $js_composer_upload_dir ) {
			return true;
		}

		$filename = $js_composer_upload_dir . '/custom.css';
		$css_string = '';
		$custom_css_string = get_option( vc_settings()::$field_prefix . 'custom_css' );
		if ( ! empty( $custom_css_string ) ) {
			$assets_url = vc_asset_url( '' );
			$css_string .= preg_replace( '/(url\(\.\.\/(?!\.))/', 'url(' . $assets_url, $custom_css_string );
			$css_string = wp_strip_all_tags( $css_string );
		}

		if ( ! $wp_filesystem->put_contents( $filename, $css_string, FS_CHMOD_FILE ) ) {
			if ( is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
				add_settings_error( vc_settings()::$field_prefix . 'custom_css', $wp_filesystem->errors->get_error_code(), esc_html__( 'Something went wrong: custom.css could not be created.', 'js_composer' ) . $wp_filesystem->errors->get_error_message() );
			} elseif ( ! $wp_filesystem->connect() ) {
				add_settings_error( vc_settings()::$field_prefix . 'custom_css', $wp_filesystem->errors->get_error_code(), esc_html__( 'custom.css could not be created. Connection error.', 'js_composer' ) );
			} elseif ( ! $wp_filesystem->is_writable( $filename ) ) {
				add_settings_error( vc_settings()::$field_prefix . 'custom_css', $wp_filesystem->errors->get_error_code(), sprintf( esc_html__( 'custom.css could not be created. Cannot write custom css to %s.', 'js_composer' ), $filename ) );
			} else {
				add_settings_error( vc_settings()::$field_prefix . 'custom_css', $wp_filesystem->errors->get_error_code(), esc_html__( 'custom.css could not be created. Problem with access.', 'js_composer' ) );
			}

			return false;
		}

		return true;
	}

	/**
	 * Load module JS in frontend and backend editor.
	 *
	 * @since 7.8
	 * @param array $dependencies
	 * @return array
	 */
	public function enqueue_editor_js( $dependencies ) {
		$dependencies[] = 'ace-editor';
		$dependencies[] = 'wpb-code-editor';

		return $dependencies;
	}
}
