<?php
/**
 * Module Name: Typography
 * Description: Plugin module for Typography management.
 *
 * Module helps users to extend initial plugin typography.
 * Adding new font families and variants.
 * That they can get from third-party services like Google Fonts Adobe Kit etc.
 *
 * @since 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_manager()->path( 'MODULES_DIR', 'typography/class-vc-typography-module-settings.php' );
require_once vc_manager()->path( 'MODULES_DIR', 'typography/class-vc-fonts-controller.php' );
require_once vc_manager()->path( 'MODULES_DIR', 'typography/class-vc-adobe-fonts-controller.php' );
require_once vc_manager()->path( 'MODULES_DIR', 'typography/class-vc-google-fonts-controller.php' );

/**
 * Module entry point.
 *
 * @since 8.0
 */
class Vc_Typography_Module {
	/**
	 * Settings object.
	 *
	 * @since 8.0
	 * @var Vc_Typography_Module_Settings
	 */
	public $settings;

	/**
	 * Settings object for Adobe Web Project.
	 *
	 * @since 8.0
	 * @var Vc_Adobe_Fonts_Controller
	 */
	public $adobe_controller;

	/**
	 * Settings object for Google Fonts.
	 *
	 * @since 8.0
	 * @var \Vc_Google_Fonts_Controller
	 */
	public $google_fonts_controller;

	/**
	 * Get dropdown separator for default fonts.
	 *
	 * @since 8.0
	 * @return string
	 */
	public function get_default_dropdown_separator() {
		return __( '----------------- Google Fonts ---------------------', 'js_composer' );
	}

	/**
	 * Vc_Typography_Module constructor.
	 *
	 * @since 8.0
	 */
	public function __construct() {
		$this->settings = new Vc_Typography_Module_Settings();
		$this->settings->init();

		$this->adobe_controller = new Vc_Adobe_Fonts_Controller( $this );
		$this->adobe_controller->init();

		$this->google_fonts_controller = new Vc_Google_Fonts_Controller( $this );
		$this->google_fonts_controller->init();
	}

	/**
	 * Init module implementation.
	 *
	 * @since 8.0
	 */
	public function init() {}
}
