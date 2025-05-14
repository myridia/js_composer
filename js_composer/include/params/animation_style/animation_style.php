<?php
/**
 * Param type 'animation_style'
 *
 * Used to create dropdown field with animation styles.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/#vc_map()-ParametersofparamsArray
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_ParamAnimation
 *
 * For working with animations
 * array(
 *        'type' => 'animation_style',
 *        'heading' => esc_html__( 'Animation', 'js_composer' ),
 *        'param_name' => 'animation',
 * ),
 * Preview in https://daneden.github.io/animate.css/
 *
 * @since 4.4
 */
class Vc_ParamAnimation {
	/**
	 * Parameter settings from vc_map.
	 *
	 * @since 4.4
	 * @var array $settings
	 */
	protected $settings;
	/**
	 * Parameter value.
	 *
	 * @since 4.4
	 * @var string $value
	 */
	protected $value;

	/**
	 * Define available animation effects.
	 *
	 * @since 4.4
	 * @see vc_filter: vc_param_animation_style_list - to override animation styles array.
	 * @return array
	 */
	protected function animationStyles() {
		$styles = [
			[
				'values' => [
					esc_html__( 'None', 'js_composer' ) => 'none',
				],
			],
			[
				'label' => esc_html__( 'Attention Seekers', 'js_composer' ),
				'values' => [
					// text to display => value.
					esc_html__( 'bounce', 'js_composer' ) => [
						'value' => 'bounce',
						'type' => 'other',
					],
					esc_html__( 'flash', 'js_composer' ) => [
						'value' => 'flash',
						'type' => 'other',
					],
					esc_html__( 'pulse', 'js_composer' ) => [
						'value' => 'pulse',
						'type' => 'other',
					],
					esc_html__( 'rubberBand', 'js_composer' ) => [
						'value' => 'rubberBand',
						'type' => 'other',
					],
					esc_html__( 'shake', 'js_composer' ) => [
						'value' => 'shake',
						'type' => 'other',
					],
					esc_html__( 'swing', 'js_composer' ) => [
						'value' => 'swing',
						'type' => 'other',
					],
					esc_html__( 'tada', 'js_composer' ) => [
						'value' => 'tada',
						'type' => 'other',
					],
					esc_html__( 'wobble', 'js_composer' ) => [
						'value' => 'wobble',
						'type' => 'other',
					],
				],
			],
			[
				'label' => esc_html__( 'Bouncing Entrances', 'js_composer' ),
				'values' => [
					// text to display => value.
					esc_html__( 'bounceIn', 'js_composer' ) => [
						'value' => 'bounceIn',
						'type' => 'in',
					],
					esc_html__( 'bounceInDown', 'js_composer' ) => [
						'value' => 'bounceInDown',
						'type' => 'in',
					],
					esc_html__( 'bounceInLeft', 'js_composer' ) => [
						'value' => 'bounceInLeft',
						'type' => 'in',
					],
					esc_html__( 'bounceInRight', 'js_composer' ) => [
						'value' => 'bounceInRight',
						'type' => 'in',
					],
					esc_html__( 'bounceInUp', 'js_composer' ) => [
						'value' => 'bounceInUp',
						'type' => 'in',
					],
				],
			],
			[
				'label' => esc_html__( 'Bouncing Exits', 'js_composer' ),
				'values' => [
					// text to display => value.
					esc_html__( 'bounceOut', 'js_composer' ) => [
						'value' => 'bounceOut',
						'type' => 'out',
					],
					esc_html__( 'bounceOutDown', 'js_composer' ) => [
						'value' => 'bounceOutDown',
						'type' => 'out',
					],
					esc_html__( 'bounceOutLeft', 'js_composer' ) => [
						'value' => 'bounceOutLeft',
						'type' => 'out',
					],
					esc_html__( 'bounceOutRight', 'js_composer' ) => [
						'value' => 'bounceOutRight',
						'type' => 'out',
					],
					esc_html__( 'bounceOutUp', 'js_composer' ) => [
						'value' => 'bounceOutUp',
						'type' => 'out',
					],
				],
			],
			[
				'label' => esc_html__( 'Fading Entrances', 'js_composer' ),
				'values' => [
					// text to display => value.
					esc_html__( 'fadeIn', 'js_composer' ) => [
						'value' => 'fadeIn',
						'type' => 'in',
					],
					esc_html__( 'fadeInDown', 'js_composer' ) => [
						'value' => 'fadeInDown',
						'type' => 'in',
					],
					esc_html__( 'fadeInDownBig', 'js_composer' ) => [
						'value' => 'fadeInDownBig',
						'type' => 'in',
					],
					esc_html__( 'fadeInLeft', 'js_composer' ) => [
						'value' => 'fadeInLeft',
						'type' => 'in',
					],
					esc_html__( 'fadeInLeftBig', 'js_composer' ) => [
						'value' => 'fadeInLeftBig',
						'type' => 'in',
					],
					esc_html__( 'fadeInRight', 'js_composer' ) => [
						'value' => 'fadeInRight',
						'type' => 'in',
					],
					esc_html__( 'fadeInRightBig', 'js_composer' ) => [
						'value' => 'fadeInRightBig',
						'type' => 'in',
					],
					esc_html__( 'fadeInUp', 'js_composer' ) => [
						'value' => 'fadeInUp',
						'type' => 'in',
					],
					esc_html__( 'fadeInUpBig', 'js_composer' ) => [
						'value' => 'fadeInUpBig',
						'type' => 'in',
					],
				],
			],
			[
				'label' => esc_html__( 'Fading Exits', 'js_composer' ),
				'values' => [
					esc_html__( 'fadeOut', 'js_composer' ) => [
						'value' => 'fadeOut',
						'type' => 'out',
					],
					esc_html__( 'fadeOutDown', 'js_composer' ) => [
						'value' => 'fadeOutDown',
						'type' => 'out',
					],
					esc_html__( 'fadeOutDownBig', 'js_composer' ) => [
						'value' => 'fadeOutDownBig',
						'type' => 'out',
					],
					esc_html__( 'fadeOutLeft', 'js_composer' ) => [
						'value' => 'fadeOutLeft',
						'type' => 'out',
					],
					esc_html__( 'fadeOutLeftBig', 'js_composer' ) => [
						'value' => 'fadeOutLeftBig',
						'type' => 'out',
					],
					esc_html__( 'fadeOutRight', 'js_composer' ) => [
						'value' => 'fadeOutRight',
						'type' => 'out',
					],
					esc_html__( 'fadeOutRightBig', 'js_composer' ) => [
						'value' => 'fadeOutRightBig',
						'type' => 'out',
					],
					esc_html__( 'fadeOutUp', 'js_composer' ) => [
						'value' => 'fadeOutUp',
						'type' => 'out',
					],
					esc_html__( 'fadeOutUpBig', 'js_composer' ) => [
						'value' => 'fadeOutUpBig',
						'type' => 'out',
					],
				],
			],
			[
				'label' => esc_html__( 'Flippers', 'js_composer' ),
				'values' => [
					esc_html__( 'flip', 'js_composer' ) => [
						'value' => 'flip',
						'type' => 'other',
					],
					esc_html__( 'flipInX', 'js_composer' ) => [
						'value' => 'flipInX',
						'type' => 'in',
					],
					esc_html__( 'flipInY', 'js_composer' ) => [
						'value' => 'flipInY',
						'type' => 'in',
					],
					esc_html__( 'flipOutX', 'js_composer' ) => [
						'value' => 'flipOutX',
						'type' => 'out',
					],
					esc_html__( 'flipOutY', 'js_composer' ) => [
						'value' => 'flipOutY',
						'type' => 'out',
					],
				],
			],
			[
				'label' => esc_html__( 'Lightspeed', 'js_composer' ),
				'values' => [
					esc_html__( 'lightSpeedIn', 'js_composer' ) => [
						'value' => 'lightSpeedIn',
						'type' => 'in',
					],
					esc_html__( 'lightSpeedOut', 'js_composer' ) => [
						'value' => 'lightSpeedOut',
						'type' => 'out',
					],
				],
			],
			[
				'label' => esc_html__( 'Rotating Entrances', 'js_composer' ),
				'values' => [
					esc_html__( 'rotateIn', 'js_composer' ) => [
						'value' => 'rotateIn',
						'type' => 'in',
					],
					esc_html__( 'rotateInDownLeft', 'js_composer' ) => [
						'value' => 'rotateInDownLeft',
						'type' => 'in',
					],
					esc_html__( 'rotateInDownRight', 'js_composer' ) => [
						'value' => 'rotateInDownRight',
						'type' => 'in',
					],
					esc_html__( 'rotateInUpLeft', 'js_composer' ) => [
						'value' => 'rotateInUpLeft',
						'type' => 'in',
					],
					esc_html__( 'rotateInUpRight', 'js_composer' ) => [
						'value' => 'rotateInUpRight',
						'type' => 'in',
					],
				],
			],
			[
				'label' => esc_html__( 'Rotating Exits', 'js_composer' ),
				'values' => [
					esc_html__( 'rotateOut', 'js_composer' ) => [
						'value' => 'rotateOut',
						'type' => 'out',
					],
					esc_html__( 'rotateOutDownLeft', 'js_composer' ) => [
						'value' => 'rotateOutDownLeft',
						'type' => 'out',
					],
					esc_html__( 'rotateOutDownRight', 'js_composer' ) => [
						'value' => 'rotateOutDownRight',
						'type' => 'out',
					],
					esc_html__( 'rotateOutUpLeft', 'js_composer' ) => [
						'value' => 'rotateOutUpLeft',
						'type' => 'out',
					],
					esc_html__( 'rotateOutUpRight', 'js_composer' ) => [
						'value' => 'rotateOutUpRight',
						'type' => 'out',
					],
				],
			],
			[
				'label' => esc_html__( 'Specials', 'js_composer' ),
				'values' => [
					esc_html__( 'hinge', 'js_composer' ) => [
						'value' => 'hinge',
						'type' => 'out',
					],
					esc_html__( 'rollIn', 'js_composer' ) => [
						'value' => 'rollIn',
						'type' => 'in',
					],
					esc_html__( 'rollOut', 'js_composer' ) => [
						'value' => 'rollOut',
						'type' => 'out',
					],
				],
			],
			[
				'label' => esc_html__( 'Zoom Entrances', 'js_composer' ),
				'values' => [
					esc_html__( 'zoomIn', 'js_composer' ) => [
						'value' => 'zoomIn',
						'type' => 'in',
					],
					esc_html__( 'zoomInDown', 'js_composer' ) => [
						'value' => 'zoomInDown',
						'type' => 'in',
					],
					esc_html__( 'zoomInLeft', 'js_composer' ) => [
						'value' => 'zoomInLeft',
						'type' => 'in',
					],
					esc_html__( 'zoomInRight', 'js_composer' ) => [
						'value' => 'zoomInRight',
						'type' => 'in',
					],
					esc_html__( 'zoomInUp', 'js_composer' ) => [
						'value' => 'zoomInUp',
						'type' => 'in',
					],
				],
			],
			[
				'label' => esc_html__( 'Zoom Exits', 'js_composer' ),
				'values' => [
					esc_html__( 'zoomOut', 'js_composer' ) => [
						'value' => 'zoomOut',
						'type' => 'out',
					],
					esc_html__( 'zoomOutDown', 'js_composer' ) => [
						'value' => 'zoomOutDown',
						'type' => 'out',
					],
					esc_html__( 'zoomOutLeft', 'js_composer' ) => [
						'value' => 'zoomOutLeft',
						'type' => 'out',
					],
					esc_html__( 'zoomOutRight', 'js_composer' ) => [
						'value' => 'zoomOutRight',
						'type' => 'out',
					],
					esc_html__( 'zoomOutUp', 'js_composer' ) => [
						'value' => 'zoomOutUp',
						'type' => 'out',
					],
				],
			],
			[
				'label' => esc_html__( 'Slide Entrances', 'js_composer' ),
				'values' => [
					esc_html__( 'slideInDown', 'js_composer' ) => [
						'value' => 'slideInDown',
						'type' => 'in',
					],
					esc_html__( 'slideInLeft', 'js_composer' ) => [
						'value' => 'slideInLeft',
						'type' => 'in',
					],
					esc_html__( 'slideInRight', 'js_composer' ) => [
						'value' => 'slideInRight',
						'type' => 'in',
					],
					esc_html__( 'slideInUp', 'js_composer' ) => [
						'value' => 'slideInUp',
						'type' => 'in',
					],
				],
			],
			[
				'label' => esc_html__( 'Slide Exits', 'js_composer' ),
				'values' => [
					esc_html__( 'slideOutDown', 'js_composer' ) => [
						'value' => 'slideOutDown',
						'type' => 'out',
					],
					esc_html__( 'slideOutLeft', 'js_composer' ) => [
						'value' => 'slideOutLeft',
						'type' => 'out',
					],
					esc_html__( 'slideOutRight', 'js_composer' ) => [
						'value' => 'slideOutRight',
						'type' => 'out',
					],
					esc_html__( 'slideOutUp', 'js_composer' ) => [
						'value' => 'slideOutUp',
						'type' => 'out',
					],
				],
			],
		];

		/**
		 * Used to override animation style list
		 *
		 * @since 4.4
		 */

		return apply_filters( 'vc_param_animation_style_list', $styles );
	}

