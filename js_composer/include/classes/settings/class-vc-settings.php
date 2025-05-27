<?php
/**
 * Main plugin settings file.
 *
 * @since 3.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Settings page for VC. list of tabs for function composer
 *
 * Settings page for VC creates menu item in admin menu as subpage of Settings section.
 * Settings are build with WP settings API and organized as tabs.
 *
 * List of tabs
 * 1. General Settings - set access rules and allowed content types for editors.
 * 2. Modules Manager - set access to certain plugin functionality.
 * 3. Role Manager - set access rules and allowed content types for editors.
 * 4. Product License - license key activation for automatic VC updates.
 * 5. Design Options - custom color and spacing editor for VC shortcodes elements.
 * 6. Custom CSS - add custom css to your WP pages.
 * 7. Custom JS - add custom css to your WP pages.
 * 8. WPBakery AI - access to AI options.
 * 9. My Shortcodes - automated mapping tool for shortcodes.
 *
 * @link http://codex.wordpress.org/Settings_API WordPress settings API
 */
class Vc_Settings {
	/**
	 * List of tabs for settings.
	 *
	 * @var array
	 */
	public $tabs;

	/**
	 * Deactivation flag.
	 *
	 * @var bool
	 */
	public $deactivate;

	/**
	 * Locale settings for the plugin.
	 *
	 * @var array
	 */
	public $locale;

	/**
	 * Option group name.
	 *
	 * @var string
	 */
	protected $option_group = 'wpb_js_composer_settings';

	/**
	 * Page slug for the settings.
	 *
	 * @var string
	 */
	protected $page = 'vc_settings';

	/**
	 * Prefix for the fields.
	 *
	 * @var string
	 */
	public static $field_prefix = 'wpb_js_';

	/**
	 * Notification name.
	 *
	 * @var string
	 */
	protected static $notification_name = 'wpb_js_notify_user_about_element_class_names';

	/**
	 * Default settings.
	 *
	 * @var mixed
	 */
	protected static $defaults;

	/**
	 * Composer instance.
	 *
	 * @var mixed
	 */
	protected $composer;

	/**
	 * Default Google Fonts subsets.
	 *
	 * @var array
	 */
	protected $google_fonts_subsets_default = [ 'latin' ];

	/**
	 * Available Google Fonts subsets.
	 *
	 * @var array
	 */
	protected $google_fonts_subsets = [
		'latin',
		'vietnamese',
		'cyrillic',
		'latin-ext',
		'greek',
		'cyrillic-ext',
		'greek-ext',
	];

	/**
	 * Excluded Google Fonts subsets.
	 *
	 * @var array
	 */
	public $google_fonts_subsets_excluded = [];

	/**
	 * Google Fonts subsets settings.
	 *
	 * @var mixed
	 */
	protected $google_fonts_subsets_settings;

	/**
	 * Support portal beta testers endpoint.
	 *
	 * @since 8.4
	 * @var string
	 */
	public $support_portal_beta_testers_endpoint = 'https://support.wpbakery.com/api/external/fluentcrm/set-beta-tester';

	/**
	 * Set the field prefix.
	 *
	 * @param string $field_prefix
	 */
	public static function setFieldPrefix( $field_prefix ) {
		self::$field_prefix = $field_prefix;
	}

	/**
	 * Get the settings page slug.
	 *
	 * @return string
	 */
	public function page() {
		return $this->page;
	}

	/**
	 * Check if the editor is enabled.
	 *
	 * @return bool
	 */
	public function isEditorEnabled() {
		global $current_user;
		wp_get_current_user();

		// @var $settings - get use group access rules.
		$settings = $this->get( 'groups_access_rules' );

		$show = true;
		foreach ( $current_user->roles as $role ) {
			if ( isset( $settings[ $role ]['show'] ) && 'no' === $settings[ $role ]['show'] ) {
				$show = false;
				break;
			}
		}

		return $show;
	}

