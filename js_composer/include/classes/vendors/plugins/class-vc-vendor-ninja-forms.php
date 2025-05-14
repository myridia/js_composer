<?php
/**
 * Backward compatibility with "Ninja Forms" WordPress plugin.
 *
 * @see https://wordpress.org/plugins/ninja-forms
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Ninja Forms vendor
 *
 * @since 4.4
 */
class Vc_Vendor_NinjaForms {
	/**
	 * Counter.
	 *
	 * @var null
	 */
	private static $ninja_count;

	/**
	 * Implement interface, map ninja forms shortcode
	 *
	 * @since 4.4
	 */
	public function load() {
		vc_lean_map( 'ninja_form', [
			$this,
			'addShortcodeSettings',
		] );

		add_filter( 'vc_frontend_editor_load_shortcode_ajax_output', [
			$this,
			'replaceIds',
		] );
	}

	/**
	 * Mapping settings for lean method.
	 *
	 * @param string $tag
	 *
	 * @return array
	 * @since 4.9
	 */
	public function addShortcodeSettings( $tag ) {

		$ninja_forms = $this->get_forms();

		return [
			'base' => $tag,
			'name' => esc_html__( 'Ninja Forms', 'js_composer' ),
			'icon' => 'icon-wpb-ninjaforms',
			'category' => esc_html__( 'Content', 'js_composer' ),
			'description' => esc_html__( 'Place Ninja Form', 'js_composer' ),
			'params' => [
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Select ninja form', 'js_composer' ),
					'param_name' => 'id',
					'value' => $ninja_forms,
					'save_always' => true,
					'description' => esc_html__( 'Choose previously created ninja form from the drop down list.', 'js_composer' ),
				],
			],
		];
	}

	/**
	 * Get all forms.
	 *
	 * @return array
	 */
	private function get_forms() {
		$ninja_forms = [];
		if ( $this->is_ninja_forms_three() ) {

			$ninja_forms_data = ninja_forms_get_all_forms();

			if ( ! empty( $ninja_forms_data ) ) {
				// Fill array with Name=>Value(ID).
				foreach ( $ninja_forms_data as $key => $value ) {
					if ( is_array( $value ) ) {
						$ninja_forms[ $value['name'] ] = $value['id'];
					}
				}
			}
		} else {

			$ninja_forms_data = Ninja_Forms()->form()->get_forms();

			if ( ! empty( $ninja_forms_data ) ) {
				// Fill array with Name=>Value(ID).
				foreach ( $ninja_forms_data as $form ) {
					$ninja_forms[ $form->get_setting( 'title' ) ] = $form->get_id();
				}
			}
		}

		return $ninja_forms;
	}

	/**
	 * Check if vendor plugin version 3.0 is installed.
	 *
	 * @return bool
	 */
	private function is_ninja_forms_three() {
		return version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3.0', '<' ) ||
			( get_option( 'ninja_forms_load_deprecated', false ) && function_exists( 'ninja_forms_get_all_forms' ) );
	}

	/**
	 * Replace ids.
	 *
	 * @param string $output
	 * @return mixed
	 */
	public function replaceIds( $output ) {
		if ( is_null( self::$ninja_count ) ) {
			self::$ninja_count = 1;
		} else {
			self::$ninja_count++;
		}
		$patterns = [
			'(nf-form-)(\d+)(-cont)',
			'(nf-form-title-)(\d+)()',
			'(nf-form-errors-)(\d+)()',
			'(form.id\s*=\s*\')(\d+)(\')',
		];
        // phpcs:ignore
		$time = time() . self::$ninja_count . rand( 100, 999 );
		foreach ( $patterns as $pattern ) {
			$output = preg_replace( '/' . $pattern . '/', '${1}' . $time . '${3}', $output );
		}
		$replace_to = <<<JS
if (typeof nfForms !== 'undefined') {
  nfForms = nfForms.filter( function(item) {
    if (item && item.id) {
      return document.querySelector('#nf-form-' + item.id + '-cont')
    }
  })
}
JS;
		$response = str_replace( 'var nfForms', $replace_to . ';var nfForms', $output );

		return $response;
	}
}
