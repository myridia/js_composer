<?php
/**
 * Class that handles specific [vc_accordion_tab] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_accordion_tab.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-tab.php' );

/**
 * Class WPBakeryShortCode_VC_Accordion_tab
 */
class WPBakeryShortCode_VC_Accordion_Tab extends WPBakeryShortCode_VC_Tab {
	/**
	 * Controls css settings.
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
	 * Class for non draggable container.
	 *
	 * @var string
	 */
	public $nonDraggableClass = 'vc-non-draggable-container';

	/**
	 * Get admin output.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function contentAdmin( $atts, $content = null ) {
		$width = '';
		// @codingStandardsIgnoreLine
		extract( vc_map_get_attributes( $this->getShortcode(), $atts ) );
		$output = '';

		$column_controls = $this->getColumnControls( $this->settings( 'controls' ) );
		$column_controls_bottom = $this->getColumnControls( 'add', 'bottom-controls' );

		if ( 'column_14' === $width || '1/4' === $width ) {
			$width = [ 'vc_col-sm-3' ];
		} elseif ( 'column_14-14-14-14' === $width ) {
			$width = [
				'vc_col-sm-3',
				'vc_col-sm-3',
				'vc_col-sm-3',
				'vc_col-sm-3',
			];
		} elseif ( 'column_13' === $width || '1/3' === $width ) {
			$width = [ 'vc_col-sm-4' ];
		} elseif ( 'column_13-23' === $width ) {
			$width = [
				'vc_col-sm-4',
				'vc_col-sm-8',
			];
		} elseif ( 'column_13-13-13' === $width ) {
			$width = [
				'vc_col-sm-4',
				'vc_col-sm-4',
				'vc_col-sm-4',
			];
		} elseif ( 'column_12' === $width || '1/2' === $width ) {
			$width = [ 'vc_col-sm-6' ];
		} elseif ( 'column_12-12' === $width ) {
			$width = [
				'vc_col-sm-6',
				'vc_col-sm-6',
			];
		} elseif ( 'column_23' === $width || '2/3' === $width ) {
			$width = [ 'vc_col-sm-8' ];
		} elseif ( 'column_34' === $width || '3/4' === $width ) {
			$width = [ 'vc_col-sm-9' ];
		} elseif ( 'column_16' === $width || '1/6' === $width ) {
			$width = [ 'vc_col-sm-2' ];
		} else {
			$width = [ '' ];
		}
		$sortable = ( vc_user_access_check_shortcode_all( $this->shortcode ) ? 'wpb_sortable' : $this->nonDraggableClass );

		$count = count( $width );
		for ( $i = 0; $i < $count; $i++ ) {
			$output .= '<div class="group ' . $sortable . '">';
			$output .= '<h3><span class="tab-label"><%= params.title %></span></h3>';
			$output .= '<div ' . $this->mainHtmlBlockParams( $width, $i ) . '>';
			$output .= str_replace( '%column_size%', wpb_translateColumnWidthToFractional( $width[ $i ] ), $column_controls );
			$output .= '<div class="wpb_element_wrapper">';
			$output .= '<div ' . $this->containerHtmlBlockParams( $width, $i ) . '>';
			$output .= do_shortcode( shortcode_unautop( $content ) );
			$output .= '</div>';
			if ( isset( $this->settings['params'] ) ) {
				$inner = '';
				foreach ( $this->settings['params'] as $param ) {
					$param_value = isset( ${$param['param_name']} ) ? ${$param['param_name']} : '';
					if ( is_array( $param_value ) ) {
						// Get first element from the array.
						reset( $param_value );
						$first_key = key( $param_value );
						$param_value = $param_value[ $first_key ];
					}
					$inner .= $this->singleParamHtmlHolder( $param, $param_value );
				}
				$output .= $inner;
			}
			$output .= '</div>';
			$output .= str_replace( '%column_size%', wpb_translateColumnWidthToFractional( $width[ $i ] ), $column_controls_bottom );
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * Get main html block params.
	 *
	 * @param string $width
	 * @param int $i
	 * @return string
	 */
	public function mainHtmlBlockParams( $width, $i ) {
		return 'data-element_type="' . esc_attr( $this->settings['base'] ) . '" class=" wpb_' . esc_attr( $this->settings['base'] ) . '"' . $this->customAdminBlockParams();
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
	 * Get title.
	 *
	 * @param string $title
	 * @return string
	 */
	protected function outputTitle( $title ) {
		return '';
	}

	/**
	 * Set custom admin block params.
	 *
	 * @return string
	 */
	public function customAdminBlockParams() {
		return '';
	}
}
