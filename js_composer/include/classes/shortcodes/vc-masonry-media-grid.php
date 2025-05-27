<?php
/**
 * Class that handles specific [vc_masonry_media_grid] shortcode.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-media-grid.php' );

/**
 * Class WPBakeryShortCode_Vc_Masonry_Media_Grid
 */
class WPBakeryShortCode_Vc_Masonry_Media_Grid extends WPBakeryShortCode_Vc_Media_Grid {

	/**
	 * Register shortcode specific scripts.
	 */
	public function shortcodeScripts() {
		parent::shortcodeScripts();
		wp_register_script( 'vc_masonry', vc_asset_url( 'lib/vendor/node_modules/masonry-layout/dist/masonry.pkgd.min.js' ), [], WPB_VC_VERSION, true );
	}

	/**
	 * Register shortcode specific scripts.
	 */
	public function enqueueScripts() {
		wp_enqueue_script( 'vc_masonry' );
		parent::enqueueScripts();
	}

	/**
	 * Build grid settings.
	 */
	public function buildGridSettings() {
		parent::buildGridSettings();
		$this->grid_settings['style'] .= '-masonry';
	}

	/**
	 * Get element content with element content wrapper.
	 *
	 * @param string $grid_style
	 * @param array $settings
	 * @param string $content
	 * @return string
	 */
	protected function contentAllMasonry( $grid_style, $settings, $content ) {
		return parent::contentAll( $grid_style, $settings, $content );
	}

	/**
	 * Get element content with attached lazy loading button.
	 *
	 * @param string $grid_style
	 * @param array $settings
	 * @param string $content
	 * @return string
	 */
	protected function contentLazyMasonry( $grid_style, $settings, $content ) {
		return parent::contentLazy( $grid_style, $settings, $content );
	}

	/**
	 * Get element content with attached load more button.
	 *
	 * @param string $grid_style
	 * @param array $settings
	 * @param string $content
	 * @return string
	 */
	protected function contentLoadMoreMasonry( $grid_style, $settings, $content ) {
		return parent::contentLoadMore( $grid_style, $settings, $content );
	}
}
