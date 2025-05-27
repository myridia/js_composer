<?php
/**
 * Plugin Name: WPBakery Page Builder
 * Plugin URI: https://wpbakery.com
 * Description: Drag and drop page builder for WordPress. Take full control over your WordPress site, build any layout you can imagine – no programming knowledge required.
 * Version: 8.4.1
 * Author: WPBakery
 * Author URI: https://wpbakery.com/?utm_source=wpdashboard&utm_medium=wp-plugins&utm_campaign=info&utm_content=text
 * Text Domain: js_composer
 * Domain Path: /locale/
 * Requires at least: 4.9
 *
 * @package WPBakery Page Builder
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Current WPBakery Page Builder version
 */
if ( ! defined( 'WPB_VC_VERSION' ) ) {
	define( 'WPB_VC_VERSION', '8.4.1' );
}

define( 'WPB_PLUGIN_DIR', __DIR__ );
define( 'WPB_PLUGIN_FILE', __FILE__ );

require_once __DIR__ . '/include/classes/core/class-vc-manager.php';
/**
 * Main WPBakery Page Builder manager.
 *
 * @var Vc_Manager $vc_manager - instance of composer management.
 * @since 4.2
 */
global $vc_manager;
if ( ! $vc_manager ) {
	$vc_manager = Vc_Manager::getInstance();
	// Load components.
	$vc_manager->loadComponents();
}
