<?php
/**
 * Module Settings.
 *
 * @since 7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Module settings.
 *
 * @since 7.9
 */
class Vc_Color_Picker_Module_Settings {
	/**
	 * Init point.
	 *
	 * @since 7.9
	 */
	public function init() {
		add_filter( 'vc_settings_tabs', [
			$this,
			'set_setting_tab',
		], 12 );

		add_action( 'vc_settings_set_sections', [
			$this,
			'add_settings_section',
		] );

		add_action( 'vc-settings-render-tab-vc-color-picker', [
			$this,
			'load_module_settings_assets',
		] );

		add_filter( 'vc_get_editor_wpb_data', [
			$this,
			'add_module_wpb_data',
		] );

		add_filter( 'vc_get_settings_wpb_data', [
			$this,
			'add_module_wpb_data',
		] );

		if ( 'vc-color-picker' === vc_get_param( 'page' ) ) {
			add_action( 'wpb_add_after_settings_form', [
				$this,
				'render_setting_tab_html',
			] );
		}

		add_action( 'vc_after_init', [
			$this,
			'restore_default',
		] );

		$this->gather_pickr_colors();
	}

	/**
	 * Add module tab to settings.
	 *
	 * @param array $tabs
	 * @return array
	 * @since 7.9
	 */
	public function set_setting_tab( $tabs ) {
		if ( vc_settings()->showConfigurationTabs() ) {
			// phpcs:ignore:WordPress.NamingConventions.ValidHookName.UseUnderscores
			if ( apply_filters( 'vc_settings_page_show_color-picker-tab', true ) ) {
				$tabs['vc-color-picker'] = esc_html__( 'Color Picker Settings', 'js_composer' );
			}
		}

		return $tabs;
	}

	/**
	 * Get color settings.
	 *
	 * @return array
	 * @since 7.9
	 */
	public function get_color_settings() {
		return [
			[ 'vc_pickr_color_1' => [ 'title' => esc_html__( 'Color #1', 'js_composer' ) ] ],
			[ 'vc_pickr_color_2' => [ 'title' => esc_html__( 'Color #2', 'js_composer' ) ] ],
			[ 'vc_pickr_color_3' => [ 'title' => esc_html__( 'Color #3', 'js_composer' ) ] ],
			[ 'vc_pickr_color_4' => [ 'title' => esc_html__( 'Color #4', 'js_composer' ) ] ],
			[ 'vc_pickr_color_5' => [ 'title' => esc_html__( 'Color #5', 'js_composer' ) ] ],
			[ 'vc_pickr_color_6' => [ 'title' => esc_html__( 'Color #6', 'js_composer' ) ] ],
			[ 'vc_pickr_color_7' => [ 'title' => esc_html__( 'Color #7', 'js_composer' ) ] ],
			[ 'vc_pickr_color_8' => [ 'title' => esc_html__( 'Color #8', 'js_composer' ) ] ],
		];
	}

	/**
	 * Get default color settings.
	 *
	 * @return array
	 * @since 7.9
	 */
	public function get_default_color_settings() {
		return [
			'vc_pickr_color_1' => '#000000',
			'vc_pickr_color_2' => '#FFFFFF',
			'vc_pickr_color_3' => '#DD3333',
			'vc_pickr_color_4' => '#DD9933',
			'vc_pickr_color_5' => '#EEEE22',
			'vc_pickr_color_6' => '#81D742',
			'vc_pickr_color_7' => '#1E73BE',
			'vc_pickr_color_8' => '#8224E3',
		];
	}

	/**
	 * Add sections to plugin settings tab.
	 *
	 * @since 7.9
	 */
	public function add_settings_section() {
		$tab = 'color-picker';
		$settings = vc_settings();
		$settings->addSection( $tab );
		$default_colors = $this->get_default_color_settings();

		foreach ( $this->get_color_settings() as $color_set ) {
			foreach ( $color_set as $key => $data ) {
				$settings->addField( $tab, $data['title'], $key, [
					$this,
					'sanitize_color_callback',
				], [
					$this,
					'color_callback',
				], [
					'id' => $key,
					'default_color' => $default_colors[ $key ],
				] );
			}
		}
	}