	/**
	 * Set the tabs for the settings page.
	 */
	public function setTabs() {
		$this->tabs = [];

		if ( $this->showConfigurationTabs() ) {
			$this->tabs['vc-general'] = esc_html__( 'General Settings', 'js_composer' );
			$this->tabs['vc-modules'] = esc_html__( 'Module Manager', 'js_composer' );
		}

		if ( ! vc_is_network_plugin() || ( vc_is_network_plugin() && is_network_admin() ) ) {
			if ( ! vc_is_updater_disabled() && ! wpb_check_wordpress_com_env() ) {
				$this->tabs['vc-updater'] = esc_html__( 'Product License', 'js_composer' );
			}
		}
	}

	/**
	 * Set the tabs for the settings page.
	 */
	public function getTabs() {
		if ( ! isset( $this->tabs ) ) {
			$this->setTabs();
		}

		return apply_filters( 'vc_settings_tabs', $this->tabs );
	}

	/**
	 * Check if configuration tabs should be shown.
	 *
	 * @return bool
	 */
	public function showConfigurationTabs() {
		return ! vc_is_network_plugin() || ! is_network_admin();
	}

	/**
	 * Render
	 *
	 * @param string $tab
	 * @throws \Exception
	 */
	public function renderTab( $tab ) {
		require_once vc_path_dir( 'CORE_DIR', 'class-vc-page.php' );

		$tabs = $this->getTabs();
		foreach ( $tabs as $key => $value ) {
			if ( ! vc_user_access()->part( 'settings' )->can( $key . '-tab' )->get() ) {
				unset( $tabs[ $key ] );
			}
		}
		$page = new Vc_Page();
        // phpcs:ignore:WordPress.NamingConventions.ValidHookName.UseUnderscores
		$page->setSlug( $tab )->setTitle( isset( $tabs[ $tab ] ) ? $tabs[ $tab ] : '' )->setTemplatePath( apply_filters( 'vc_settings-render-tab-' . $tab, 'pages/vc-settings/tab.php' ) );

        // phpcs:ignore:WordPress.NamingConventions.ValidHookName.UseUnderscores
		do_action( 'vc-settings-render-tab-' . $tab, $page );

		vc_include_template( 'pages/vc-settings/index.php', [
			'pages' => $tabs,
			'active_page' => $page,
			'vc_settings' => $this,
		] );
	}

	/**
	 * Init settings page && menu item
	 * vc_filter: vc_settings_tabs - hook to override settings tabs
	 */
	public function initAdmin() {
		$this->setTabs();

		add_action( 'update_option_wpb_js_modules', [
			$this,
			'reset_modules_dependency',
		], 10, 2 );

		add_action( 'add_option_wpb_js_modules', [
			$this,
			'reset_modules_dependency',
		], 10, 2 );

		add_action( 'add_option_wpb_js_beta_version', [
			$this,
			'subscribe_user_to_beta',
		], 10, 2 );

		add_action( 'update_option_wpb_js_beta_version', [
			$this,
			'subscribe_user_to_beta',
		], 10, 2 );

		$this->set_sections();

		/**
		 * Custom Tabs
		 */
		foreach ( $this->getTabs() as $tab => $title ) {
            // phpcs:ignore:WordPress.NamingConventions.ValidHookName.UseUnderscores
			do_action( 'vc_settings_tab-' . preg_replace( '/^vc\-/', '', $tab ), $this );
		}

		/**
		 * Tab: Updater
		 */
		$tab = 'updater';
		$this->addSection( $tab );
	}

	/**
	 * Set sections
	 *
	 * @since 7.7
	 */
	public function set_sections() {
		$this->set_general_section();
		$this->set_modules_section();

		/**
		 * Set settings sections tabs.
		 *
		 * @since 7.7
		 */
		do_action( 'vc_settings_set_sections', $this );
	}

