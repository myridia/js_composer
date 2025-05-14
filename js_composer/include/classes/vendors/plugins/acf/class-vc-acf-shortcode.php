<?php
/**
 * Backward compatibility with "Advanced custom fields" WordPress plugin.
 *
 * @see https://wordpress.org/plugins/advanced-custom-fields/
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'VENDORS_DIR', 'plugins/acf/class-wpb-acf-provider.php' );

/**
 * Class WPBakeryShortCode_Vc_Acf
 */
class WPBakeryShortCode_Vc_Acf extends WPBakeryShortCode {

	/**
	 * Provider instance.
	 *
	 * @var Wpb_Acf_Provider
	 * @since 8.1
	 */
	public $provider;

	/**
	 * Constructor.
	 *
	 * @param array $settings
	 * @since 8.1
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->provider = new Wpb_Acf_Provider();
	}

	/**
	 * Content rendering function.
	 *
	 * @param array $atts
	 * @param null $content
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function content( $atts, $content = null ) {
		$atts = $atts + vc_map_get_attributes( $this->getShortcode(), $atts );

		$field_group = $atts['field_group'];
		$field_key = '';
		if ( 0 === strlen( $atts['field_group'] ) ) {
			$groups = function_exists( 'acf_get_field_groups' ) ? acf_get_field_groups() : apply_filters( 'acf/get_field_groups', [] );
			if ( is_array( $groups ) && isset( $groups[0] ) ) {
				$key = isset( $groups[0]['id'] ) ? 'id' : ( isset( $groups[0]['ID'] ) ? 'ID' : 'id' );
				$field_group = $groups[0][ $key ];
			}
		}
		if ( $field_group ) {
			$field_key = ! empty( $atts[ 'field_from_' . $field_group ] ) ? $atts[ 'field_from_' . $field_group ] : 'field_from_group_' . $field_group;
		}

		$css_class = [];
		$css_class[] = 'vc_acf';
		if ( $atts['el_class'] ) {
			$css_class[] = $atts['el_class'];
		}
		if ( $atts['align'] ) {
			$css_class[] = 'vc_txt_align_' . $atts['align'];
		}

		$value = '';
		$show_empty_acf = apply_filters( 'wpb_shortcode_acf_display_when_empty_value', false );

		if ( $field_key ) {
			$css_class[] = $field_key;

			$value = $this->provider->get_field_value( $field_key );

			if ( $atts['show_label'] ) {
				if ( empty( $value ) && ! $show_empty_acf ) {
					$value = '';
				} else {
					$field = get_field_object( $field_key );
					$label = is_array( $field ) && isset( $field['label'] ) ? '<span class="vc_acf-label">' . $field['label'] . ':</span> ' : '';
					$value = $label . $value;
				}
			} elseif ( empty( $value ) && ! $show_empty_acf ) {
				$value = '';
			}
		}

		$css_string = implode( ' ', $css_class );

		$output = '';
		if ( ! empty( $value ) ) {
			$output = '<div class="' . esc_attr( $css_string ) . '">' . $value . '</div>';
		}

		return $output;
	}
}
