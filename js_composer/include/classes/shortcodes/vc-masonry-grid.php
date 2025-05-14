<?php
/**
 * Class that handles specific [vc_masonry_grid] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_masonry_grid.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-basic-grid.php' );

/**
 * Class WPBakeryShortCode_Vc_Masonry_Grid
 */
class WPBakeryShortCode_Vc_Masonry_Grid extends WPBakeryShortCode_Vc_Basic_Grid {
	/**
	 * Get name.
	 *
	 * @return mixed|string
	 */
	protected function getFileName() {
		return 'vc_basic_grid';
	}

	/**
	 * Register element script.
	 */
	public function shortcodeScripts() {
		parent::shortcodeScripts();
		wp_register_script( 'vc_masonry', vc_asset_url( 'lib/vendor/node_modules/masonry-layout/dist/masonry.pkgd.min.js' ), [], WPB_VC_VERSION, true );
	}

	/**
	 * Enqueue element scripts.
	 */
	public function enqueueScripts() {
		wp_enqueue_script( 'vc_masonry' );
		parent::enqueueScripts();
	}

	/**
	 * Build element settings.
	 */
	public function buildGridSettings() {
		parent::buildGridSettings();
		$this->grid_settings['style'] .= '-masonry';
	}

	/**
	 * Get element masonry content.
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
	 * Get element lazy masonry.
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
	 * Get load more button.
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