	/**
	 * Set general section
	 *
	 * @since 7.7
	 */
	public function set_general_section() {
		$tab = 'general';
		$this->addSection( $tab );

		$this->addField( $tab, esc_html__( 'Disable responsive content elements', 'js_composer' ), 'not_responsive_css', [
			$this,
			'sanitize_not_responsive_css_callback',
		], [
			$this,
			'not_responsive_css_field_callback',
		], [
			'info' => esc_html__( 'Disable content elements from "stacking" one on top other on small media screens (Example: mobile devices).', 'js_composer' ),
		]    );

		$this->addField( $tab, esc_html__( 'Google fonts subsets', 'js_composer' ), 'google_fonts_subsets', [
			$this,
			'sanitize_google_fonts_subsets_callback',
		], [
			$this,
			'google_fonts_subsets_callback',
		], [
			'info' => esc_html__( 'Select subsets for Google Fonts available to content elements.', 'js_composer' ),
		]    );

		$this->addField( $tab, esc_html__( 'Local Google Fonts', 'js_composer' ), 'local_google_fonts', [
			$this,
			'sanitize_local_google_fonts_callback',
		], [
			$this,
			'local_google_fonts_callback',
		] );

		$this->addField( $tab, esc_html__( 'Beta Version', 'js_composer' ), 'beta_version', [
			$this,
			'sanitize_beta_version_callback',
		], [
			$this,
			'beta_version_callback',
		] );
	}

	/**
	 * Set modules section
	 *
	 * @since 7.7
	 */
	public function set_modules_section() {
		$tab = 'modules';

		$this->addField($tab, '', vc_modules_manager()->option_slug, [
			$this,
			'sanitize_modules_callback',
		], [
			$this,
			'use_modules_callback',
		]);

		$this->addSection( $tab );
	}

	/**
	 * Creates new section.
	 *
	 * @param string $tab - tab key name as tab section.
	 * @param string $title - Human title.
	 * @param callable|null $callback - function to build section header.
	 */
	public function addSection( $tab, $title = null, $callback = null ) {
		add_settings_section( $this->option_group . '_' . $tab, $title, ( null !== $callback ? $callback : [
			$this,
			'setting_section_callback_function',
		] ), $this->page . '_' . $tab );
	}

	/**
	 * Create field in section.
	 *
	 * @param string $tab
	 * @param string $title
	 * @param string $field_name
	 * @param callable $sanitize_callback
	 * @param callable $field_callback
	 * @param array $args
	 *
	 * @return $this
	 */
	public function addField( $tab, $title, $field_name, $sanitize_callback, $field_callback, $args = [] ) {
		register_setting( $this->option_group . '_' . $tab, self::$field_prefix . $field_name, $sanitize_callback );
		add_settings_field( self::$field_prefix . $field_name, $title, $field_callback, $this->page . '_' . $tab, $this->option_group . '_' . $tab, $args );

		return $this; // chaining.
	}

	/**
	 * Get option.
	 *
	 * @param string $option_name
	 *
	 * @param bool $default_value
	 *
	 * @return mixed
	 */
	public static function get( $option_name, $default_value = false ) {
		return get_option( self::$field_prefix . $option_name, $default_value );
	}

