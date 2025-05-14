<?php
/**
 * Class that handles specific [vc_tta_tour] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_tta_tour.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_Vc_Tta_Tabs' );

/**
 * Class WPBakeryShortCode_Vc_Tta_Tour
 */
class WPBakeryShortCode_Vc_Tta_Tour extends WPBakeryShortCode_Vc_Tta_Tabs {

	/**
	 * Layout type.
	 *
	 * @var string
	 */
	public $layout = 'tabs';

	/**
	 * Add specific tta classes.
	 *
	 * @return string
	 */
	public function getTtaGeneralClasses() {
		$classes = parent::getTtaGeneralClasses();

		if ( isset( $this->atts['controls_size'] ) ) {
			$classes .= ' ' . $this->getTemplateVariable( 'controls_size' );
		}

		return $classes;
	}

	/**
	 * Add size attributes to element.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamControlsSize( $atts, $content ) {
		if ( isset( $atts['controls_size'] ) && strlen( $atts['controls_size'] ) > 0 ) {
			return 'vc_tta-controls-size-' . $atts['controls_size'];
		}

		return null;
	}

	/**
	 * Add size attribute with left position to element.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamTabsListLeft( $atts, $content ) {
		if ( empty( $atts['tab_position'] ) || 'left' !== $atts['tab_position'] ) {
			return null;
		}

		return $this->getParamTabsList( $atts, $content );
	}

	/**
	 * Add size attribute with right position to element.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamTabsListRight( $atts, $content ) {
		if ( empty( $atts['tab_position'] ) || 'right' !== $atts['tab_position'] ) {
			return null;
		}

		return $this->getParamTabsList( $atts, $content );
	}

	/**
	 * Never on top
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamPaginationTop( $atts, $content ) {
		return null;
	}

	/**
	 * Always on bottom
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamPaginationBottom( $atts, $content ) {
		return $this->getParamPaginationList( $atts, $content );
	}
}
