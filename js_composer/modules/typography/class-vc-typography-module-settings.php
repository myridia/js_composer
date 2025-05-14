<?php
/**
 * Module Settings.
 *
 * @since 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Typography module settings.
 *
 * @since 8.0
 */
class Vc_Typography_Module_Settings {
	/**
	 * Field option_name where we keep adobe web project fonts data.
	 *
	 * @since 8.0
	 * @var string
	 */
	const ADOBE_FONT_DATA_OPTION = 'adobe_fonts_data';

	/**
	 * Field option_name where we keep google fonts data.
	 *
	 * @since 8.0
	 * @var string
	 */
	const GOOGLE_FONTS_DATA_OPTION = 'google_fonts_data';

	/**
	 * Field option_name where we keep adobe web project id.
	 *
	 * @since 8.0
	 * @var string
	 */
	const ADOBE_FONT_WEB_PROJECT_ID_OPTION = 'adobe_fonts_web_project_id';

	/**
	 * Init point.
	 *
	 * @since 8.0
	 */
	public function init() {
		add_filter( 'vc_settings_tabs', [ $this, 'set_setting_tab' ], 12 );
		add_action( 'vc_settings_set_sections', [ $this, 'add_settings_section_google_fonts_synchronize' ] );
		add_action( 'vc_settings_set_sections', [ $this, 'add_settings_section_adobe_fonts_synchronize' ] );
		add_action( 'vc_settings_set_sections', [ $this, 'add_settings_section_messages' ] );
		add_action( 'vc-settings-render-tab-vc-typography', [ $this, 'load_module_settings_assets' ] );
		add_action( 'vc-settings-render-tab-vc-typography', [ $this, 'set_ajax_save' ] );
	}

	/**
	 * Add module tab to settings.
	 *
	 * @param array $tabs Existing settings tabs.
	 * @return array Modified settings tabs.
	 * @since 8.0
	 */
	public function set_setting_tab( $tabs ) {
		if ( vc_settings()->showConfigurationTabs() ) {
			if ( apply_filters( 'vc_settings_page_show_typography_tab', true ) ) {
				$tabs['vc-typography'] = esc_html__( 'Typography', 'js_composer' );
			}
		}
		return $tabs;
	}

	/**
	 * Add Google Fonts sync sections to the Typography tab.
	 *
	 * @since 8.0
	 */
	public function add_settings_section_google_fonts_synchronize() {
		$tab = 'typography';
		$settings = vc_settings();
		$settings->addSection( $tab );

		$settings->addField(
			$tab,
			esc_html__( 'Google Fonts', 'js_composer' ),
			'vc_google_fonts_sync',
			null,
			[
				$this,
				'google_fonts_sync_callback',
			],
			[
				'id' => 'vc_google_fonts_sync',
			]
		);
	}

	/**
	 * Render the Google Fonts synchronization field.
	 *
	 * @param array $args Field arguments.
	 * @since 8.0
	 */
	public function google_fonts_sync_callback( $args ) {
		vc_include_template( '/pages/vc-settings/fields/modules/typography/google-fonts.php', [ 'args' => $args ] );
	}

	/**
	 * Add Adobe Kits sync sections to the Typography tab.
	 *
	 * @see https://fonts.adobe.com
	 *
	 * @since 8.0
	 */
	public function add_settings_section_adobe_fonts_synchronize() {
		$tab = 'typography';
		$settings = vc_settings();
		$settings->addSection( $tab );

		$settings->addField(
			$tab,
			esc_html__( 'Adobe Web Project ID', 'js_composer' ),
			self::ADOBE_FONT_WEB_PROJECT_ID_OPTION,
			null,
			[
				$this,
				'adobe_fonts_sync_callback',
			]
		);
		$settings->addField(
			$tab,
			'',
			self::ADOBE_FONT_DATA_OPTION,
			null,
			[
				$this,
				'adobe_fonts_data_callback',
			],
			[
				'class' => 'wpb_adobe_fonts_data',
			]
		);
	}

	/**
	 * Add messages for a typography tab.
	 *
	 * @since 8.0
	 */
	public function add_settings_section_messages() {
		$tab = 'typography';
		$settings = vc_settings();
		$settings->addSection( $tab );

		$settings->addField(
			$tab,
			'',
			'vc-messages',
			null,
			[
				$this,
				'output_tab_messages',
			]
		);
	}

	/**
	 * Output messages for a typography tab.
	 *
	 * @since 8.0
	 */
	public function output_tab_messages() {
		vc_include_template(
			'/pages/vc-settings/fields/modules/typography/messages.php'
		);
	}

	/**
	 * Render the Adobe Fonts synchronization fields.
	 *
	 * @since 8.0
	 */
	public function adobe_fonts_sync_callback() {
		$field = vc_settings()::$field_prefix . 'adobe_fonts_web_project_id';

		vc_include_template(
			'/pages/vc-settings/fields/modules/typography/adobe-fonts.php',
			[
				'field_value' => get_option( $field ),
				'field_name' => $field,
			]
		);
	}

	/**
	 * Render the Adobe Fonts data hidden field.
	 *
	 * @since 8.0
	 */
	public function adobe_fonts_data_callback() {
		$field = vc_settings()::$field_prefix . 'adobe_fonts_data';

		vc_include_template(
			'/pages/vc-settings/fields/modules/typography/adobe-fonts-data-hidden-field.php',
			[
				'field_value' => get_option( $field ),
				'field_name' => $field,
			]
		);
	}

	/**
	 * Load scripts and styles needed for the settings tab.
	 *
	 * @since 8.0
	 */
	public function load_module_settings_assets() {
		wp_enqueue_script( 'wpb_typography_module', vc_asset_url( '../modules/typography/assets/dist/module.min.js' ), [ 'jquery' ], WPB_VC_VERSION, true );
		wp_enqueue_style( 'wpb_typography_module', vc_asset_url( '../modules/typography/assets/dist/module.min.css' ), false, WPB_VC_VERSION );

		wp_localize_script( 'wpb_typography_module', 'i18nLocaleSettings', [
			'enter_adobe_sync_web_project_id' => esc_html__( 'Please enter Adobe Web Project ID before activate synchronize.', 'js_composer' ),
			'adobe_fonts_sync_failed' => esc_html__( 'The issue with the request to synchronization service. Please try again later.', 'js_composer' ),
			'adobe_fonts_synced' => esc_html__( 'Your web project fonts were successfully synced. Now you can use them in editor elements', 'js_composer' ),
			'google_fonts_sync_failed' => esc_html__( 'The issue with the request to synchronization service. Please try again later.', 'js_composer' ),
			'google_fonts_synced' => esc_html__( 'Google fonts were successfully synced. Now you can use them in editor elements', 'js_composer' ),
		] );
	}

	/**
	 * Set ajax save for module settings page.
	 *
	 * @param Vc_Page $page
	 */
	public function set_ajax_save( $page ) {
		$page->set_ajax_save();
	}
}
