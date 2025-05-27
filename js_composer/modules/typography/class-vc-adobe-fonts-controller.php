<?php
/**
 * Controller for Adobe typography synchronization.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Adobe_Fonts_Controller
 *
 * @since 8.0
 */
class Vc_Adobe_Fonts_Controller extends Vc_Fonts_Controller {

	/**
	 * Enqueue slug for Adobe fonts.
	 *
	 * @since 8.0
	 *
	 * @var string
	 */
	const ADOBE_ENQUEUE_SLUG = 'wpb_adobe_fonts';

	/**
	 * Adobe enqueue host URL.
	 *
	 * @since 8.0
	 *
	 * @var string
	 */
	protected $adobe_enqueue_host = 'https://use.typekit.net';

	/**
	 * Option name for storing web project ID.
	 *
	 * @since 8.0
	 *
	 * @var string
	 */
	protected $web_project_id_option_name;

	/**
	 * Option value for storing web project ID.
	 *
	 * @since 8.0
	 *
	 * @var string
	 */
	protected $web_project_id;

	/**
	 * Constructor.
	 *
	 * @param Vc_Typography_Module $module
	 * @since 8.0
	 */
	public function __construct( $module ) {
		parent::__construct( $module );
		$this->vendor_slug = 'adobe';
		$this->api_url = 'https://support.wpbakery.com/api/external/adobe';
		$this->font_data_option_name = vc_settings()::$field_prefix . $module->settings::ADOBE_FONT_DATA_OPTION;
		$this->web_project_id_option_name = vc_settings()::$field_prefix . $module->settings::ADOBE_FONT_WEB_PROJECT_ID_OPTION;
	}

	/**
	 * Init hooks and actions.
	 *
	 * @since 8.0
	 */
	public function init() {
		add_action( 'wp_ajax_wpb_adobe_set_fonts', [
			$this,
			'set_fonts',
		] );
		add_filter( 'vc_google_fonts_get_fonts_filter', [
			$this,
			'add_fonts',
		] );

		add_action( 'wpb_after_register_backend_editor_css', [
			$this,
			'register_fonts',
		] );
		add_action( 'wpb_after_register_frontend_editor_css', [
			$this,
			'register_fonts',
		] );

		add_filter( 'vc_enqueue_frontend_editor_css', [
			$this,
			'enqueue_fonts',
		] );
		add_filter( 'vc_enqueue_backend_editor_css', [
			$this,
			'enqueue_fonts',
		] );

		add_action( 'wpb_after_enqueue_element_google_fonts', [
			$this,
			'enqueue_fonts_directly',
		] );
	}

	/**
	 * Get request parameters for API call.
	 *
	 * @return array
	 * @since 8.0
	 */
	protected function get_request_params() {
		$web_project_id = vc_request_param( 'web_project_id' );
		if ( empty( $web_project_id ) ) {
			wp_send_json_error( __( 'Please enter the Adobe Web Project ID before activating synchronization.', 'js_composer' ) );
		}
		$this->web_project_id = $web_project_id;

		return [
			'license_key' => vc_license()->getLicenseKey(),
			'kit_id' => $web_project_id,
		];
	}

	/**
	 * Get API endpoint for Adobe.
	 *
	 * @return string
	 * @since 8.0
	 */
	protected function get_api_endpoint() {
		return 'get-kit';
	}

	/**
	 * Get Adobe web project ID.
	 *
	 * @return string
	 * @since 8.0
	 */
	protected function get_web_project_id() {
		return get_option( $this->web_project_id_option_name, '' );
	}

	/**
	 * Add synchronized Adobe fonts to the font list.
	 *
	 * @param array $font_list
	 *
	 * @return array
	 * @since 8.0
	 */
	public function add_fonts( $font_list ) {
		$adobe_web_project_data = $this->get_fonts_data();
		$web_project_id = $this->get_web_project_id();

		if ( ! $adobe_web_project_data || ! $web_project_id ) {
			return $font_list;
		}

		$adobe_list = json_decode( $adobe_web_project_data );
		$adobe_list = $this->add_web_project_url_to_font_list( $adobe_list, $web_project_id );

		if ( is_array( $adobe_list ) && count( $adobe_list ) ) {
			$adobe_list = $this->add_adobe_dropdown_separator( $adobe_list );
			$font_list = $this->add_default_dropdown_separator( $font_list );
		}

		return array_merge( $adobe_list, $font_list );
	}

