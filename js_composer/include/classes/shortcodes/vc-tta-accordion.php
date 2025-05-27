<?php
/**
 * Class that handles specific [vc_tta_accordion] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_tta_accordion.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Tta_Accordion
 */
class WPBakeryShortCode_Vc_Tta_Accordion extends WPBakeryShortCodesContainer {
	/**
	 * CSS settings for controls.
	 *
	 * @var string
	 */
	protected $controls_css_settings = 'out-tc vc_controls-content-widget';

	/**
	 * List of controls available.
	 *
	 * @var array
	 */
	protected $controls_list = [
		'add',
		'edit',
		'clone',
		'copy',
		'paste',
		'delete',
	];

	/**
	 * Template variables.
	 *
	 * @var array
	 */
	protected $template_vars = [];

	/**
	 * Layout type.
	 *
	 * @var string
	 */
	public $layout = 'accordion';

	/**
	 * Content of the accordion.
	 *
	 * @var mixed
	 */
	protected $content;

	/**
	 * Active class name.
	 *
	 * @var string
	 */
	public $activeClass = 'vc_active';

	/**
	 * Section class instance.
	 *
	 * @var WPBakeryShortCode_Vc_Tta_Section
	 */
	protected $sectionClass;

	/**
	 * Non-draggable class name.
	 *
	 * @var string
	 */
	public $nonDraggableClass = 'vc-non-draggable-container';

	/**
	 * Get name.
	 *
	 * @return mixed|string
	 */
	public function getFileName() {
		return 'vc_tta_global';
	}

	/**
	 * Get container content class.
	 *
	 * @return string
	 */
	public function containerContentClass() {
		return 'vc_container_for_children vc_clearfix';
	}

	/**
	 * Reset var values.
	 *
	 * @param array $atts
	 * @param string $content
	 */
	public function resetVariables( $atts, $content ) {
		$this->atts = $atts;
		$this->content = $content;
		$this->template_vars = [];
	}

	/**
	 * Set tta info data.
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function setGlobalTtaInfo() {
		$sectionClass = wpbakery()->getShortCode( 'vc_tta_section' )->shortcodeClass();
		$this->sectionClass = $sectionClass;

		// WPBakeryShortCode_Vc_Tta_Section $sectionClass - instance of section class.
		if ( is_object( $sectionClass ) ) {
			VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_Vc_Tta_Section' );
			WPBakeryShortCode_Vc_Tta_Section::$tta_base_shortcode = $this;
			WPBakeryShortCode_Vc_Tta_Section::$self_count = 0;
			WPBakeryShortCode_Vc_Tta_Section::$section_info = [];

			return true;
		}

		return false;
	}

	/**
	 * Override default getColumnControls to make it "simple"(blue), as single element has
	 *
	 * @param string $controls
	 * @param string $extended_css
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function getColumnControls( $controls = 'full', $extended_css = '' ) {
		// we don't need containers bottom-controls for tabs.
		if ( 'bottom-controls' === $extended_css ) {
			return '';
		}
		$column_controls = $this->getColumnControlsModular();

		return $output = $column_controls;
	}

	/**
	 * Get tta container classes.
	 *
	 * @return string
	 */
	public function getTtaContainerClasses() {
		$classes = [];
		$classes[] = 'vc_tta-container';

		return implode( ' ', apply_filters( 'vc_tta_container_classes', array_filter( $classes ), $this->getAtts() ) );
	}

	/**
	 * Add specific tta classes.
	 *
	 * @return string
	 */
	public function getTtaGeneralClasses() {
		$classes = [];
		$classes[] = 'vc_general';
		$classes[] = 'vc_tta';
		$classes[] = 'vc_tta-' . $this->layout;
		$classes[] = $this->getTemplateVariable( 'color' );
		$classes[] = $this->getTemplateVariable( 'style' );
		$classes[] = $this->getTemplateVariable( 'shape' );
		$classes[] = $this->getTemplateVariable( 'spacing' );
		$classes[] = $this->getTemplateVariable( 'gap' );
		$classes[] = $this->getTemplateVariable( 'c_align' );
		$classes[] = $this->getTemplateVariable( 'no_fill' );
		if ( isset( $this->atts['collapsible_all'] ) && 'true' === $this->atts['collapsible_all'] ) {
			$classes[] = 'vc_tta-o-all-clickable';
		}

		$pagination = isset( $this->atts['pagination_style'] ) ? trim( $this->atts['pagination_style'] ) : false;
		if ( $pagination && 'none' !== $pagination && strlen( $pagination ) > 0 ) {
			$classes[] = 'vc_tta-has-pagination';
		}

		// since 4.6.2.
		if ( isset( $this->atts['el_class'] ) ) {
			$classes[] = $this->getExtraClass( $this->atts['el_class'] );
		}

		return implode( ' ', apply_filters( 'vc_tta_accordion_general_classes', array_filter( $classes ), $this->getAtts() ) );
	}

