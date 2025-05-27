<?php
/**
 * Backward compatibility with "qtranslate" WordPress plugin.
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Vendor_QtranslateX
 *
 * @since 4.12
 */
class Vc_Vendor_QtranslateX {

	/**
	 * Load hooks.
	 */
	public function load() {
		add_action( 'vc_backend_editor_render', [
			$this,
			'enqueueJsBackend',
		] );
		add_action( 'vc_frontend_editor_render', [
			$this,
			'enqueueJsFrontend',
		] );
		add_filter( 'vc_frontend_editor_iframe_url', [
			$this,
			'appendLangToUrl',
		] );

		add_filter( 'vc_nav_front_controls', [
			$this,
			'vcNavControlsFrontend',
		] );

		if ( ! vc_is_frontend_editor() ) {
			add_filter( 'vc_get_inline_url', [
				$this,
				'vcRenderEditButtonLink',
			] );
		}
	}

	/**
	 * Enqueue JS for backend.
	 */
	public function enqueueJsBackend() {
		wp_enqueue_script( 'vc_vendor_qtranslatex_backend', vc_asset_url( 'js/vendors/qtranslatex_backend.js' ), [
			'vc-backend-min-js',
			'jquery-core',
		], '1.0', true );
	}

	/**
	 * Append language to URL.
	 *
	 * @param string $link
	 * @return string
	 */
	public function appendLangToUrl( $link ) {
		global $q_config;
		if ( $q_config && isset( $q_config['language'] ) ) {
			return add_query_arg( [ 'lang' => ( $q_config['language'] ) ], $link );
		}

		return $link;
	}

	/**
	 * Enqueue JS for frontend.
	 */
	public function enqueueJsFrontend() {
		wp_enqueue_script( 'vc_vendor_qtranslatex_frontend', vc_asset_url( 'js/vendors/qtranslatex_frontend.js' ), [
			'vc-frontend-editor-min-js',
			'jquery-core',
		], '1.0', true );
	}

	/**
	 * Generate select for frontend.
	 *
	 * @return string
	 */
	public function generateSelectFrontend() {
		global $q_config;
		$output = '';
		$output .= '<select id="vc_vendor_qtranslatex_langs_front" class="vc_select vc_select-navbar">';
		$inline_url = vc_frontend_editor()->getInlineUrl();
		$active_language = $q_config['language'];
		$available_languages = $q_config['enabled_languages'];
		foreach ( $available_languages as $lang ) {
			$output .= '<option value="' . add_query_arg( [ 'lang' => $lang ], $inline_url ) . '"' . ( $active_language == $lang ? ' selected' : '' ) . ' > ' . qtranxf_getLanguageNameNative( $lang ) . '</option > ';
		}
		$output .= '</select > ';

		return $output;
	}

	/**
	 * VC Nav controls frontend.
	 *
	 * @param string $init_list
	 *
	 * @return array
	 */
	public function vcNavControlsFrontend( $init_list ) {
		if ( is_array( $init_list ) ) {
			$init_list[] = [
				'qtranslatex',
				'<li class="vc_pull-right" > ' . $this->generateSelectFrontend() . '</li > ',
			];
		}

		return $init_list;
	}

	/**
	 * Render edit button link.
	 *
	 * @param string $link
	 *
	 * @return string
	 */
	public function vcRenderEditButtonLink( $link ) {
		global $q_config;
		$active_language = $q_config['language'];

		return add_query_arg( [ 'lang' => $active_language ], $link );
	}
}