	/**
	 * Add web project font URL to font list.
	 *
	 * @param array $adobe_list
	 * @param string $web_project_id
	 *
	 * @return array
	 * @since 8.0
	 */
	protected function add_web_project_url_to_font_list( $adobe_list, $web_project_id ) {
		$web_project_url = $this->get_web_project_style_url( $web_project_id );

		if ( empty( $adobe_list->data ) ) {
			return $adobe_list;
		}

		foreach ( $adobe_list->data as $font ) {
			$font->font_url = $web_project_url;
		}

		return $adobe_list->data;
	}

	/**
	 * Add dropdown separator for Adobe fonts.
	 *
	 * @param array $font_list
	 *
	 * @return array
	 * @since 8.0
	 */
	protected function add_adobe_dropdown_separator( $font_list ) {
		$separator = (object) [
			'font_family' => $this->get_adobe_dropdown_separator(),
			'font_styles' => '',
			'font_types' => '',
		];

		return array_merge( [ $separator ], $font_list );
	}

	/**
	 * Add dropdown separator for default fonts.
	 *
	 * @param array $font_list
	 *
	 * @return array
	 * @since 8.0
	 */
	protected function add_default_dropdown_separator( $font_list ) {
		$separator = (object) [
			'font_family' => $this->module->get_default_dropdown_separator(),
			'font_styles' => '',
			'font_types' => '',
		];

		return array_merge( [ $separator ], $font_list );
	}

	/**
	 * Get dropdown separator for Adobe fonts.
	 *
	 * @return string
	 * @since 8.0
	 */
	public function get_adobe_dropdown_separator() {
		return __( '----------------- Adobe Fonts ---------------------', 'js_composer' );
	}

	/**
	 * Register Adobe fonts to backend and frontend editors.
	 *
	 * @since 8.0
	 */
	public function register_fonts() {
		$web_project_id = $this->get_web_project_id();
		if ( empty( $web_project_id ) ) {
			return;
		}

		$styles_url = $this->get_web_project_style_url( $web_project_id );

		wp_register_style( self::ADOBE_ENQUEUE_SLUG, $styles_url, [], WPB_VC_VERSION );
	}

	/**
	 * Enqueue Adobe fonts to backend and frontend editors.
	 *
	 * @param array $enqueue_list
	 *
	 * @return array
	 * @since 8.0
	 */
	public function enqueue_fonts( $enqueue_list ) {
		$web_project_id = $this->get_web_project_id();
		if ( ! $web_project_id ) {
			return $enqueue_list;
		}

		$enqueue_list[] = self::ADOBE_ENQUEUE_SLUG;

		return $enqueue_list;
	}

	/**
	 * Enqueue Adobe web project fonts directly to element template.
	 *
	 * @param array $fonts_data
	 * @since 8.0
	 */
	public function enqueue_fonts_directly( $fonts_data ) {
		if ( empty( $fonts_data['values']['font_vendor'] ) || $fonts_data['values']['font_vendor'] !== $this->vendor_slug ) {
			return;
		}

		$web_project_id = $this->get_web_project_id();
		if ( ! $web_project_id ) {
			return;
		}

		$styles_url = $this->get_web_project_style_url( $web_project_id );

		wp_enqueue_style( self::ADOBE_ENQUEUE_SLUG, $styles_url, [], WPB_VC_VERSION );
	}

	/**
	 * Get Adobe web project style URL.
	 *
	 * @param string $web_project_id
	 *
	 * @return string
	 * @since 8.0
	 */
	protected function get_web_project_style_url( $web_project_id ) {
		return $this->adobe_enqueue_host . '/' . $web_project_id . '.css';
	}

	/**
	 * Set font data to options.
	 *
	 * @param string $data
	 * @since 8.0
	 */
	protected function set_fonts_data( $data ) {
		update_option( $this->web_project_id_option_name, $this->web_project_id );
		update_option( $this->font_data_option_name, $data );
	}
}