	/**
	 * Set option.
	 *
	 * @param string $option_name
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public static function set( $option_name, $value ) {
		return update_option( self::$field_prefix . $option_name, $value );
	}

	/**
	 * Set up the enqueue for the CSS & JavaScript files.
	 */
	public function adminLoad() {
		wp_register_script( 'wpb_js_composer_settings', vc_asset_url( 'js/dist/settings.min.js' ), [], WPB_VC_VERSION, true );
		wp_register_script( 'wpb-popper', vc_asset_url( 'lib/vendor/node_modules/@popperjs/core/dist/umd/popper.min.js' ), [], WPB_VC_VERSION, true );
		wp_enqueue_style( 'js_composer_settings', vc_asset_url( 'css/js_composer_settings.min.css' ), false, WPB_VC_VERSION );
		wp_enqueue_script( 'backbone' );
		wp_enqueue_script( 'shortcode' );
		wp_enqueue_script( 'underscore' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'wpb_js_composer_settings' );
		wp_enqueue_script( 'wpb-popper' );

		$this->locale = apply_filters( 'vc_get_settings_locale', [
			'are_you_sure_reset_css_classes' => esc_html__( 'Are you sure you want to reset to defaults?', 'js_composer' ),
			'are_you_sure_reset_color' => esc_html__( 'Are you sure you want to reset to defaults?', 'js_composer' ),
			'saving' => esc_html__( 'Saving...', 'js_composer' ),
			'save' => esc_html__( 'Save Changes', 'js_composer' ),
			'saved' => esc_html__( 'Design Options successfully saved.', 'js_composer' ),
			'save_error' => esc_html__( 'Design Options could not be saved', 'js_composer' ),
			'form_save_error' => esc_html__( 'Problem with AJAX request execution, check internet connection and try again.', 'js_composer' ),
			'are_you_sure_delete' => esc_html__( 'Are you sure you want to delete this shortcode?', 'js_composer' ),
			'are_you_sure_delete_param' => esc_html__( "Are you sure you want to delete the shortcode's param?", 'js_composer' ),
			'my_shortcodes_category' => esc_html__( 'My shortcodes', 'js_composer' ),
			'error_shortcode_name_is_required' => esc_html__( 'Shortcode name is required.', 'js_composer' ),
			'error_enter_valid_shortcode_tag' => esc_html__( 'Please enter valid shortcode tag.', 'js_composer' ),
			'error_enter_required_fields' => esc_html__( 'Please enter all required fields for params.', 'js_composer' ),
			'new_shortcode_mapped' => esc_html__( 'New shortcode mapped from string!', 'js_composer' ),
			'shortcode_updated' => esc_html__( 'Shortcode updated!', 'js_composer' ),
			'error_content_param_not_manually' => esc_html__( 'Content param can not be added manually, please use checkbox.', 'js_composer' ),
			'error_param_already_exists' => esc_html__( 'Param %s already exists. Param names must be unique.', 'js_composer' ),
			'error_wrong_param_name' => esc_html__( 'Please use only letters, numbers and underscore for param name', 'js_composer' ),
			'error_enter_valid_shortcode' => esc_html__( 'Please enter valid shortcode to parse!', 'js_composer' ),
			'copied' => esc_html__( 'Copied', 'js_composer' ),
			'license_sync_failed' => esc_html__( 'Failed to sync license. Please try again later.', 'js_composer' ),
		]);

		wp_localize_script( 'wpb_js_composer_settings', 'vcData', apply_filters( 'vc_global_js_data', [
			'version' => WPB_VC_VERSION,
			'debug' => false,
		] ) );
		wp_localize_script( 'wpb_js_composer_settings', 'i18nLocaleSettings', $this->locale );
		$wpb_settings_data = apply_filters( 'vc_get_settings_wpb_data', [] );
		wp_localize_script( 'wpb_js_composer_settings', 'wpbData', $wpb_settings_data );
	}

	/**
	 * Not responsive checkbox callback function
	 */
	public function not_responsive_css_field_callback() {
		$checked = get_option( self::$field_prefix . 'not_responsive_css' );
		if ( empty( $checked ) ) {
			$checked = false;
		}
		?>
		<label>
			<input type="checkbox"<?php echo $checked ? ' checked' : ''; ?> value="1" id="wpb_js_not_responsive_css" name="<?php echo esc_attr( self::$field_prefix . 'not_responsive_css' ); ?>">
			<?php esc_html_e( 'Disable', 'js_composer' ); ?>
		</label>
		<?php
	}

	/**
	 * Modules html settings
	 *
	 * @since 7.7
	 */
	public function use_modules_callback() {
		vc_include_template( 'pages/vc-settings/partials/modules/title.php' );

		$modules_manager = vc_modules_manager();
		$all_modules = $modules_manager->get_all();
		$hidden_value = [];

		foreach ( $all_modules as $module_slug => $module_data ) {
			if ( $modules_manager->get_module_status( $module_slug ) ) {
				$hidden_value[ $module_slug ] = true;
				$module_value = 'checked';
			} else {
				$hidden_value[ $module_slug ] = false;
				$module_value = '';
			}
			vc_include_template(
				'pages/vc-settings/partials/modules/toggle.php',
				[
					'module_data' => $module_data,
					'module_slug' => $module_slug,
					'module_value' => $module_value,
				]
			);
		}

		vc_include_template(
			'pages/vc-settings/partials/modules/hidden-input.php',
			[
				'hidden_value' => $hidden_value,
				'option_name' => $modules_manager->get_option_name(),
			]
		);
	}

