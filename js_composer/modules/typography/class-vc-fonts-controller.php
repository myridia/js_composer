<?php
/**
 * Abstract Controller for font synchronization.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Fonts_Controller
 *
 * @since 8.0
 */
abstract class Vc_Fonts_Controller {

	/**
	 * API URL for the font provider.
	 *
	 * @since 8.0
	 * @var string
	 */
	protected $api_url;

	/**
	 * Vendor slug (e.g., 'google', 'adobe').
	 *
	 * @since 8.0
	 *
	 * @var string
	 */
	protected $vendor_slug;

	/**
	 * Module instance.
	 *
	 * @since 8.0
	 *
	 * @var Vc_Typography_Module
	 */
	protected $module;

	/**
	 * Option name for storing font data.
	 *
	 * @since 8.0
	 *
	 * @var string
	 */
	protected $font_data_option_name;

	/**
	 * Constructor.
	 *
	 * @param Vc_Typography_Module $module
	 * @since 8.0
	 */
	public function __construct( $module ) {
		$this->module = $module;
	}

	/**
	 * Initialize hooks and actions.
	 *
	 * @since 8.0
	 */
	abstract public function init();

	/**
	 * Ajax handler to set fonts.
	 *
	 * @since 8.0
	 */
	public function set_fonts() {
		vc_user_access()->checkAdminNonce()->validateDie()->part( 'settings' )->can( 'vc-typography-tab' )->validateDie();

		if ( ! $this->is_license_valid() ) {
			wp_send_json_error( __( 'Sync process is only available for users with an active license key.', 'js_composer' ) );
		}

		$request_params = $this->get_request_params();

		$fonts = $this->get_fonts_from_api( $request_params );

		if ( is_wp_error( $fonts ) ) {
			wp_send_json_error( $fonts->get_error_message() );
		}

		$this->set_fonts_data( wp_remote_retrieve_body( $fonts ) );

		wp_send_json_success( $fonts );
	}

	/**
	 * Check if the license is valid.
	 *
	 * @return bool
	 * @since 8.0
	 */
	protected function is_license_valid() {
		$license_key = vc_license()->getLicenseKey();

		return ! empty( $license_key );
	}

	/**
	 * Get fonts from API.
	 *
	 * @param array $request_params
	 *
	 * @return array|WP_Error
	 * @since 8.0
	 */
	protected function get_fonts_from_api( $request_params ) {
		$endpoint = $this->get_api_endpoint();

		$response = $this->get_api_response( $endpoint, $request_params );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			$body = json_decode( wp_remote_retrieve_body( $response ) );
			if ( empty( $body ) ) {
				return new WP_Error( $this->vendor_slug . '_api_error', sprintf( __( 'Error occurred during request to %s API. Please try again later.', 'js_composer' ), ucfirst( $this->vendor_slug ) ) );
			} else {
				return new WP_Error( $this->vendor_slug . '_api_error', $body->message );
			}
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );
		if ( isset( $body->status ) && false === $body->status ) {
			return new WP_Error( $this->vendor_slug . '_api_error', $body->data->message );
		}

		return $response;
	}

	/**
	 * Get API response.
	 *
	 * @param string $endpoint
	 * @param array $request_params
	 *
	 * @return array|WP_Error
	 * @since 8.0
	 */
	protected function get_api_response( $endpoint, $request_params ) {
		$request_args = [
			'body' => wp_json_encode( $request_params ),
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			],
		];

		$url = $this->api_url . '/' . $endpoint;

		return wp_remote_post( $url, $request_args );
	}

	/**
	 * Get request parameters for API call.
	 *
	 * @return array
	 * @since 8.0
	 */
	abstract protected function get_request_params();

	/**
	 * Get API endpoint for the font provider.
	 *
	 * @return string
	 * @since 8.0
	 */
	abstract protected function get_api_endpoint();

	/**
	 * Get font data from options.
	 *
	 * @return string
	 * @since 8.0
	 */
	protected function get_fonts_data() {
		return get_option( $this->font_data_option_name, '' );
	}

	/**
	 * Set font data to options.
	 *
	 * @param string $data
	 * @since 8.0
	 */
	protected function set_fonts_data( $data ) {
		update_option( $this->font_data_option_name, $data );
	}
}
