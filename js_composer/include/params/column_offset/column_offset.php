<?php
/**
 * Param type "column_offset".
 *
 * Used to create dropdown for width responsiveness
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/#vc_map()-ParametersofparamsArray
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Vc_Column_Offset class.
 */
class Vc_Column_Offset {
	/**
	 * The settings for the column offset, passed to the constructor.
	 *
	 * @var array
	 */
	protected $settings = [];

	/**
	 * The value associated with the column offset.
	 *
	 * @var string
	 */
	protected $value = '';

	/**
	 * The available size types for the column offset.
	 *
	 * @var array
	 */
	protected $size_types = [
		'lg' => 'Large',
		'md' => 'Medium',
		'sm' => 'Small',
		'xs' => 'Extra small',
	];

	/**
	 * A list of possible column widths.
	 *
	 * @var array
	 */
	protected $column_width_list = [];

	/**
	 * Parsed data from the $value attribute.
	 *
	 * @var array|mixed
	 */
	protected $data = [];

	/**
	 * Vc_Column_Offset constructor.
	 *
	 * @param array $settings
	 * @param string $value
	 */
	public function __construct( $settings, $value ) {
		$this->settings = $settings;
		$this->value = $value;

		$this->column_width_list = [
			esc_html__( '1/12 - 1 column', 'js_composer' ) => '1',
			esc_html__( '1/6 - 2 columns', 'js_composer' ) => '2',
			esc_html__( '1/4 - 3 columns', 'js_composer' ) => '3',
			esc_html__( '1/3 - 4 columns', 'js_composer' ) => '4',
			esc_html__( '5/12 - 5 columns', 'js_composer' ) => '5',
			esc_html__( '1/2 - 6 columns', 'js_composer' ) => '6',
			esc_html__( '7/12 - 7 columns', 'js_composer' ) => '7',
			esc_html__( '2/3 - 8 columns', 'js_composer' ) => '8',
			esc_html__( '3/4 - 9 columns', 'js_composer' ) => '9',
			esc_html__( '5/6 - 10 columns', 'js_composer' ) => '10',
			esc_html__( '11/12 - 11 columns', 'js_composer' ) => '11',
			esc_html__( '1/1 - 12 columns', 'js_composer' ) => '12',
			esc_html__( '1/5 - 20%', 'js_composer' ) => '1/5',
			esc_html__( '2/5 - 40%', 'js_composer' ) => '2/5',
			esc_html__( '3/5 - 60%', 'js_composer' ) => '3/5',
			esc_html__( '4/5 - 80%', 'js_composer' ) => '4/5',
		];
	}

	/**
	 * Render the column offset param.
	 *
	 * @return string
	 */
	public function render() {
		ob_start();
		vc_include_template( 'params/column_offset/template.tpl.php', [
			'settings' => $this->settings,
			'value' => $this->value,
			'data' => $this->valueData(),
			'sizes' => $this->size_types,
			'param' => $this,
		] );

		return ob_get_clean();
	}

	/**
	 * Parses and returns the data associated with the value.
	 *
	 * @return array|mixed
	 */
	public function valueData() {
		if ( empty( $this->data ) ) {
			$this->data = ! empty( $this->value ) ? preg_split( '/\s+/', $this->value ) : [];
		}

		return $this->data;
	}

	/**
	 * Generates the HTML select element for size control.
	 *
	 * @param string $size
	 *
	 * @return string
	 */
	public function sizeControl( $size ) {
		if ( 'sm' === $size ) {
			return '<span class="vc_description">' . esc_html__( 'Default value from width attribute', 'js_composer' ) . '</span>';
		}
		$empty_label = 'xs' === $size ? 'Default' : esc_html__( 'Inherit from smaller', 'js_composer' );
		$output = sprintf( '<select name="vc_col_%s_size" class="vc_column_offset_field" data-type="size-%s"><option value="" style="color: #ccc;">%s</option>', $size, $size, $empty_label );
		foreach ( $this->column_width_list as $label => $index ) {
			$value = 'vc_col-' . $size . '-' . $index;
			$output .= sprintf( '<option value="%s" %s>%s</option>', $value, in_array( $value, $this->data, true ) ? 'selected="true"' : '', $label );
		}
		$output .= '</select>';

		return $output;
	}

	/**
	 * Generates the HTML select element for offset control.
	 *
	 * @param string $size
	 *
	 * @return string
	 */
	public function offsetControl( $size ) {
		$prefix = 'vc_col-' . $size . '-offset-';
		$empty_label = 'xs' === $size ? esc_html__( 'No offset', 'js_composer' ) : esc_html__( 'Inherit from smaller', 'js_composer' );
		$output = sprintf( '<select name="vc_%s_offset_size" class="vc_column_offset_field" data-type="offset-%s"><option value="" style="color: #ccc;">%s</option>', $size, $size, $empty_label );

		if ( 'xs' !== $size ) {
			$output .= sprintf( '<option value="%s0" style="color: #ccc;"%s>%s</option>', $prefix, in_array( $prefix . '0', $this->data, true ) ? ' selected="true"' : '', esc_html__( 'No offset', 'js_composer' ) );
		}

		foreach ( $this->column_width_list as $label => $index ) {
			$value = $prefix . $index;
			$output .= sprintf( '<option value="%s"%s>%s</option>', $value, in_array( $value, $this->data, true ) ? ' selected="true"' : '', $label );
		}
		$output .= '</select>';

		return $output;
	}
}

/**
 * Renders the form field for column offset settings.
 *
 * @param array $settings
 * @param string $value
 *
 * @return string
 */
function vc_column_offset_form_field( $settings, $value ) {
	$column_offset = new Vc_Column_Offset( $settings, $value );

	return $column_offset->render();
}

/**
 * Merges the column offset class with the column width class.
 *
 * @param string $column_offset
 * @param string $width
 *
 * @return string
 */
function vc_column_offset_class_merge( $column_offset, $width ) {
	// Remove offset settings if.
	if ( '1' === vc_settings()->get( 'not_responsive_css' ) ) {
		$column_offset = preg_replace( '/vc_col\-(lg|md|xs)[^\s]*/', '', $column_offset );
	}
	if ( preg_match( '/vc_col\-sm\-\d+/', $column_offset ) ) {
		return $column_offset;
	}

	return $width . ( empty( $column_offset ) ? '' : ' ' . $column_offset );
}

/**
 * Registers the column offset parameter with Visual Composer.
 */
function vc_load_column_offset_param() {
	vc_add_shortcode_param( 'column_offset', 'vc_column_offset_form_field' );
}

add_action( 'vc_load_default_params', 'vc_load_column_offset_param' );
