<?php
/**
 * Backward compatibility with "JW Player" WordPress plugin.
 *
 * @see https://www.ilghera.com/product/jw-player-7-for-wordpress-premium
 *
 * Used to initialize plugin jwplayer vendor for frontend editor.
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * JWPLayer loader.
 *
 * @since 4.3
 */
class Vc_Vendor_Jwplayer {
	/**
	 * Dublicate jwplayer logic for editor, when used in frontend editor mode.
	 *
	 * @since 4.3
	 */
	public function load() {

		add_action( 'wp_enqueue_scripts', [
			$this,
			'vc_load_iframe_jscss',
		] );
		add_filter( 'vc_front_render_shortcodes', [
			$this,
			'renderShortcodes',
		] );
		add_filter( 'vc_frontend_template_the_content', [
			$this,
			'wrapPlaceholder',
		] );

		// fix for #1065.
		add_filter( 'vc_shortcode_content_filter_after', [
			$this,
			'renderShortcodesPreview',
		] );
	}

	/**
	 * Render shortcodes.
	 *
	 * @param string $output
	 *
	 * @return mixed|string
	 * @since 4.3
	 */
	public function renderShortcodes( $output ) {
		$output = str_replace( '][jwplayer', '] [jwplayer', $output ); // fixes jwplayer shortcode regex..
		$data = JWP6_Shortcode::the_content_filter( $output );
		preg_match_all( '/(jwplayer-\d+)/', $data, $matches );
		$pairs = array_unique( $matches[0] );

		if ( count( $pairs ) > 0 ) {
			$id_zero = time();
			foreach ( $pairs as $pair ) {
				$data = str_replace( $pair, 'jwplayer-' . ( $id_zero++ ), $data );
			}
		}

		return $data;
	}

	/**
	 * Wrap placeholder.
	 *
	 * @param string $content
	 * @return mixed
	 */
	public function wrapPlaceholder( $content ) {
		add_shortcode( 'jwplayer', [
			$this,
			'renderPlaceholder',
		] );

		return $content;
	}

	/**
	 * Render placeholder.
	 *
	 * @return string
	 */
	public function renderPlaceholder() {
		return '<div class="vc_placeholder-jwplayer"></div>';
	}

	/**
	 * Render shortcodes preview.
	 *
	 * @param string $output
	 *
	 * @return string
	 * @since 4.3, due to #1065
	 */
	public function renderShortcodesPreview( $output ) {
		$output = str_replace( '][jwplayer', '] [jwplayer', $output ); // fixes jwplayer shortcode regex..

		return $output;
	}

	/**
	 * Load iframe js.
	 *
	 * @since 4.3
	 * @todo check it for preview mode (check is it needed)
	 */
	public function vc_load_iframe_jscss() {
		wp_enqueue_script( 'vc_vendor_jwplayer', vc_asset_url( 'js/frontend_editor/vendors/plugins/jwplayer.js' ), [ 'jquery-core' ], '1.0', true );
	}
}
