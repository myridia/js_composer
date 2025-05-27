<?php
/**
 * Class that handles specific [vc_copyright] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_copyright.php
 *
 * @since 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Copyright
 *
 * @since 8.0
 */
class WPBakeryShortCode_Vc_Copyright extends WPBakeryShortCode {
	/**
	 * Template variables.
	 *
	 * @var array
	 * @since 8.0
	 */
	protected $template_vars = [];

	/**
	 * Build templates vars where we are keeping element optionality
	 *
	 * @since 8.0
	 *
	 * @param array $atts
	 * @throws Exception
	 */
	public function buildTemplate( $atts ) {
		$output = [];
		$inline_css = [];

		$output['css-class'] = $this->get_element_wrapper_classes( 'wpb-copyright', $atts );

		if ( isset( $atts['style'] ) && 'custom' === $atts['style'] ) {
			if ( ! empty( $atts['custom_background'] ) ) {
				$inline_css[] = vc_get_css_color( 'background-color', $atts['custom_background'] );
			}
		}

		$output['inline-css'] = $inline_css;

		$this->template_vars = $output;
	}

	/**
	 * Get list of elements classes that we attach to class wrapper tag.
	 * Usually we attach there classes related to animation, default element class and etc
	 *
	 * @since 8.0
	 *
	 * @param string $element_class
	 * @param array $atts
	 *
	 * @return array
	 */
	public function get_element_wrapper_classes( $element_class, $atts ) {
		$main_wrapper_classes = [ $element_class ];
		if ( ! empty( $atts['el_class'] ) ) {
			$main_wrapper_classes[] = $atts['el_class'];
		}

		if ( ! empty( $atts['css_animation'] ) ) {
			$main_wrapper_classes[] = $this->getCSSAnimation( $atts['css_animation'] );
		}

		if ( ! empty( $atts['css'] ) ) {
			$main_wrapper_classes[] = vc_shortcode_custom_css_class( $atts['css'] );
		}

		if ( ! empty( $this->settings['element_default_class'] ) ) {
			$main_wrapper_classes[] = $this->settings['element_default_class'];
		}

		return $main_wrapper_classes;
	}

	/**
	 * Get certain template variable value.
	 *
	 * @since 8.0
	 *
	 * @param string $var_name
	 * @return mixed|string
	 */
	public function getTemplateVariable( $var_name ) {
		if ( is_array( $this->template_vars ) && isset( $this->template_vars[ $var_name ] ) ) {
			return $this->template_vars[ $var_name ];
		}

		return '';
	}
}
