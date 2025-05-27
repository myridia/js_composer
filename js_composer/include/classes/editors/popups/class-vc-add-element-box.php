<?php
/**
 * Add element for VC editors with a list of mapped shortcodes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Add_Element_Box
 *
 * @since 4.3
 */
class Vc_Add_Element_Box {
	/**
	 * Enable show empty message
	 *
	 * @since 4.8
	 * @var bool
	 */
	protected $show_empty_message = false;

	/**
	 * Retrieves the icon HTML based on the given parameters.
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	protected function getIcon( $params ) {
		$data = $this->get_data_container( $params );
		$data = $data ? ' ' . $data : '';
		$icon = '';

		if ( ! empty( $params['icon'] ) ) {
			if ( ! filter_var( $params['icon'], FILTER_VALIDATE_URL ) ) {
				$icon = ' ' . sanitize_text_field( $params['icon'] );
			}
		}

		$payload = [
			'icon' => $icon,
			'data' => $data,
		];

		return vc_get_template( 'editors/popups/partials/add_element_render_icon.php', $payload );
	}

	/**
	 * Single button html template
	 *
	 * @param mixed $params
	 *
	 * @return string
	 */
	public function renderButton( $params ) {
		if ( ! is_array( $params ) || empty( $params ) ) {
			return '';
		}
		$class = $class_out = '';
		if ( ! empty( $params['class'] ) ) {
			$class_ar = $class_at_out = explode( ' ', $params['class'] );
			$count = count( $class_ar );
			for ( $n = 0; $n < $count; $n++ ) {
				$class_ar[ $n ] .= '_nav';
				$class_at_out[ $n ] .= '_o';
			}
			$class = implode( ' ', $class_ar );
			$class = $class ? ' ' . $class : '';
			$class_out = implode( ' ', $class_at_out );
			$class_out = $class_out ? ' ' . $class_out : '';
		}

		$data_atts = $this->get_data_container( $params );
		$data_atts = $data_atts ? $data_atts . ' ' : '';
		$data_atts .= isset( $params['presetId'] ) ? 'data-preset="' . esc_attr( $params['presetId'] ) . '" ' : '';
		$data_atts .= 'data-element="' . esc_attr( $params['base'] ) . '" data-vc-ui-element="add-element-button"';

		$payload = [
			'params' => $params,
			'data_atts' => $data_atts,
			'class' => $class,
			'class_out' => $class_out,
			'category_css_classes' => $this->get_category_id_class( $params ),
			'icon' => $this->getIcon( $params ),
			'deprecated' => isset( $params['deprecated'] ) ? ' vc_element-deprecated' : '',
		];

		return vc_get_template( 'editors/popups/partials/add_element_render_button.php', $payload );
	}

	/**
	 * Get category id class
	 *
	 * @param mixed $params
	 * @since 8.4
	 * @return string
	 */
	public function get_category_id_class( $params ) {
		$category_css_classes = '';
		if ( ! isset( $params['_category_ids'] ) ) {
			return $category_css_classes;
		}
		foreach ( $params['_category_ids'] as $id ) {
			$category_css_classes .= ' js-category-' . $id;
		}
		return $category_css_classes ? ' ' . $category_css_classes : '';
	}

	/**
	 * Get data container
	 *
	 * @since 8.4
	 * @param mixed $params
	 * @return string
	 */
	public function get_data_container( $params ) {
		$data = '';
		if ( isset( $params['is_container'] ) && true === $params['is_container'] ) {
			$data = 'data-is-container="true"';
		}
		return $data;
	}

	/**
	 * Get mapped shortcodes list.
	 *
	 * @return array
	 * @throws \Exception
	 * @since 4.4
	 */
	public function shortcodes() {
		return apply_filters( 'vc_add_new_elements_to_box', WPBMap::getSortedUserShortCodes() );
	}