	/**
	 * Render tab content.
	 *
	 * @since 7.9
	 */
	public function render_setting_tab_html() {
		vc_include_template( '/pages/vc-settings/vc-color-picker.php' );
	}

	/**
	 * Sanitize use custom callback.
	 *
	 * @param mixed $color
	 *
	 * @return mixed
	 * @since 7.9
	 */
	public function sanitize_color_callback( $color ) {
		return $color;
	}

	/**
	 * Gather pickr colors.
	 *
	 * @since 7.9
	 */
	public function gather_pickr_colors() {
		$default_colors = $this->get_default_color_settings();
		$colors = [];

		foreach ( $default_colors as $field => $default ) {
			$value = get_option( vc_settings()::$field_prefix . $field );
			$value = $value ?: $default;
			$colors[] = $value;
		}
		return $colors;
	}

	/**
	 * Filed output callback.
	 *
	 * @param array $args
	 * @since 7.9
	 */
	public function color_callback( $args ) {
		$field = $args['id'];
		$value = get_option( vc_settings()::$field_prefix . $field );
		$value = $value ?: $this->get_default( $field );
		echo '<div class="color-group"><div class="wpb-color-picker"></div><input type="text" name="' . esc_attr( vc_settings()::$field_prefix . $field ) . '" value="' . esc_attr( $value ) . '" data-default-value="' . esc_attr( $args['default_color'] ) . '" class="vc_color-control css-control vc_ui-hidden"></div>';
	}

	/**
	 * Restore default color settings.
	 *
	 * @since 7.9
	 */
	public function restore_default() {
		$is_restore = 'restore_color-picker' === vc_post_param( 'vc_action' ) && vc_user_access()->check( 'wp_verify_nonce', vc_post_param( '_wpnonce' ), vc_settings()->getOptionGroup() . '_color-picker-options' )->validateDie()->wpAny( 'manage_options' )->validateDie()->part( 'settings' )->can( 'vc-color-picker-tab' )->validateDie()->get();
		if ( $is_restore ) {
			$this->restore_color();
		}
	}

	/**
	 * Restore default module settings.
	 *
	 * @since 7.9
	 */
	public function restore_color() {
		$settings = vc_settings();
		foreach ( $this->get_color_settings() as $color_set ) {
			foreach ( $color_set as $key => $value ) {
				delete_option( $settings::$field_prefix . $key );
			}
		}
	}

	/**
	 * Get default color.
	 *
	 * @param string $key
	 * @return string
	 * @since 7.9
	 */
	public function get_default( $key ) {
		$default = $this->get_default_color_settings();

		return ! empty( $default[ $key ] ) ? $default[ $key ] : '';
	}

	/**
	 * Add module $jsData.
	 *
	 * @param array $wpb_data
	 * @return array
	 * @since 7.9
	 */
	public function add_module_wpb_data( $wpb_data ) {
		$wpb_data['pickrColors'] = $this->gather_pickr_colors();

		return $wpb_data;
	}

	/**
	 * Load scripts that demand tab settings.
	 *
	 * @since 7.8
	 */
	public function load_module_settings_assets() {
		wp_enqueue_style( 'pickr', vc_asset_url( 'lib/vendor/node_modules/@simonwep/pickr/dist/themes/classic.min.css' ), [], WPB_VC_VERSION );
		wp_enqueue_script( 'pickr', vc_asset_url( 'lib/vendor/node_modules/@simonwep/pickr/dist/pickr.es5.min.js' ), [], WPB_VC_VERSION, true );
		wp_enqueue_script( 'wpb_color_picker_module', vc_asset_url( '../modules/color-picker/assets/dist/module.min.js' ), [], WPB_VC_VERSION, true );
		wp_enqueue_style( 'wpb_automapper_module', vc_asset_url( '../modules/color-picker/assets/dist/module.min.css' ), false, WPB_VC_VERSION );
	}
}
