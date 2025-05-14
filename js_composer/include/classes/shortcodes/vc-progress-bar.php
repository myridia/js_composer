<?php
/**
 * Class that handles specific [vc_progress_bar] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_progress_bar.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Progress_Bar
 */
class WPBakeryShortCode_Vc_Progress_Bar extends WPBakeryShortCode {
	/**
	 * Convert attributes to new progress bar.
	 *
	 * @param array $atts
	 * @return mixed
	 */
	public static function convertAttributesToNewProgressBar( $atts ) {
		if ( isset( $atts['values'] ) && strlen( $atts['values'] ) > 0 ) {
			$values = vc_param_group_parse_atts( $atts['values'] );
			if ( ! is_array( $values ) ) {
				$temp = explode( ',', $atts['values'] );
				$param_values = [];
				foreach ( $temp as $value ) {
					$data = explode( '|', $value );
					$color_index = 2;
					$new_line = [];
					$new_line['value'] = isset( $data[0] ) ? $data[0] : 0;
					$new_line['label'] = isset( $data[1] ) ? $data[1] : '';
					if ( isset( $data[1] ) && preg_match( '/^\d{1,3}\%$/', $data[1] ) ) {
						$color_index++;
						$new_line['value'] = (float) str_replace( '%', '', $data[1] );
						$new_line['label'] = isset( $data[2] ) ? $data[2] : '';
					}
					if ( isset( $data[ $color_index ] ) ) {
						$new_line['customcolor'] = $data[ $color_index ];
					}
					$param_values[] = $new_line;
				}
				$atts['values'] = rawurlencode( wp_json_encode( $param_values ) );
			}
		}

		return $atts;
	}
}
