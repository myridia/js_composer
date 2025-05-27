<?php
/**
 * Abstract class for modules.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Abstract class for modules that help provide common functionality for modules.
 *
 * @note it is not obligatory to use this class for every module.
 * We use it only for our modules that need to have some common functionality,
 * but we don't keep here specific module realization.
 *
 * @since 8.4
 */
abstract class Vc_Module {
	/**
	 * Module currently processed functionality.
	 *
	 * @since 8.4
	 * @var object
	 */
	public $processed_functionality;

	/**
	 * Module functionality list contains all functionality objects.
	 *
	 * @since 8.4
	 * @var array
	 */
	public $functionality_list = [];

	/**
	 * Vc_Module constructor.
	 *
	 * @since 8.4
	 */
	public function __construct() {
		$this->init_common_functionality();
	}

	/**
	 * Magic method that help override functionality methods in module.
	 *
	 * @since 8.4
	 *
	 * @param string $name
	 * @param array $arguments
	 */
	public function __call( $name, $arguments ) {
		return $this->processed_functionality->{$name}( ...$arguments );
	}

	/**
	 * Get module functionality object by name.
	 *
	 * @since 8.4
	 *
	 * @param string $functionality_name
	 *
	 * @return object
	 */
	public function get_module_functionality( $functionality_name ) {
		return $this->functionality_list[ $functionality_name ];
	}

	/**
	 * Init common functionality for some modules
	 *
	 * @since 8.4
	 */
	public function init_common_functionality() {
		if ( empty( $this->module_common_functionality ) || ! is_array( $this->module_common_functionality ) ) {
			return;
		}

		foreach ( $this->module_common_functionality as $functionality_name ) {
			if ( ! is_string( $functionality_name ) ) {
				continue;
			}

			// check if setting class file exist.
			$functionality_class_file = vc_manager()->path( 'MUTUAL_MODULES_DIR', 'functionality/class-module-' . $functionality_name . '.php' );
			if ( ! file_exists( $functionality_class_file ) ) {
				continue;
			}

			require_once $functionality_class_file;
			$functionality_class_name = 'Vc_Module_' . str_replace( '-', '_', ucwords( $functionality_name, '-' ) );
			if ( class_exists( $functionality_class_name ) ) {
				$this->processed_functionality = new $functionality_class_name( $this );
				$this->functionality_list[ $functionality_name ] = $this->processed_functionality;
			}
		}
	}
}
