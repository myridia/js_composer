<?php
/**
 * Controller for Google Fonts typography synchronization.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Google_Fonts_Controller
 *
 * @since 8.0
 */
class Vc_Google_Fonts_Controller extends Vc_Fonts_Controller {

	/**
	 * Constructor.
	 *
	 * @param Vc_Typography_Module $module
	 * @since 8.0
	 */
	public function __construct( $module ) {
		parent::__construct( $module );
		$this->vendor_slug = 'google';
		$this->api_url = 'https://support.wpbakery.com/api/external/google';
		$this->font_data_option_name = vc_settings()::$field_prefix . $module->settings::GOOGLE_FONTS_DATA_OPTION;
	}

	/**
	 * Init hooks and actions.
	 *
	 * @since 8.0
	 */
	public function init() {
		add_action( 'wp_ajax_wpb_google_fonts', [
			$this,
			'set_fonts',
		] );
		add_filter( 'vc_google_fonts_get_fonts_filter', [
			$this,
			'add_fonts',
		], 9 );
	}

	/**
	 * Get request parameters for API call.
	 *
	 * @return array
	 * @since  8.0
	 */
	protected function get_request_params() {
		return [
			'license_key' => vc_license()->getLicenseKey(),
		];
	}

	/**
	 * Get API endpoint for Google Fonts.
	 *
	 * @return string
	 * @since  8.0
	 */
	protected function get_api_endpoint() {
		return 'get-fonts';
	}

	/**
	 * Add synchronized Google fonts to the font list.
	 *
	 * @param array $font_list
	 *
	 * @return array
	 * @since  8.0
	 */
	public function add_fonts( $font_list ) {
		$google_fonts = $this->get_fonts_data();

		if ( ! $google_fonts ) {
			return $font_list;
		}

		$google_list = json_decode( $google_fonts );

		return $google_list->data;
	}
}