	/**
	 * Google fonts subsets callback
	 */
	public function google_fonts_subsets_callback() {
		$pt_array = get_option( self::$field_prefix . 'google_fonts_subsets' );
		$pt_array = $pt_array ? $pt_array : $this->googleFontsSubsets();
		foreach ( $this->getGoogleFontsSubsets() as $pt ) {
			if ( ! in_array( $pt, $this->getGoogleFontsSubsetsExcluded(), true ) ) {
				$checked = ( in_array( $pt, $pt_array, true ) ) ? ' checked' : '';
				?>
				<label>
					<input type="checkbox"<?php echo esc_attr( $checked ); ?> value="<?php echo esc_attr( $pt ); ?>"
						id="wpb_js_gf_subsets_<?php echo esc_attr( $pt ); ?>"
						name="<?php echo esc_attr( self::$field_prefix . 'google_fonts_subsets' ); ?>[]">
					<?php echo esc_html( $pt ); ?>
				</label><br>
				<?php
			}
		}
		?>
		<?php
	}

	/**
	 * Sanitizes the beta version option.
	 *
	 * @param mixed $rules The beta version rules.
	 * @return bool Sanitized beta version status.
	 * @since 8.1
	 */
	public function sanitize_beta_version_callback( $rules ) {
		return (bool) $rules;
	}

	/**
	 * Renders the beta version checkbox in the WordPress dashboard,
	 * under WPBakery -> General Settings.
	 *
	 * @since 8.1
	 */
	public function beta_version_callback() {
		vc_include_template( 'pages/vc-settings/beta-version.php', [
			'checked' => get_option( 'wpb_js_beta_version', false ),
		] );
	}

	/**
	 * Local google fonts callback.
	 */
	public function local_google_fonts_callback() {
		$checked = get_option( self::$field_prefix . 'local_google_fonts' );
		if ( empty( $checked ) ) {
			$checked = false;
		}
		?>
		<label>
			<input type="checkbox"<?php echo $checked ? ' checked' : ''; ?> value="1" id="local_google_fonts" name="<?php echo esc_attr( self::$field_prefix . 'local_google_fonts' ); ?>">
			<?php esc_html_e( 'Enable', 'js_composer' ); ?>
		</label>
		<?php
	}

	/**
	 * Get subsets for google fonts.
	 *
	 * @return array
	 * @since  4.3
	 * @access public
	 */
	public function googleFontsSubsets() {
		if ( ! isset( $this->google_fonts_subsets_settings ) ) {
			$pt_array = vc_settings()->get( 'google_fonts_subsets' );
			$this->google_fonts_subsets_settings = $pt_array ? $pt_array : $this->googleFontsSubsetsDefault();
		}

		return $this->google_fonts_subsets_settings;
	}

	/**
	 * Get default Google Fonts subsets.
	 *
	 * @return array
	 */
	public function googleFontsSubsetsDefault() {
		return $this->google_fonts_subsets_default;
	}

	/**
	 * Get the Google Fonts subsets.
	 *
	 * @return array
	 */
	public function getGoogleFontsSubsets() {
		return $this->google_fonts_subsets;
	}

	/**
	 * Set the Google Fonts subsets.
	 *
	 * @param mixed $subsets
	 *
	 * @return bool
	 */
	public function setGoogleFontsSubsets( $subsets ) {
		if ( is_array( $subsets ) ) {
			$this->google_fonts_subsets = $subsets;

			return true;
		}

		return false;
	}

	/**
	 * Get the excluded Google Fonts subsets.
	 *
	 * @return array
	 */
	public function getGoogleFontsSubsetsExcluded() {
		return $this->google_fonts_subsets_excluded;
	}

	/**
	 * Set the excluded Google Fonts subsets.
	 *
	 * @param mixed $excluded
	 *
	 * @return bool
	 */
	public function setGoogleFontsSubsetsExcluded( $excluded ) {
		if ( is_array( $excluded ) ) {
			$this->google_fonts_subsets_excluded = $excluded;

			return true;
		}

		return false;
	}

	/**
	 * Callback function for settings section
	 *
	 * @param array $tab
	 */
	public function setting_section_callback_function( $tab ) {
		if ( 'wpb_js_composer_settings_color' === $tab['id'] ) {
			echo '<div class="tab_intro">
				<p>' . esc_html__( 'Here you can tweak default WPBakery Page Builder content elements visual appearance. By default WPBakery Page Builder is using neutral light-grey theme. Changing "Main accent color" will affect all content elements if no specific "content block" related color is set.', 'js_composer' ) . '
				</p>
			</div>';
		}
	}

