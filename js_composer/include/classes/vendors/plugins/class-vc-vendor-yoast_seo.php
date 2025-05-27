<?php
/**
 * Backward compatibility with "Yoast" WordPress plugin.
 *
 * @see https://wordpress.org/plugins/wordpress-seo
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Vendor_YoastSeo
 *
 * @since 4.4
 */
class Vc_Vendor_YoastSeo {

	/**
	 * Created to improve yoast multiply calling wpseo_pre_analysis_post_content filter.
	 *
	 * @since 4.5.3
	 * @var string - parsed post content
	 */
	protected $parsed_content;

	/**
	 * Vc_Vendor_YoastSeo constructor.
	 */
	public function __construct() {
		add_action( 'vc_backend_editor_render', [
			$this,
			'enqueueJs',
		] );
		add_filter( 'wpseo_sitemap_urlimages', [
			$this,
			'filterSitemapUrlImages',
		], 10, 2 );
	}

	/**
	 * Add filter for yoast.
	 *
	 * @since 4.4
	 */
	public function load() {
		if ( class_exists( 'WPSEO_Metabox' ) && ( 'admin_page' === vc_mode() || 'admin_frontend_editor' === vc_mode() ) ) {
			add_filter( 'wpseo_pre_analysis_post_content', [
				$this,
				'filterResults',
			] );
		}
	}

	/**
	 * Properly parse content to detect images/text keywords.
	 *
	 * @param string $content
	 *
	 * @return string
	 * @since 4.4
	 */
	public function filterResults( $content ) {
		if ( empty( $this->parsed_content ) ) {
			global $post, $wp_the_query;
			$wp_the_query->post = $post; // since 4.5.3 to avoid the_post replaces.
			/**
			 * Vc_filter: vc_vendor_yoastseo_filter_results.
			 *
			 * @since 4.4.3
			 */
			do_action( 'vc_vendor_yoastseo_filter_results' );
			$this->parsed_content = do_shortcode( shortcode_unautop( $content ) );
            // phpcs:ignore
			wp_reset_query();
		}

		return $this->parsed_content;
	}

	/**
	 * Enqueue JS for Yoast SEO.
	 *
	 * @since 4.4
	 */
	public function enqueueJs() {
		require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/editor/class-vc-grid-item-editor.php' );
		if ( get_post_type() === Vc_Grid_Item_Editor::postType() ) {
			return;
		}
		wp_enqueue_script( 'yoast-seo-post-scraper' );
		wp_enqueue_script( 'yoast-seo-admin-global-script' );
		wp_enqueue_script( 'vc_vendor_seo_js', vc_asset_url( 'js/vendors/seo.js' ), [
			'underscore',
		], WPB_VC_VERSION, true );
	}

	/**
	 * Build frontend editor.
	 */
	public function frontendEditorBuild() {
		$vc_yoast_meta_box = $GLOBALS['wpseo_metabox'];
		remove_action( 'admin_init', [
			$GLOBALS['wpseo_meta_columns'],
			'setup_hooks',
		] );
		apply_filters( 'wpseo_use_page_analysis', false );
		remove_action( 'add_meta_boxes', [
			$vc_yoast_meta_box,
			'add_meta_box',
		] );
		remove_action( 'admin_enqueue_scripts', [
			$vc_yoast_meta_box,
			'enqueue',
		] );
		remove_action( 'wp_insert_post', [
			$vc_yoast_meta_box,
			'save_postdata',
		] );
		remove_action( 'edit_attachment', [
			$vc_yoast_meta_box,
			'save_postdata',
		] );
		remove_action( 'add_attachment', [
			$vc_yoast_meta_box,
			'save_postdata',
		] );
		remove_action( 'post_submitbox_start', [
			$vc_yoast_meta_box,
			'publish_box',
		] );
		remove_action( 'admin_init', [
			$vc_yoast_meta_box,
			'setup_page_analysis',
		] );
		remove_action( 'admin_init', [
			$vc_yoast_meta_box,
			'translate_meta_boxes',
		] );
		remove_action( 'admin_footer', [
			$vc_yoast_meta_box,
			'template_keyword_tab',
		] );
	}

	/**
	 * Filter sitemap url images.
	 *
	 * @param array $images
	 * @param int $id
	 * @return array
	 */
	public function filterSitemapUrlImages( $images, $id ) {
		if ( empty( $images ) ) {
			$post = get_post( $id );
			if ( $post && strpos( $post->post_content, '[vc_row' ) !== false ) {
				preg_match_all( '/(?:image|images|ids|include)\=\"([^\"]+)\"/', $post->post_content, $matches );
				foreach ( $matches[1] as $m ) {
					$ids = explode( ',', $m );
					foreach ( $ids as $id ) {
						if ( (int) $id ) {
							$images[] = [
								'src' => wp_get_attachment_url( $id ),
								'title' => get_the_title( $id ),
							];
						}
					}
				}
			}
		}

		return $images;
	}
}
