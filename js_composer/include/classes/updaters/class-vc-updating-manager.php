<?php
/**
 * Manage update messages and Plugins info for WPBakery in default WordPress plugins list.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Updating Manager class
 */
class Vc_Updating_Manager {
	/**
	 * The plugin current version
	 *
	 * @var string
	 */
	public $current_version;

	/**
	 * The plugin remote update path
	 *
	 * @var string
	 */
	public $update_path;

	/**
	 * Plugin Slug (plugin_directory/plugin_file.php)
	 *
	 * @var string
	 */
	public $plugin_slug;

	/**
	 * Plugin name (plugin_file)
	 *
	 * @var string
	 */
	public $slug;
	/**
	 * Link to download VC.
	 *
	 * @var string
	 */
	protected $url = 'https://go.wpbakery.com/wpb-buy';

	/**
	 * Initialize a new instance of the WordPress Auto-Update class
	 *
	 * @param string $current_version
	 * @param string $update_path
	 * @param string $plugin_slug
	 */
	public function __construct( $current_version, $update_path, $plugin_slug ) {
		// Set the class public variables.
		$this->current_version = $current_version;
		$this->update_path = $update_path;
		$this->plugin_slug = $plugin_slug;
		$t = explode( '/', $plugin_slug );
		$this->slug = str_replace( '.php', '', $t[1] );

		// define the alternative API for updating checking.
		add_filter( 'pre_set_site_transient_update_plugins', [
			$this,
			'check_update',
		] );

		// Define the alternative response for information checking.
		add_filter( 'plugins_api', [
			$this,
			'check_info',
		], 10, 3 );

		add_action( 'in_plugin_update_message-' . vc_plugin_name(), [
			$this,
			'addUpgradeMessageLink',
		] );
	}

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param object $transient
	 *
	 * @return object
	 */
	public function check_update( $transient ) {
		// Extra check for 3rd plugins.
		if ( isset( $transient->response[ $this->plugin_slug ] ) ) {
			return $transient;
		}
		// Get the remote version.
		$remote_version = $this->getRemote_version();

		// If a newer version is available, add the update.
		if ( version_compare( $this->current_version, $remote_version, '<' ) ) {
			$obj = new stdClass();
			$obj->slug = $this->slug;
			$obj->new_version = $remote_version;
			$obj->plugin = $this->plugin_slug;
			$obj->url = '';
			$obj->package = true;
			$obj->name = 'WPBakery Page Builder';
			$transient->response[ $this->plugin_slug ] = $obj;
		}

		return $transient;
	}

	/**
	 * Add our self-hosted description to the filter
	 *
	 * @param bool $false_value
	 * @param array $action
	 * @param object $arg
	 *
	 * @return bool|object
	 */
	public function check_info( $false_value, $action, $arg ) {
		if ( isset( $arg->slug ) && $arg->slug === $this->slug ) {
			$information = $this->getRemote_information();
			if ( empty( $information->sections ) ) {
				return $false_value;
			}

			$array_pattern = [
				'/^([\*\s])*(\d\d\.\d\d\.\d\d\d\d[^\n]*)/m',
				'/^\n+|^[\t\s]*\n+/m',
				'/\n/',
			];
			$array_replace = [
				'<h4>$2</h4>',
				'</div><div>',
				'</div><div>',
			];
			$information->name = 'WPBakery Page Builder';
			$information->sections = (array) $information->sections;
			$information->sections['changelog'] = '<div>' . preg_replace( $array_pattern, $array_replace, $information->sections['changelog'] ) . '</div>';

			return $information;
		}

		return $false_value;
	}

	/**
	 * Return the remote version
	 *
	 * @return string $remote_version
	 */
	public function getRemote_version() {
		// FIX SSL SNI.
		$filter_add = true;
		if ( function_exists( 'curl_version' ) ) {
			$version = curl_version();
			if ( version_compare( $version['version'], '7.18', '>=' ) ) {
				$filter_add = false;
			}
		}
		if ( $filter_add ) {
			add_filter( 'https_ssl_verify', '__return_false' );
		}
		$index_file = vc_updater()->isBetaEnabled() ? 'index-beta.html' : '';
		$request = wp_remote_get( $this->update_path . $index_file, [ 'timeout' => 30 ] );

		if ( $filter_add ) {
			remove_filter( 'https_ssl_verify', '__return_false' );
		}
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return $request['body'];
		}

		return false;
	}

	/**
	 * Get information about the remote version
	 *
	 * @return bool|object|null
	 */
	public function getRemote_information() {
		// FIX SSL SNI.
		$filter_add = true;
		if ( function_exists( 'curl_version' ) ) {
			$version = curl_version();
			if ( version_compare( $version['version'], '7.18', '>=' ) ) {
				$filter_add = false;
			}
		}
		if ( $filter_add ) {
			add_filter( 'https_ssl_verify', '__return_false' );
		}
		$information_file = vc_updater()->isBetaEnabled() ? 'information-beta.json' : 'information.json';
		$request = wp_remote_get( $this->update_path . $information_file, [ 'timeout' => 30 ] );

		if ( $filter_add ) {
			remove_filter( 'https_ssl_verify', '__return_false' );
		}
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return json_decode( $request['body'] );
		}

		return false;
	}

	/**
	 * Shows message on Wp plugins page with a link for updating from envato.
	 */
	public function addUpgradeMessageLink() {
		$is_activated = vc_license()->isActivated();
		if ( ! $is_activated ) {
			$url = vc_updater()->getUpdaterUrl();

			printf( ' ' . esc_html__( 'To receive automatic updates license activation is required. Please visit %1$ssettings%2$s to activate your WPBakery Page Builder.', 'js_composer' ), '<a href="' . esc_url( $url ) . '" target="_blank">', '</a>' ) . sprintf( ' <a href="https://go.wpbakery.com/faq-update-in-theme" target="_blank">%s</a>', esc_html__( 'Got WPBakery Page Builder in theme?', 'js_composer' ) );
		}

		$is_support_expired = vc_license()->isExpired();
		if ( $is_support_expired ) {
			$url = vc_updater()->getUpdaterUrl();
			printf(
				esc_html__( '%1$s Visit the %2$slicense%3$s section for more information.%4$s', 'js_composer' ),
				'<em>',
				'<a href="' . esc_url( $url ) . '">',
				'</a>',
				'</em>'
			);
		}
	}
}
