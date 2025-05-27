<?php
/**
 * Module Name: Post Custom Layout
 * Description: Add users optionality to change post initial layout.
 *
 * @since 7.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_manager()->path( 'MUTUAL_MODULES_DIR', 'class-module.php' );

/**
 * Module entry point.
 *
 * @since 7.7
 */
class Vc_Post_Custom_Layout_Module extends Vc_Module {
	/**
	 * Module meta key.
	 *
	 * @since 7.7
	 * @var string
	 */
	const CUSTOM_LAYOUT_META_KEY = '_wpb_post_custom_layout';

	/**
	 * Module settings that specify functionality common for some modules.
	 *
	 * @note We can use object functionality $this->get_module_functionality( 'post-meta' )->foo();
	 *
	 * @since 8.4
	 * @var array
	 */
	public $module_common_functionality = [ 'post-meta' ];

	/**
	 * Post meta key.
	 *
	 * @since 8.4
	 * @var string
	 */
	public $post_meta_key = 'post_custom_layout';

	/**
	 * Post meta slug.
	 *
	 * @since 8.4
	 * @var string
	 */
	public $post_meta_slug = 'custom_layout';

	/**
	 * Init module implementation.
	 *
	 * @since 7.7
	 */
	public function init() {
		// We initialize the common modules functionality in parent constructor.
		parent::__construct();

		add_action( 'template_include', [ $this, 'switch_post_custom_layout' ], 11 );

		add_filter( 'wpb_is_post_custom_layout_blank', [ $this, 'is_layout_blank' ] );
	}

	/**
	 * Get module post meta.
	 *
	 * @since 8.4
	 * @param array $post_custom_meta
	 * @param int $post_id
	 * @return string
	 */
	public function get_module_meta( $post_custom_meta, $post_id ) {
		return $this->get_custom_layout_name();
	}

	/**
	 * Change the path of the current template to our custom layout.
	 *
	 * @since 7.7
	 *
	 * @param string $template The path of the template to include.
	 * @return string
	 */
	public function switch_post_custom_layout( $template ) {
		if ( ! is_singular() ) {
			return $template;
		}
		$layout_name = $this->get_custom_layout_name();
		if ( ! $layout_name || 'default' === $layout_name ) {
			return $template;
		}

		$custom_layout_path = $this->get_custom_layout_path( $layout_name );
		if ( $custom_layout_path ) {
			$template = $custom_layout_path;
		}

		return apply_filters( 'vc_post_custom_layout_template', $template, $layout_name );
	}

	/**
	 * Get name of the custom layout.
	 *
	 * @note on a plugin core level right now we have only 'blank' layout.
	 * @since 7.7
	 *
	 * @return string
	 */
	public function get_custom_layout_name() {
		global $post;
		if ( $this->is_layout_switched_in_frontend_editor() ) {
			$layout_name = $this->get_layout_name_from_get_params();
		} else {
			$layout_name = $this->get_layout_from_meta();
		}

		$layout_name = empty( $layout_name ) ? '' : $layout_name;

		if ( ! empty( $post->post_content ) && ! $layout_name ) {
			$layout_name = 'default';
		}

		return apply_filters( 'vc_post_custom_layout_name', $layout_name );
	}

	/**
	 * Check if user switched layout in frontend editor.
	 *
	 * @note in such cases we should reload the page
	 * @since 7.7
	 *
	 * @return bool
	 */
	public function is_layout_switched_in_frontend_editor() {
		$params = $this->get_request_params();

		return isset( $params['vc_post_custom_layout'] );
	}

	/**
	 * For a frontend editor we keep layout as get param
	 * when we switching it inside editor and show user new layout inside editor.
	 *
	 * @since 7.7
	 *
	 * @return false|string
	 */
	public function get_layout_name_from_get_params() {
		$params = $this->get_request_params();

		return empty( $params['vc_post_custom_layout'] ) ? false : $params['vc_post_custom_layout'];
	}

	/**
	 * Retrieve get params.
	 *
	 * @description  we should obtain params from $_SERVER['HTTP_REFERER']
	 * if we try to get params inside iframe and from regular $_GET when outside
	 * @since 7.7
	 *
	 * @return array|false
	 */
	public function get_request_params() {
		if ( ! vc_is_page_editable() && ! vc_is_inline() ) {
			return false;
		}

		// inside iframe.
		if ( vc_is_page_editable() ) {
			$params = $this->get_params_from_server_referer();
			// outside iframe.
		} else {
            // phpcs:ignore
			$params = $_GET;
		}

		return $params;
	}

	/**
	 * Parse $_SERVER['HTTP_REFERER'] and get params from it.
	 *
	 * @since 7.7
	 *
	 * @return array|false
	 */
	public function get_params_from_server_referer() {
		if ( ! isset( $_SERVER['HTTP_REFERER'] ) ) {
			return false;
		}
        // phpcs:ignore
        $query = parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_QUERY );
		if ( ! $query ) {
			return false;
		}

		$params = [];
		parse_str( $query, $params );

		return $params;
	}

	/**
	 * Get previously saved layout from post meta.
	 *
	 * @since 7.7
	 *
	 * @return mixed
	 */
	public function get_layout_from_meta() {
		$post_id = wpb_update_id_with_preview_id( get_the_ID() );

		return get_post_meta( $post_id, self::CUSTOM_LAYOUT_META_KEY, true );
	}

	/**
	 * Get path of the custom layout.
	 *
	 * @note we keep all plugin layouts in include/templates/pages/layouts/ folder.
	 * @since 7.7
	 *
	 * @param string $layout_name
	 * @return string|false
	 */
	public function get_custom_layout_path( $layout_name ) {
		$custom_layout_path = vc_template( '/pages/layouts/' . sanitize_file_name( $layout_name ) . '.php' );
		if ( ! is_file( $custom_layout_path ) ) {
			return false;
		}

		return $custom_layout_path;
	}

	/**
	 * Get href for the custom layout by layout name.
	 *
	 * @since 7.7
	 *
	 * @param string $layout_name
	 * @return string
	 */
	public function get_layout_href_by_layout_name( $layout_name ) {
		if ( vc_is_page_editable() || vc_is_inline() ) {
			$frontend_editor = new Vc_Frontend_Editor();
			$href = $frontend_editor->getInlineUrl() . '&vc_post_custom_layout=' . $layout_name;
		} else {
			$href = '#';
		}

		return $href;
	}

	/**
	 * Check if layout active on current location.
	 *
	 * @since 7.7
	 *
	 * @param string $check_name
	 * @param string $location settings or welcome.
	 * @return bool
	 */
	public function check_if_layout_active( $check_name, $location ) {
		$current_name = $this->get_custom_layout_name();

		if ( $current_name && $current_name === $check_name ) {
			return true;
		}

		if ( ! $current_name && 'settings' === $location && 'default' === $check_name ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if the layout is 'blank'.
	 *
	 * @since 8.2
	 *
	 * @return bool
	 */
	public function is_layout_blank() {
		$layout_name = $this->get_custom_layout_name();

		return 'blank' === $layout_name;
	}
}
