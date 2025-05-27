<?php
/**
 * Interface lib
 *
 * @deprecated
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Interface for editors
 *
 * @since 4.3
 * @deprecated since 5.8
 */
interface Vc_Editor_Interface {
	/**
	 * Render editor.
	 *
	 * @return mixed
	 * @deprecated 5.8
	 * @since 4.3
	 */
	public function renderEditor();
}

/**
 * Default render interface
 *
 * @since 4.3
 * @deprecated 5.8
 */
interface Vc_Render {
	/**
	 * Render.
	 *
	 * @return mixed
	 * @deprecated 5.8
	 * @since 4.3
	 */
	public function render();
}

/**
 * Interface for third-party plugins classes loader.
 *
 * @since 4.3
 * @deprecated 5.8
 */
interface Vc_Vendor_Interface {
	/**
	 * Load.
	 *
	 * @return mixed
	 * @deprecated 5.8
	 * @since 4.3
	 */
	public function load();
}