	/**
	 * Retrieve tta pagination classes.
	 *
	 * @return string
	 */
	public function getTtaPaginationClasses() {
		$classes = [];
		$classes[] = 'vc_general';
		$classes[] = 'vc_pagination';

		if ( isset( $this->atts['pagination_style'] ) && strlen( $this->atts['pagination_style'] ) > 0 ) {
			$chunks = explode( '-', $this->atts['pagination_style'] );
			$classes[] = 'vc_pagination-style-' . $chunks[0];
			$classes[] = 'vc_pagination-shape-' . $chunks[1];
		}

		if ( isset( $this->atts['pagination_color'] ) && strlen( $this->atts['pagination_color'] ) > 0 ) {
			$classes[] = 'vc_pagination-color-' . $this->atts['pagination_color'];
		}

		return implode( ' ', $classes );
	}

	/**
	 * Get element wrapper attributes.
	 *
	 * @return string
	 */
	public function getWrapperAttributes() {
		$attributes = [];
		$attributes[] = 'class="' . esc_attr( $this->getTtaContainerClasses() ) . '"';
		$attributes[] = 'data-vc-action="' . ( 'true' === $this->atts['collapsible_all'] ? 'collapseAll' : 'collapse' ) . '"';

		$autoplay = isset( $this->atts['autoplay'] ) ? trim( $this->atts['autoplay'] ) : false;
		if ( $autoplay && 'none' !== $autoplay && intval( $autoplay ) > 0 ) {
			$autoplayAttr = wp_json_encode( [
				'delay' => intval( $autoplay ) * 1000,
			] );
			$attributes[] = 'data-vc-tta-autoplay="' . esc_attr( $autoplayAttr ) . '"';
		}
		if ( ! empty( $this->atts['el_id'] ) ) {
			$attributes[] = 'id="' . esc_attr( $this->atts['el_id'] ) . '"';
		}

		return implode( ' ', $attributes );
	}

	/**
	 * Get element template variables.
	 *
	 * @param string $initial
	 * @return mixed|string
	 */
	public function getTemplateVariable( $initial ) {
		if ( isset( $this->template_vars[ $initial ] ) ) {
			return $this->template_vars[ $initial ];
		} elseif ( method_exists( $this, 'getParam' . vc_studly( $initial ) ) ) {
			$this->template_vars[ $initial ] = $this->{'getParam' . vc_studly( $initial )}( $this->atts, $this->content );

			return $this->template_vars[ $initial ];
		}

		return '';
	}

	/**
	 * Get optional param color class.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamColor( $atts, $content ) {
		if ( isset( $atts['color'] ) && strlen( $atts['color'] ) > 0 ) {
			return 'vc_tta-color-' . esc_attr( $atts['color'] );
		}

		return null;
	}

	/**
	 * Get optional param style class.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamStyle( $atts, $content ) {
		if ( isset( $atts['style'] ) && strlen( $atts['style'] ) > 0 ) {
			return 'vc_tta-style-' . esc_attr( $atts['style'] );
		}

		return null;
	}

	/**
	 * Get element title html.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamTitle( $atts, $content ) {
		if ( isset( $atts['title'] ) && strlen( $atts['title'] ) > 0 ) {
			$tag = 'h2';
			if ( isset( $atts['title_tag'] ) ) {
				$tag = $atts['title_tag'];
			}

			return '<' . $tag . '>' . esc_html( $atts['title'] ) . '</' . $tag . '>';
		}

		return null;
	}

	/**
	 * Get element icon html.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamContent( $atts, $content ) {
		$panelsContent = wpb_js_remove_wpautop( $content );
		if ( isset( $atts['c_icon'] ) && strlen( $atts['c_icon'] ) > 0 ) {
			$isPageEditable = vc_is_page_editable();
			if ( ! $isPageEditable ) {
				$panelsContent = str_replace( '{{{ control-icon }}}', '<i class="vc_tta-controls-icon vc_tta-controls-icon-' . $atts['c_icon'] . '"></i>', $panelsContent );
			} else {
				$panelsContent = str_replace( '{{{ control-icon }}}', '<i class="vc_tta-controls-icon" data-vc-tta-controls-icon="' . $atts['c_icon'] . '"></i>', $panelsContent );
			}
		} else {
			$panelsContent = str_replace( '{{{ control-icon }}}', '', $panelsContent );
		}

		return $panelsContent;
	}

	/**
	 * Get optional param shape class.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamShape( $atts, $content ) {
		if ( isset( $atts['shape'] ) && strlen( $atts['shape'] ) > 0 ) {
			return 'vc_tta-shape-' . $atts['shape'];
		}

		return null;
	}

	/**
	 * Get optional param spacing class.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function getParamSpacing( $atts, $content ) {
		if ( isset( $atts['spacing'] ) && strlen( $atts['spacing'] ) > 0 ) {
			return 'vc_tta-spacing-' . $atts['spacing'];
		}

		// In case if no spacing set we need to append extra class.
		return 'vc_tta-o-shape-group';
	}

	/**
	 * Get optional param gap class.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamGap( $atts, $content ) {
		if ( isset( $atts['gap'] ) && strlen( $atts['gap'] ) > 0 ) {
			return 'vc_tta-gap-' . $atts['gap'];
		}

		return null;
	}

	/**
	 * Get optional param no fill class.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamNoFill( $atts, $content ) {
		if ( isset( $atts['no_fill'] ) && 'true' === $atts['no_fill'] ) {
			return 'vc_tta-o-no-fill';
		}

		return null;
	}

	/**
	 * Get optional param align class.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamCAlign( $atts, $content ) {
		if ( isset( $atts['c_align'] ) && strlen( $atts['c_align'] ) > 0 ) {
			return 'vc_tta-controls-align-' . $atts['c_align'];
		}

		return null;
	}

	/**
	 * Accordion doesn't have pagination
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return null
	 */
	public function getParamPaginationTop( $atts, $content ) {
		return null;
	}