	/**
	 * Sanitize callback for not responsive css.
	 *
	 * @param mixed $rules
	 *
	 * @return bool
	 */
	public function sanitize_not_responsive_css_callback( $rules ) {
		return (bool) $rules;
	}

	/**
	 * Sanitize callback for local google fonts.
	 *
	 * @param mixed $checkbox
	 *
	 * @return bool
	 */
	public function sanitize_local_google_fonts_callback( $checkbox ) {
		return (bool) $checkbox;
	}

	/**
	 * Sanitize callback for modules.
	 *
	 * @param mixed $field
	 *
	 * @since 7.7
	 *
	 * @return mixed
	 */
	public function sanitize_modules_callback( $field ) {
		return $field;
	}

	/**
	 * Sanitize callback for google fonts subsets.
	 *
	 * @param array $subsets
	 *
	 * @return array
	 */
	public function sanitize_google_fonts_subsets_callback( $subsets ) {
		$pt_array = [];
		if ( isset( $subsets ) && is_array( $subsets ) ) {
			foreach ( $subsets as $pt ) {
				if ( ! in_array( $pt, $this->getGoogleFontsSubsetsExcluded(), true ) && in_array( $pt, $this->getGoogleFontsSubsets(), true ) ) {
					$pt_array[] = $pt;
				}
			}
		}

		return $pt_array;
	}

	/**
	 * Rebuilding.
	 */
	public function rebuild() {
		/** WordPress Template Administration API */
		require_once ABSPATH . 'wp-admin/includes/template.php';
		/** WordPress Administration File API */
		require_once ABSPATH . 'wp-admin/includes/file.php';
		delete_option( self::$field_prefix . 'compiled_js_composer_less' );
		$this->initAdmin();
	}

	/**
	 * Build custom css file using css options from vc settings.
	 *
	 * @deprecated 7.7
	 */
	public static function buildCustomColorCss() {
		_deprecated_function( __METHOD__, '7.7', "vc_modules_manager()->get_module('vc-design-options')->settings->build_custom_color_css()" );
		if ( ! vc_modules_manager()->is_module_on( 'vc-design-options' ) ) {
			vc_modules_manager()->turn_on( 'vc-design-options' );
		}
		vc_modules_manager()->get_module( 'vc-design-options' )->settings->build_custom_color_css();
	}

	/**
	 * Builds custom css file using css options from vc settings.
	 *
	 * @deprecated 7.7
	 */
	public static function buildCustomCss() {
		_deprecated_function( __METHOD__, '7.7', "vc_modules_manager()->get_module('vc-custom-css')->settings->build_custom_css()" );
		if ( ! vc_modules_manager()->is_module_on( 'vc-custom-css' ) ) {
			vc_modules_manager()->turn_on( 'vc-custom-css' );
		}
		vc_modules_manager()->get_module( 'vc-custom-css' )->settings->build_custom_css();
	}

	/**
	 * Check create upload dir.
	 *
	 * @param \WP_Filesystem_Direct $wp_filesystem
	 * @param string $option
	 * @param string $filename
	 *
	 * @return bool|string
	 */
	public static function checkCreateUploadDir( $wp_filesystem, $option, $filename ) {
		$js_composer_upload_dir = self::uploadDir();
		if ( ! $wp_filesystem->is_dir( $js_composer_upload_dir ) ) {
			if ( ! $wp_filesystem->mkdir( $js_composer_upload_dir, 0777 ) ) {
				add_settings_error( self::$field_prefix . $option, $wp_filesystem->errors->get_error_code(), sprintf( esc_html__( '%1$s could not be created. Not available to create js_composer directory in uploads directory (%2$s).', 'js_composer' ), $filename, $js_composer_upload_dir ), 'error' );

				return false;
			}
		}

		return $js_composer_upload_dir;
	}

	/**
	 * Upload dir.
	 *
	 * @return string
	 */
	public static function uploadDir() {
		$upload_dir = wp_upload_dir();
		// WP_Filesystem_Direct $wp_filesystem - global variable.
		global $wp_filesystem;

		return $wp_filesystem->find_folder( $upload_dir['basedir'] ) . vc_upload_dir();
	}

