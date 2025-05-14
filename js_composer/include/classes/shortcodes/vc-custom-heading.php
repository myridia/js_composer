<?php
/**
 * Class that handles specific [vc_custom_heading] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_custom_heading.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Custom_heading
 *
 * @since 4.3
 */
class WPBakeryShortCode_Vc_Custom_Heading extends WPBakeryShortCode {
	/**
	 * Defines fields names for google_fonts, font_container and etc
	 *
	 * @since 4.4
	 * @var array
	 */
	protected $fields = [
		'google_fonts' => 'google_fonts',
		'font_container' => 'font_container',
		'el_class' => 'el_class',
		'css' => 'css',
		'text' => 'text',
	];

	/**
	 * Used to get field name in vc_map function for google_fonts, font_container and etc..
	 *
	 * @param string $key
	 *
	 * @return bool
	 * @since 4.4
	 */
	protected function getField( $key ) {
		return isset( $this->fields[ $key ] ) ? $this->fields[ $key ] : false;
	}

	/**
	 * Get param value by providing key
	 *
	 * @param string $key
	 *
	 * @return array|bool
	 * @throws \Exception
	 * @since 4.4
	 */
	protected function getParamData( $key ) {
		return WPBMap::getParam( $this->shortcode, $this->getField( $key ) );
	}

	/**
	 * Parses shortcode attributes and set defaults based on vc_map function relative to shortcode and fields names
	 *
	 * @param array $atts
	 *
	 * @return array
	 * @throws \Exception
	 * @since 4.3
	 */
	public function getAttributes( $atts ) {
		/**
		 * Shortcode attributes
		 *
		 * @var $text
		 * @var $google_fonts
		 * @var $font_container
		 * @var $el_class
		 * @var $link
		 * @var $css
		 */
		$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
		extract( $atts );

		/**
		 * Get default values from VC_MAP.
		 */
		$google_fonts_field = $this->getParamData( 'google_fonts' );
		$font_container_field = $this->getParamData( 'font_container' );

		$el_class = $this->getExtraClass( $el_class );
		$font_container_obj = new Vc_Font_Container();
		$google_fonts_obj = new Vc_Google_Fonts();
		$font_container_field_settings = isset( $font_container_field['settings'], $font_container_field['settings']['fields'] ) ? $font_container_field['settings']['fields'] : [];
		$google_fonts_field_settings = isset( $google_fonts_field['settings'], $google_fonts_field['settings']['fields'] ) ? $google_fonts_field['settings']['fields'] : [];
		$font_container_data = $font_container_obj->_vc_font_container_parse_attributes( $font_container_field_settings, $font_container );
		$google_fonts_data = strlen( $google_fonts ) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes( $google_fonts_field_settings, $google_fonts ) : '';

		return [
			'text' => isset( $text ) ? $text : '',
			'google_fonts' => $google_fonts,
			'font_container' => $font_container,
			'el_class' => $el_class,
			'css' => isset( $css ) ? $css : '',
			'link' => ( 0 === strpos( $link, '|' ) ) ? false : $link,
			'font_container_data' => $font_container_data,
			'google_fonts_data' => $google_fonts_data,
		];
	}

	/**
	 * Enqueue element styles related to fonts.
	 *
	 * @param array $fonts_data element shortcode attributes.
	 * @param array $atts element shortcode attributes.
	 *
	 * @since 8.0
	 */
	public function enqueue_element_font_styles( $fonts_data, $atts = [] ) {
		if ( isset( $atts['use_theme_fonts'] ) && 'yes' === $atts['use_theme_fonts'] ) {
			return;
		}

		if ( empty( $fonts_data ) || ! isset( $fonts_data['values']['font_family'] ) ) {
			return;
		}

		$settings = get_option( 'wpb_js_google_fonts_subsets' );
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			$subsets = '&subset=' . implode( ',', $settings );
		} else {
			$subsets = '';
		}

		if ( empty( $fonts_data['values']['font_vendor'] ) ) {
			wp_enqueue_style(
				'vc_google_fonts_' . vc_build_safe_css_class( $fonts_data['values']['font_family'] ),
				'https://fonts.googleapis.com/css?family=' . $fonts_data['values']['font_family'] . $subsets,
				[],
				WPB_VC_VERSION
			);
		}

		do_action( 'wpb_after_enqueue_element_google_fonts', $fonts_data );
	}


	/**
	 * Parses google_fonts_data and font_container_data to get needed css styles to markup
	 *
	 * @param string $el_class
	 * @param string $css
	 * @param array $google_fonts_data
	 * @param array $font_container_data
	 * @param array $atts
	 *
	 * @return array
	 * @since 4.3
	 */
	public function getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) {
		$styles = [];
		if ( ! empty( $font_container_data ) && isset( $font_container_data['values'] ) ) {
			foreach ( $font_container_data['values'] as $key => $value ) {
				if ( 'tag' !== $key && strlen( $value ) ) {
					if ( preg_match( '/description/', $key ) ) {
						continue;
					}
					if ( 'font_size' === $key ) {
						$value = wpb_format_with_css_unit( $value );
					} elseif ( 'line_height' === $key ) {
						$value = preg_replace( '/\s+/', '', $value );
					}
					if ( strlen( $value ) > 0 ) {
						$styles[] = str_replace( '_', '-', $key ) . ': ' . $value;
					}
				}
			}
		}
		if ( ( ! isset( $atts['use_theme_fonts'] ) || 'yes' !== $atts['use_theme_fonts'] ) && ! empty( $google_fonts_data ) && isset( $google_fonts_data['values'], $google_fonts_data['values']['font_family'], $google_fonts_data['values']['font_style'] ) ) {
			$google_fonts_family = explode( ':', $google_fonts_data['values']['font_family'] );
			$styles[] = 'font-family:' . $google_fonts_family[0];
			$google_fonts_styles = explode( ':', $google_fonts_data['values']['font_style'] );
			$styles[] = 'font-weight:' . $google_fonts_styles[1];
			$styles[] = 'font-style:' . $google_fonts_styles[2];
		}

		/**
		 * Filter 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' to change vc_custom_heading class
		 *
		 * @param string - filter_name
		 * @param string - element_class
		 * @param string - shortcode_name
		 * @param array - shortcode_attributes
		 *
		 * @since 4.3
		 */
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_custom_heading ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

		return [
			'css_class' => trim( preg_replace( '/\s+/', ' ', $css_class ) ),
			'styles' => $styles,
		];
	}
}