	/**
	 * Group styles by type.
	 *
	 * @param array $styles - array of styles to group.
	 * @param string|array $type - what type to return.
	 *
	 * @return array
	 * @since 4.4
	 */
	public function groupStyleByType( $styles, $type ) {
		$grouped = [];
		foreach ( $styles as $group ) {
			$inner_group = [ 'values' => [] ];
			if ( isset( $group['label'] ) ) {
				$inner_group['label'] = $group['label'];
			}
			foreach ( $group['values'] as $key => $value ) {
				if ( ( is_array( $value ) && isset( $value['type'] ) && ( ( is_string( $type ) && $value['type'] === $type ) || is_array( $type ) && in_array( $value['type'], $type, true ) ) ) || ! is_array( $value ) || ! isset( $value['type'] ) ) {
					$inner_group['values'][ $key ] = $value;
				}
			}
			if ( ! empty( $inner_group['values'] ) ) {
				$grouped[] = $inner_group;
			}
		}

		return $grouped;
	}

	/**
	 * Set variables and register animate-css asset.
	 *
	 * @param array $settings
	 * @param string $value
	 * @since 4.4
	 */
	public function __construct( $settings, $value ) {
		$this->settings = $settings;
		$this->value = $value;
		wp_register_style( 'vc_animate-css', vc_asset_url( 'lib/vendor/node_modules/animate.css/animate.min.css' ), [], WPB_VC_VERSION );
	}

