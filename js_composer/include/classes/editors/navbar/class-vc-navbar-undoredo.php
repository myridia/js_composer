<?php
/**
 * Undo/Redo Navbar functionality.
 *
 * @package WPBakeryPageBuilder
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Navbar_Undoredo
 */
class Vc_Navbar_Undoredo {

	/**
	 * Vc_Navbar_Undoredo constructor.
	 */
	public function __construct() {
		// Backend.
		add_filter( 'vc_nav_controls', [
			$this,
			'addControls',
		] );

		// Frontend.
		add_filter( 'vc_nav_front_controls', [
			$this,
			'addControls',
		] );
	}

	/**
	 * Add undo/redo controls.
	 *
	 * @param array $controls
	 * @return array
	 */
	public function addControls( $controls ) {
		$controls[] = [
			'undo',
			'<li class="vc_hide-mobile vc_hide-desktop-more">
				<a id="vc_navbar-undo" class="vc_icon-btn vc_undo-redo vc_undo-button vc_hide-mobile" disabled title="' . esc_attr__( 'Undo', 'js_composer' ) . '">
					<i class="vc-composer-icon vc-c-icon-undo"></i>
					<p class="vc_hide-desktop">' . __( 'Undo', 'js_composer' ) . '</p>
				</a>
			</li>',
		];
		$controls[] = [
			'redo',
			'<li class="vc_hide-mobile vc_hide-desktop-more">
				<a id="vc_navbar-redo" class="vc_icon-btn vc_undo-redo vc_redo-button vc_hide-mobile" disabled title="' . esc_attr__( 'Redo', 'js_composer' ) . '">
					<i class="vc-composer-icon vc-c-icon-redo"></i>
					<p class="vc_hide-desktop">' . __( 'Redo', 'js_composer' ) . '</p>
				</a>
			</li>',
		];

		return $controls;
	}
}