	/**
	 * Render 6 most used elements from the wpb_usage_count option, and the usage count should be 10 or more.
	 *
	 * @param array $shortcodes list of shorcodes avalable in current editor.
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getMostUsedElements( $shortcodes ) {
		$usage_count = get_option( 'wpb_usage_count', [] );
		$most_used_elements = [];
		if ( ! empty( $usage_count ) ) {
			arsort( $usage_count );
			$most_used_elements = array_slice( $usage_count, 0, 6 );
		}
		$most_used_elements = array_filter( $most_used_elements, function ( $count ) {
			return $count >= 10;
		} );
		$most_used_elements = array_keys( $most_used_elements );
		$most_used_elements = array_filter( $most_used_elements, function ( $element ) use ( $shortcodes ) {
			if ( ! in_array( $element, $shortcodes ) ) {
				return false;
			}
			return WPBMap::getShortCode( $element );
		} );
		$most_used_elements = array_slice( $most_used_elements, 0, 6 );
		return $most_used_elements;
	}

	/**
	 * Render list of buttons for each mapped and allowed VC shortcodes.
	 *
	 * @see vc_filter: vc_add_element_box_buttons - hook to override output of getControls method
	 * @return mixed
	 * @throws \Exception
	 * @see WPBMap::getSortedUserShortCodes
	 */
	public function getControls() {
		$output = '<div class="vc-panel-no-results-message">' . __( 'No elements found', 'js_composer' ) . '</div>';
		$shortcodes = $this->shortcodes();
		$most_used_elements = $this->getMostUsedElements( array_column( $shortcodes, 'base' ) );
		if ( ! empty( $most_used_elements ) ) {
			$output .= '<div class="vc_clearfix"><h4>' . esc_html__( 'Most used', 'js_composer' ) . '</h4>';
			$output .= '<ul class="wpb-content-layouts" style="margin-bottom: 20px">';
			foreach ( $most_used_elements as $element ) {
				$button = $this->renderButton( WPBMap::getShortCode( $element ) );
				if ( ! empty( $button ) ) {
					$output .= $button;
				}
			}
			$output .= '</ul></div>';
		}

		if ( ! empty( $most_used_elements ) ) {
			$output .= '<div class="vc_clearfix"><h4>' . esc_html__( 'All elements', 'js_composer' ) . '</h4>';
		}
		$output .= '<ul class="wpb-content-layouts">';
		$buttons_count = 0;
		foreach ( $shortcodes as $element ) {
			if ( isset( $element['content_element'] ) && false === $element['content_element'] ) {
				continue;
			}
			$button = $this->renderButton( $element );
			if ( ! empty( $button ) ) {
				$buttons_count++;
			}
			$output .= $button;
		}
		$output .= '</ul>';
		if ( ! empty( $most_used_elements ) ) {
			$output .= '</div>';
		}
		if ( 0 === $buttons_count ) {
			$this->show_empty_message = true;
		}

		return apply_filters( 'vc_add_element_box_buttons', $output );
	}

	/**
	 * Get categories list from mapping data.
	 *
	 * @return array
	 * @throws \Exception
	 * @since 4.5
	 */
	public function getCategories() {
		return apply_filters( 'vc_add_new_category_filter', WPBMap::getUserCategories() );
	}

	/**
	 * Renders the add element panel template.
	 */
	public function render() {
		vc_include_template( 'editors/popups/vc_ui-panel-add-element.tpl.php', [
			'box' => $this,
			'header_tabs_template_variables' => [
				'categories' => $this->getCategories(),
			],
		] );
	}

	/**
	 * Render icon for shortcode
	 *
	 * @param array $params
	 *
	 * @return string
	 * @since 4.8
	 */
	public function renderIcon( $params ) {
		return $this->getIcon( $params );
	}

	/**
	 * Checks if the empty message should be shown.
	 *
	 * @return boolean
	 */
	public function isShowEmptyMessage() {
		return $this->show_empty_message;
	}

	/**
	 * Retrieves the state of the part related to shortcodes.
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function getPartState() {
		return vc_user_access()->part( 'shortcodes' )->getState();
	}

	/**
	 * Get modal popup template tabs.
	 *
	 * @param array $categories
	 *
	 * @since 8.1
	 * @return array
	 */
	public function get_tabs( $categories ) {
		$other_tab = false;

		$tabs = [
			[
				'name' => esc_html__( 'All', 'js_composer' ),
				'active' => true,
				'filter' => '*',
			],
		];

		foreach ( $categories as $key => $name ) {
			if ( '_other_category_' === $name ) {
				$other_tab = [
					'name' => esc_html__( 'Other', 'js_composer' ),
					'filter' => '.js-category-' . $key,
					'active' => false,
				];
				continue;
			}

			if ( 'deprecated' === $name ) {
				$name = esc_html__( 'Deprecated', 'js_composer' );
				$filter = '.js-category-deprecated';
			} elseif ( '_my_elements_' === $name ) {
				$name = esc_html__( 'My Elements', 'js_composer' );
				$filter = '.js-category-_my_elements_';
			} else {
				$filter = '.js-category-' . md5( $name );
			}

			$tabs[] = [
				'name' => $name,
				'filter' => $filter,
				'active' => false,
			];
		}

		if ( $other_tab ) {
			$tabs[] = $other_tab;
		}

		return $tabs;
	}
}
