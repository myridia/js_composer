<?php
/**
 * Class that handles specific [vc_tab] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_tab.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

define( 'TAB_TITLE', esc_attr__( 'Tab', 'js_composer' ) );
require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-column.php' );

/**
 * Class WPBakeryShortCode_Vc_Tab
 */
class WPBakeryShortCode_Vc_Tab extends WPBakeryShortCode_Vc_Column {
	/**
	 * Controls container css classes.
	 *
	 * @var string
	 */
	protected $controls_css_settings = 'tc vc_control-container';

	/**
	 * Controls list.
	 *
	 * @var array
	 */
	protected $controls_list = [
		'add',
		'edit',
		'clone',
		'copy',
		'delete',
	];

	/**
	 * Controls template file.
	 *
	 * @var string
	 */
	protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

	/**
	 * Set custom admin block params.
	 *
	 * @return string
	 */
	public function customAdminBlockParams() {
		return ' id="tab-' . $this->atts['tab_id'] . '"';
	}

	/**
	 * Add main html block attributes.
	 *
	 * @param string $width
	 * @param int $i
	 * @return string
	 * @throws \Exception
	 */
	public function mainHtmlBlockParams( $width, $i ) {
		$sortable = ( vc_user_access_check_shortcode_all( $this->shortcode ) ? 'wpb_sortable' : $this->nonDraggableClass );

		return 'data-element_type="' . $this->settings['base'] . '" class="wpb_' . $this->settings['base'] . ' ' . $sortable . ' wpb_content_holder"' . $this->customAdminBlockParams();
	}

	/**
	 * Add container classes.
	 *
	 * @param string $width
	 * @param int $i
	 * @return string
	 */
	public function containerHtmlBlockParams( $width, $i ) {
		return 'class="wpb_column_container vc_container_for_children"';
	}

	/**
	 * Get column controls.
	 *
	 * @param string $controls
	 * @param string $extended_css
	 * @return string
	 * @throws \Exception
	 */
	public function getColumnControls( $controls, $extended_css = '' ) {
		return $this->getColumnControlsModular( $extended_css );
	}
}

/**
 * Add tab settings filed.
 *
 * @param array $settings
 * @param string $value
 *
 * @return string
 * @since 4.4
 */
function vc_tab_id_settings_field( $settings, $value ) {
	return sprintf( '<div class="vc_tab_id_block"><input name="%s" class="wpb_vc_param_value wpb-textinput %s %s_field" type="hidden" value="%s" /><label>%s</label></div>', $settings['param_name'], $settings['param_name'], $settings['type'], $value, $value );
}

vc_add_shortcode_param( 'tab_id', 'vc_tab_id_settings_field' );