	/**
	 * Upload URL.
	 *
	 * @return string
	 */
	public static function uploadURL() {
		$upload_dir = wp_upload_dir();

		return $upload_dir['baseurl'] . vc_upload_dir();
	}


	/**
	 * Get field prefix.
	 *
	 * @return string
	 */
	public static function getFieldPrefix() {
		return self::$field_prefix;
	}

	/**
	 * Get file system.
	 *
	 * @param string $url
	 * @return bool|null
	 */
	public static function getFileSystem( $url = '' ) {
		if ( '' !== $url ) {
			_deprecated_argument( __METHOD__, '7.9', '$url' );
		}
		// WP_Filesystem_Direct $wp_filesystem - global variable.
		global $wp_filesystem;
		$status = true;
		if ( ! $wp_filesystem || ! is_object( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			$status = WP_Filesystem( false, false, true );
		}

		return $status ? $wp_filesystem : false;
	}

	/**
	 * Get option group.
	 *
	 * @return string
	 */
	public function getOptionGroup() {
		return $this->option_group;
	}

	/**
	 * Get page.
	 *
	 * @deprecated 7.7
	 */
	public function useCustomCss() {
		_deprecated_function( __METHOD__, '7.7', "vc_modules_manager()->get_module('vc-design-options')->settings->use_custom_css()" );
		if ( ! vc_modules_manager()->is_module_on( 'vc-design-options' ) ) {
			vc_modules_manager()->turn_on( 'vc-design-options' );
		}
		return vc_modules_manager()->get_module( 'vc-design-options' )->settings->use_custom_css();
	}

	/**
	 * Get page.
	 *
	 * @deprecated 7.7
	 */
	public function getCustomCssVersion() {
		_deprecated_function( __METHOD__, '7.7', "vc_modules_manager()->get_module('vc-design-options')->settings->get_custom_css_version()" );
		if ( ! vc_modules_manager()->is_module_on( 'vc-design-options' ) ) {
			vc_modules_manager()->turn_on( 'vc-design-options' );
		}
		return vc_modules_manager()->get_module( 'vc-design-options' )->settings->get_custom_css_version();
	}

	/**
	 * Get page.
	 *
	 * @param string $key
	 * @deprecated 7.7
	 */
	public function get_default( $key ) {
		_deprecated_function( __METHOD__, '7.7', "vc_modules_manager()->get_module('vc-design-options')->settings->get_default()" );
		if ( ! vc_modules_manager()->is_module_on( 'vc-design-options' ) ) {
			vc_modules_manager()->turn_on( 'vc-design-options' );
		}
		return vc_modules_manager()->get_module( 'vc-design-options' )->settings->get_default( $key );
	}

	/**
	 * Get page.
	 *
	 * @deprecated 7.7
	 */
	public function restoreColor() {
		_deprecated_function( __METHOD__, '7.7', "vc_modules_manager()->get_module('vc-design-options')->settings->restore_color()" );
		if ( ! vc_modules_manager()->is_module_on( 'vc-design-options' ) ) {
			vc_modules_manager()->turn_on( 'vc-design-options' );
		}
		vc_modules_manager()->get_module( 'vc-design-options' )->settings->restore_color();
	}

	/**
	 * We should reset some optionality of other modules when modules option changed.
	 *
	 * @param mixed $old_value
	 * @param string $new_value
	 *
	 * @since 7.7
	 */
	public function reset_modules_dependency( $old_value, $new_value ) {
		$options = json_decode( $new_value, true );

		if ( isset( $options['vc-design-options'] ) && ! $options['vc-design-options'] ) {
			delete_option( self::$field_prefix . 'use_custom' );
		}
	}

	/**
	 * We save users that agree use our beta to CRM beta_testers tag.
	 *
	 * @param string $old_value
	 * @param bool $new_value
	 *
	 * @since 8.4
	 */
	public function subscribe_user_to_beta( $old_value, $new_value ) {
		if ( ! $new_value ) {
			return;
		}

		if ( ! vc_manager()->license()->isActivated() ) {
			return;
		}

		$params = [
			'body' => [ 'key' => vc_manager()->license()->getLicenseKey() ],
			'timeout' => 30,
		];

		wp_remote_post( $this->support_portal_beta_testers_endpoint, $params );
	}
}