	/**
	 * Accordion doesn't have pagination
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return null
	 */
	public function getParamPaginationBottom( $atts, $content ) {
		return null;
	}

	/**
	 * Get currently active section (from $atts)
	 *
	 * @param array $atts
	 * @param bool $strict_bounds If true, check for min/max bounds.
	 *
	 * @return int nth position (one-based) of active section
	 */
	public function getActiveSection( $atts, $strict_bounds = false ) {
		$active_section = intval( $atts['active_section'] );

		if ( $strict_bounds ) {
			VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_Vc_Tta_Section' );
			if ( $active_section < 1 ) {
				$active_section = 1;
			} elseif ( $active_section > WPBakeryShortCode_Vc_Tta_Section::$self_count ) {
				$active_section = WPBakeryShortCode_Vc_Tta_Section::$self_count;
			}
		}

		return $active_section;
	}

	/**
	 * Get pagination list html.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function getParamPaginationList( $atts, $content ) {
		if ( empty( $atts['pagination_style'] ) ) {
			return null;
		}

		$html = [];
		$html[] = vc_get_template( 'partials/tta-pagination-start.php', [
			'classes' => $this->getTtaPaginationClasses(),
		] );

		if ( ! vc_is_page_editable() ) {
			VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_Vc_Tta_Section' );
			foreach ( WPBakeryShortCode_Vc_Tta_Section::$section_info as $nth => $section ) {
				$active_section = $this->getActiveSection( $atts );

				$classes = [ 'vc_pagination-item' ];
				$current = $nth + 1;
				if ( $current === $active_section ) {
					$classes[] = $this->activeClass;
				}

				$html[] = vc_get_template( 'partials/tta-pagination-item.php', [
					'classes' => implode( ' ', $classes ),
					'section' => $section,
					'current' => $current,
				] );
			}
		}

		$html[] = vc_get_template( 'partials/tta-pagination-end.php' );

		return implode( '', $html );
	}

	/**
	 * Enqueue element specific styles.
	 */
	public function enqueueTtaStyles() {
		wp_register_style( 'vc_tta_style', vc_asset_url( 'css/js_composer_tta.min.css' ), false, WPB_VC_VERSION );
		wp_enqueue_style( 'vc_tta_style' );
	}

	/**
	 * Enqueue element specific scripts.
	 */
	public function enqueueTtaScript() {
		wp_register_script( 'vc_accordion_script', vc_asset_url( 'lib/vc/vc_accordion/vc-accordion.min.js' ), [ 'jquery-core' ], WPB_VC_VERSION, true );
		wp_register_script( 'vc_tta_autoplay_script', vc_asset_url( 'lib/vc/vc-tta-autoplay/vc-tta-autoplay.min.js' ), [ 'vc_accordion_script' ], WPB_VC_VERSION, true );

		wp_enqueue_script( 'vc_accordion_script' );
		if ( ! vc_is_page_editable() ) {
			wp_enqueue_script( 'vc_tta_autoplay_script' );
		}
	}

	/**
	 * Override default outputTitle (also Icon). To remove anything, also Icon.
	 *
	 * @param string $title - just for strict standards.
	 *
	 * @return string
	 */
	protected function outputTitle( $title ) {
		return '';
	}

	/**
	 * Check is allowed to add another element inside current element.
	 *
	 * @return bool
	 * @throws \Exception
	 * @since 4.8
	 */
	public function getAddAllowed() {
		return vc_user_access_check_shortcode_all( 'vc_tta_section' );
	}
}