	/**
	 * Render edit form output.
	 *
	 * @return string
	 * @since 4.4
	 */
	public function render() {
		$output = '<div class="vc_row">';
		wp_enqueue_style( 'vc_animate-css' );

		$styles = $this->animationStyles();
		if ( isset( $this->settings['settings']['type'] ) ) {
			$styles = $this->groupStyleByType( $styles, $this->settings['settings']['type'] );
		}
		if ( isset( $this->settings['settings']['custom'] ) && is_array( $this->settings['settings']['custom'] ) ) {
			$styles = array_merge( $styles, $this->settings['settings']['custom'] );
		}

		if ( is_array( $styles ) && ! empty( $styles ) ) {
			$left_side = '<div class="vc_col-sm-6">';
			$build_style_select = '<select class="vc_param-animation-style">';
			foreach ( $styles as $style ) {
				$build_style_select .= '<optgroup ' . ( isset( $style['label'] ) ? 'label="' . esc_attr( $style['label'] ) . '"' : '' ) . '>';
				if ( is_array( $style['values'] ) && ! empty( $style['values'] ) ) {
					foreach ( $style['values'] as $key => $value ) {
						$selected = '';
						$option_value = is_array( $value ) ? $value['value'] : $value;
						if ( $option_value === $this->value ) {
							$selected = 'selected="selected"';
						}
						$build_style_select .= '<option value="' . ( $option_value ) . '" ' . $selected . '>' . esc_html( $key ) . '</option>';
					}
				}
				$build_style_select .= '</optgroup>';
			}
			$build_style_select .= '</select>';
			$left_side .= $build_style_select;
			$left_side .= '</div>';
			$output .= $left_side;

			$right_side = '<div class="vc_col-sm-6">';
			$right_side .= '<div class="vc_param-animation-style-preview"><button class="vc_btn-grey vc_general vc_param-animation-style-trigger vc_ui-button vc_ui-button-shape-rounded">' . esc_html__( 'Animate it', 'js_composer' ) . '</button></div>';
			$right_side .= '</div>';
			$output .= $right_side;
		}

		$output .= '</div>'; // Close Row.
		$output .= sprintf( '<input name="%s" class="wpb_vc_param_value  %s %s_field" type="hidden" value="%s"  />', esc_attr( $this->settings['param_name'] ), esc_attr( $this->settings['param_name'] ), esc_attr( $this->settings['type'] ), $this->value );

		return $output;
	}
}

/**
 * Function for rendering param in edit form (add element)
 * Parse settings from vc_map and entered 'values'.
 *
 * @param array $settings - parameter settings in vc_map.
 * @param string $value - parameter value.
 * @param string $tag - shortcode tag.
 *
 * @see vc_filter: vc_animation_style_render_filter - filter to override editor form
 *     field output
 *
 * @return mixed rendered template for params in edit form
 *
 * @since 4.4
 */
function vc_animation_style_form_field( $settings, $value, $tag ) {

	$field = new Vc_ParamAnimation( $settings, $value );

	/**
	 * Filter used to override full output of edit form field animation style
	 *
	 * @since 4.4
	 */

	return apply_filters( 'vc_animation_style_render_filter', $field->render(), $settings, $value, $tag );
}
