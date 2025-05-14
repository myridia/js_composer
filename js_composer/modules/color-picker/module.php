<?php
/**
 * Module Name: Color Picker Settings
 * Description: Add users optionality to define settings for color picker.
 *
 * @since 7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_manager()->path( 'MODULES_DIR', 'color-picker/class-vc-color-picker-module-settings.php' );

/**
 * Module entry point.
 *
 * @since 7.9
 */
class Vc_Color_Picker_Module {

	/**
	 * Settings object.
	 *
	 * @since 7.9
	 * @var Vc_Color_Picker_Module_Settings
	 */
	public $settings;

	/**
	 * Vc_Color_Picker_Module constructor.
	 *
	 * @since 8.0
	 */
	public function __construct() {
		$this->settings = new Vc_Color_Picker_Module_Settings();
		$this->settings->init();
	}

	/**
	 * Init module implementation.
	 *
	 * @since 7.9
	 */
	public function init() {}
}
