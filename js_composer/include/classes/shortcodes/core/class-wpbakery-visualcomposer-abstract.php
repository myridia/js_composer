<?php
/**
 * Abstract deprecated class for creating structural objects.
 *
 * This file contains an abstract class that was used for creating and managing
 * structural objects in the WPBakery environment. The class includes deprecated
 * methods for adding and removing actions, filters, shortcodes, and handling
 * asset URLs and paths. These methods are no longer recommended for use.
 *
 * @depreacted
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Abstract deprecated class to create structural object of any type.
 *
 * @deprecated
 */
abstract class WPBakeryVisualComposerAbstract {
	/**
	 * Configurations.
	 *
	 * @var array
	 */
	public static $config;
	/**
	 * Controls CSS settings.
	 *
	 * @var string
	 */
	protected $controls_css_settings = 'cc';
	/**
	 * Controls list.
	 *
	 * @var array
	 */
	protected $controls_list = [
		'edit',
		'clone',
		'delete',
	];

	/**
	 * Shortcode content.
	 *
	 * @var string
	 */
	protected $shortcode_content = '';

	/**
	 * WPBakeryVisualComposerAbstract constructor.
	 */
	public function __construct() {
	}

	/**
	 * Initialize the object.
	 *
	 * @param array $settings
	 * @deprecated not used
	 */
	public function init( $settings ) {
		self::$config = (array) $settings;
	}

	/**
	 * Add action.
	 *
	 * @param string $action
	 * @param string $method
	 * @param int $priority
	 * @return true|void
	 * @deprecated 6.0 use native WordPress actions
	 */
	public function addAction( $action, $method, $priority = 10 ) {
		return add_action( $action, [
			$this,
			$method,
		], $priority );
	}

	/**
	 * Remove action.
	 *
	 * @param string $action
	 * @param string $method
	 * @param int $priority
	 *
	 * @return bool
	 * @deprecated 6.0 use native WordPress actions
	 */
	public function removeAction( $action, $method, $priority = 10 ) {
		return remove_action( $action, [
			$this,
			$method,
		], $priority );
	}

	/**
	 * Add filter.
	 *
	 * @param string $filter
	 * @param string $method
	 * @param int $priority
	 *
	 * @return bool|void
	 * @deprecated 6.0 use native WordPress actions
	 */
	public function addFilter( $filter, $method, $priority = 10 ) {
		return add_filter( $filter, [
			$this,
			$method,
		], $priority );
	}

	/**
	 * Remove filter.
	 *
	 * @param string $filter
	 * @param string $method
	 * @param int $priority
	 * @return bool
	 * @deprecated 6.0 use native WordPress
	 */
	public function removeFilter( $filter, $method, $priority = 10 ) {
		return remove_filter( $filter, [
			$this,
			$method,
		], $priority );
	}

	/**
	 * Add shortcode.
	 *
	 * @param string $tag
	 * @param string $func
	 * @deprecated 6.0 not used
	 */
	public function addShortCode( $tag, $func ) {
		// this function is deprecated since 6.0.
	}

	/**
	 * Do shortcode.
	 *
	 * @param string $content
	 * @deprecated 6.0 not used.
	 */
	public function doShortCode( $content ) {
		// this function is deprecated since 6.0.
	}

	/**
	 * Remove shortcode.
	 *
	 * @param string $tag
	 * @deprecated 6.0 not used
	 */
	public function removeShortCode( $tag ) {
		// this function is deprecated since 6.0.
	}

	/**
	 * Post param.
	 *
	 * @param string $param
	 *
	 * @return null
	 * @deprecated 6.0 not used, use vc_post_param
	 */
	public function post( $param ) {
		// this function is deprecated since 6.0.

		return vc_post_param( $param );
	}

	/**
	 * Get param.
	 *
	 * @param string $param
	 *
	 * @return null
	 * @deprecated 6.0 not used, use vc_get_param
	 */
	public function get( $param ) {
		// this function is deprecated since 6.0.

		return vc_get_param( $param );
	}

	/**
	 * Get assets URL.
	 *
	 * @param string $asset
	 *
	 * @return string
	 * @deprecated 4.5 use vc_asset_url
	 */
	public function assetURL( $asset ) {
		// this function is deprecated since 4.5.

		return vc_asset_url( $asset );
	}

	/**
	 * Get assets path.
	 *
	 * @param string $asset
	 *
	 * @return string
	 * @deprecated 6.0 not used
	 */
	public function assetPath( $asset ) {
		// this function is deprecated since 6.0.

		return self::$config['APP_ROOT'] . self::$config['ASSETS_DIR'] . $asset;
	}

	/**
	 * Get config.
	 *
	 * @param string $name
	 *
	 * @return null
	 * @deprecated 6.0 not used
	 */
	public static function config( $name ) {
		return isset( self::$config[ $name ] ) ? self::$config[ $name ] : null;
	}
}
