<?php
/**
 * Module post meta functionality.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Vc_Module_Post_Meta class.
 *
 * @since 8.4
 */
class Vc_Module_Post_Meta {
	/**
	 * Module instance.
	 *
	 * @var Vc_Module
	 */
	public $module;

	/**
	 * Vc_Module_Post_Meta constructor.
	 *
	 * @param Vc_Module $module Module instance.
	 *
	 * @since 8.4
	 */
	public function __construct( $module ) {
		$this->module = $module;
		$this->init();
	}

	/**
	 * Init functionality.
	 *
	 * @since 8.4
	 */
	public function init() {
		add_filter( 'vc_base_save_post_' . $this->module->post_meta_slug, [ $this->module, 'validate_post_meta_value' ] );

		add_filter( 'vc_post_meta_list', [ $this->module, 'add_custom_meta_to_update' ] );

		add_filter( 'wpb_set_post_custom_meta', [ $this->module, 'set_post_custom_meta' ], 10, 2 );
	}

	/**
	 * Validate post meta value before saving.
	 *
	 * @since 8.4
	 * @param string $value
	 * @return string
	 */
	public function validate_post_meta_value( $value ) {
		return wp_strip_all_tags( $value );
	}

	/**
	 * Add custom js to the plugin post custom meta list.
	 *
	 * @since 8.4
	 * @param array $meta_list
	 * @return array
	 */
	public function add_custom_meta_to_update( $meta_list ) {
		$meta_list[] = $this->module->post_meta_slug;

		return $meta_list;
	}

	/**
	 * Set post custom meta.
	 *
	 * @since 7.7
	 * @param array $post_custom_meta
	 * @param WP_Post $post
	 * @return array
	 */
	public function set_post_custom_meta( $post_custom_meta, $post ) {
		$post_custom_meta[ $this->module->post_meta_key ] = $this->module->get_module_meta( $post_custom_meta, $post->ID );

		return $post_custom_meta;
	}
}
