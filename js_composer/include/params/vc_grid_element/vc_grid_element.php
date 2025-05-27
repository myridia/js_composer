<?php
/**
 * Param type 'vc_grid_element'.
 *
 * Specific param type for vc_grid_element that we use for our grid builder.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/#vc_map()-ParametersofparamsArray
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Grid_Element
 */
class Vc_Grid_Element {
	/**
	 * The template string used for rendering.
	 *
	 * @var string
	 */
	protected $template = '';

	/**
	 * The HTML version of the template after processing shortcodes.
	 *
	 * @var bool|string
	 */
	protected $html_template = false;

	/**
	 * The post object associated with the grid element.
	 *
	 * @var bool|WP_Post
	 */
	protected $post = false;

	/**
	 * Array of attributes for the grid element.
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * Array of grid attributes.
	 *
	 * @var array
	 */
	protected $grid_atts = [];

	/**
	 * Indicates whether the grid element is the last in the row.
	 *
	 * @var bool
	 */
	protected $is_end = false;

	/**
	 * Indicates whether templates have been added.
	 *
	 * @var bool
	 */
	protected static $templates_added = false;

	/**
	 * Array of shortcode tags associated with the grid element.
	 *
	 * @var array
	 */
	protected $shortcodes = [
		'vc_gitem_row',
		'vc_gitem_col',
		'vc_gitem_post_title',
		'vc_gitem_icon',
	];

	/**
	 * Gets the list of shortcodes associated with the grid element.
	 *
	 * @return array
	 */
	public function shortcodes() {
		return $this->shortcodes;
	}

	/**
	 * Sets the template string and parses it.
	 *
	 * @param string $template
	 */
	public function setTemplate( $template ) {
		$this->template = $template;
		$this->parseTemplate( $template );
	}

	/**
	 * Gets the current template string.
	 *
	 * @return string
	 */
	public function template() {
		return $this->template;
	}

	/**
	 * Parses the given template string and processes shortcodes.
	 *
	 * @param string $template
	 */
	public function parseTemplate( $template ) {
		$this->setShortcodes();
		$this->html_template = do_shortcode( $template );
	}

	/**
	 * Renders an HTML item for a given post.
	 *
	 * @param \WP_Post $post
	 * @return string
	 */
	public function renderItem( WP_Post $post ) {
		$attributes = $this->attributes();
		$pattern = [];
		$replacement = [];
		foreach ( $attributes as $attr ) {
			$pattern[] = '/\{\{' . preg_quote( $attr, '' ) . '\}\}/';
			$replacement[] = $this->attribute( $attr, $post );
		}
		$css_class_items = 'vc_grid-item ' . ( $this->isEnd() ? ' vc_grid-last-item ' : '' ) . ' vc_grid-thumb vc_theme-thumb-full-overlay vc_animation-slide-left vc_col-sm-' . $this->gridAttribute( 'element_width', 12 );
		foreach ( $post->filter_terms as $t ) {
			$css_class_items .= ' vc_grid-term-' . $t;
		}

		return '<div class="' . $css_class_items . '">' . "\n" . preg_replace( $pattern, $replacement, $this->html_template ) . "\n" . '</div>' . "\n";
	}

	/**
	 * Renders the parameter for the grid element.
	 *
	 * @return string
	 */
	public function renderParam() {
		$output = '<div class="vc_grid-element-constructor" data-vc-grid-element="builder"></div><a href="#" data-vc-control="add-row">' . esc_html__( 'Add row', 'js_composer' ) . '</a>';
		if ( false === self::$templates_added ) {
			foreach ( $this->shortcodes as $tag ) {
				$method = vc_camel_case( $tag . '_template' );
				if ( method_exists( $this, $method ) ) {
					$content = $this->$method();
				} else {
					$content = $this->vcDefaultTemplate( $tag );
				}
				$custom_tag = 'script';
				$output .= '<' . $custom_tag . ' type="text/template" data-vc-grid-element-template="' . esc_attr( $tag ) . '">' . $content . '</' . $custom_tag . '>';
				$output .= '<' . $custom_tag . ' type="text/template" data-vc-grid-element-template="modal"><div class="vc_grid-element-modal-title"><# title #></div><div class="vc_grid-element-modal-controls"><# controls #></div><div class="vc_grid-element-modal-body"><# body #></div></' . $custom_tag . '>';
			}
			self::$templates_added = true;
		}

		return $output;
	}

	/**
	 * Sets the grid attributes.
	 *
	 * @param array $grid_atts
	 */
	public function setGridAttributes( $grid_atts ) {
		$this->grid_atts = $grid_atts;
	}

	/**
	 * Gets a specific grid attribute by name.
	 *
	 * @param string $name
	 * @param string $initial
	 * @return mixed|string
	 */
	public function gridAttribute( $name, $initial = '' ) {
		return isset( $this->grid_atts[ $name ] ) ? $this->grid_atts[ $name ] : $initial;
	}

	/**
	 * Adds an attribute to the list of attributes.
	 *
	 * @param string $name
	 */
	public function setAttribute( $name ) {
		$this->attributes[] = $name;
	}

	/**
	 * Gets the list of attributes.
	 *
	 * @return array
	 */
	public function attributes() {
		return $this->attributes;
	}

	/**
	 * Retrieves the value of a specific attribute for a given post.
	 *
	 * @param string $name
	 * @param WP_Post $post
	 * @return string
	 */
	public function attribute( $name, $post ) {
		if ( method_exists( $this, 'attribute' . ucfirst( $name ) ) ) {
			$method_name = 'attribute' . ucfirst( $name );

			return $this->$method_name( $post );
		}
		if ( isset( $post->$name ) ) {
			return $post->$name;
		}

		return '';
	}

	/**
	 * Sets whether the grid element is the last in the row.
	 *
	 * @param bool $is_end
	 */
	public function setIsEnd( $is_end = true ) {
		$this->is_end = $is_end;
	}

	/**
	 * Checks if the grid element is the last in the row.
	 *
	 * @return bool
	 */
	public function isEnd() {
		return $this->is_end;
	}

	/**
	 * Set elements templates.
	 */
	protected function setShortcodes() {
		foreach ( $this->shortcodes as $tag ) {
			add_shortcode( $tag, [
				$this,
				vc_camel_case( $tag . '_shortcode' ),
			] );
		}
	}

	/**
	 * Get shortcode output with row wrapper.
	 *
	 * @param array $atts
	 * @param string $content
	 * @return string
	 */
	public function vcGitemRowShortcode( $atts, $content = '' ) {
		return '<div class="vc_row vc_gitem-row' . $this->gridAttribute( 'element_width' ) . '">' . "\n" . do_shortcode( $content ) . "\n" . '</div>';
	}

	/**
	 * Provides the template.
	 *
	 * @return string
	 */
	public function vcGitemRowTemplate() {
		$output = '<div class="vc_gitem-wrapper">';
		$output .= '<div class="vc_t-grid-controls vc_t-grid-controls-row" data-vc-element-shortcode="controls">';
		// Move control.
		$output .= '<a class="vc_t-grid-control vc_t-grid-control-move" href="#" title="' . esc_html__( 'Drag row to reorder', 'js_composer' ) . '" data-vc-element-control="move"><i class="vc_t-grid-icon vc_t-grid-icon-move"></i></a>';
		// Layout control.
        //phpcs:disable:Generic.Strings.UnnecessaryStringConcat.Found
		$output .= '<span class="vc_t-grid-control vc_t-grid-control-layouts" style="display: none;">' // vc_col-sm-12.
			. '<a class="vc_t-grid-control vc_t-grid-control-layout" data-cells="12" title="1/1" data-vc-element-control="layouts">' . '<i class="vc_t-grid-icon vc_t-grid-icon-layout-12"></i></a>' // vc_col-sm-6 + vc_col-sm-6.
			. '<a class="vc_t-grid-control vc_t-grid-control-layout" data-cells="6_6" title="1/2 + 1/2" data-vc-element-control="layouts">' . '<i class="vc_t-grid-icon vc_t-grid-icon-layout-6-6"></i></a>' // vc_col-sm-4 + vc_col-sm-4 + vc_col-sm-4.
			. '<a class="vc_t-grid-control vc_t-grid-control-layout" data-cells="4_4_4" title="1/3 + 1/3 + 1/3" data-vc-element-control="layouts">' . '<i class="vc_t-grid-icon vc_t-grid-icon-layout-4-4-4"></i></a></span><span class="vc_pull-right">' // Destroy control.
			. '<a class="vc_t-grid-control vc_t-grid-control-destroy" href="#" title="' . esc_attr__( 'Delete this row', 'js_composer' ) . '" data-vc-element-control="destroy"><i class="vc_t-grid-icon vc_t-grid-icon-destroy"></i></a></span>';
        //phpcs:enable:Generic.Strings.UnnecessaryStringConcat.Found
		$output .= '</div>';
		$output .= '<div data-vc-element-shortcode="content" class="vc_row vc_gitem-content"></div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Get shortcode output with col wrapper.
	 *
	 * @param array $atts
	 * @param string $content
	 * @return string
	 */
	public function vcGitemColShortcode( $atts, $content = '' ) {
		$width = '12';
		$atts = shortcode_atts( [
			'width' => '12',
		], $atts );
		extract( $atts );

		return '<div class="vc_col-sm-' . $width . ' vc_gitem-col">' . "\n" . do_shortcode( $content ) . "\n" . '</div>';
	}

	/**
	 * Provides the template for the `vc_gitem_col` shortcode.
	 *
	 * @return string
	 */
	public function vcGitemColTemplate() {
		$output = '<div class="vc_gitem-wrapper">';
		// Controls.
		// Control "Add".
		$controls = '<a class="vc_t-grid-control vc_t-grid-control-add" href="#" title="' . esc_attr__( 'Prepend to this column', 'js_composer' ) . '" data-vc-element-control="add"><i class="vc_t-grid-icon vc_t-grid-icon-add"></i></a>';
		$output .= '<div class="vc_t-grid-controls vc_t-grid-controls-col" data-vc-element-shortcode="controls">' . $controls . '</div>';
		// Content.
		$output .= '<div data-vc-element-shortcode="content" class="vc_gitem-content"></div>';

		$output .= '</div>';

		return $output;
	}

	/**
	 * Shortcode handler for `vc_gitem_post_title`.
	 *
	 * @param array $atts
	 * @return string
	 */
	public function vcGitemPostTitleShortcode( $atts ) {
		$atts = shortcode_atts( [], $atts );
		extract( $atts );
		$this->setAttribute( 'post_title' );

		return '<h3 data-vc-element-shortcode="content" class="vc_ptitle">{{post_title}}</h3>';
	}

	/**
	 * Provides a default template for a given shortcode tag.
	 *
	 * @param string $tag
	 * @return string
	 */
	public function vcDefaultTemplate( $tag ) {
		$name = preg_replace( '/^vc_gitem_/', '', $tag );
		$title = ucfirst( preg_replace( '/\_/', ' ', $name ) );

		return '<div class="vc_gitem-wrapper">' . $this->elementControls( $title, preg_match( '/^post/', $name ) ? 'orange' : 'green' ) . '</div>';
	}

	/**
	 * Generates controls for an element in the grid.
	 *
	 * @param string $title
	 * @param null $theme
	 * @return string
	 */
	protected function elementControls( $title, $theme = null ) {
        // phpcs:disable:Generic.Strings.UnnecessaryStringConcat.Found
		return '<div class="vc_t-grid-controls vc_t-grid-controls-element' . ( is_string( $theme ) ? ' vc_th-controls-element-' . $theme : '' ) . '" data-vc-element-shortcode="controls">' // Move control.
			. '<a class="vc_t-grid-control vc_t-grid-control-move" href="#" title="' . esc_attr__( 'Drag to reorder', 'js_composer' ) . '" data-vc-element-control="move"><i class="vc_t-grid-icon vc_t-grid-icon-move"></i></a>' // Label.
			. '<span class="vc_t-grid-control vc_t-grid-control-name" data-vc-element-control="name">
					' . $title . '</span>' // Edit control.
			. '<a class="vc_t-grid-control vc_t-grid-control-edit" data-vc-element-control="edit">' . '<i class="vc_t-grid-icon vc_t-grid-icon-edit"></i></a>' // Delete control.
			. '<a class="vc_t-grid-control vc_t-grid-control-destroy" data-vc-element-control="destroy">' . '<i class="vc_t-grid-icon vc_t-grid-icon-destroy"></i></a></div>';
        // phpcs:enable:Generic.Strings.UnnecessaryStringConcat.Found
	}
}

/**
 * Renders the form field for the grid element.
 *
 * @param array $settings
 * @param string $value
 * @return string
 */
function vc_vc_grid_element_form_field( $settings, $value ) {
	$grid_element = new Vc_Grid_Element();

	return '<div data-vc-grid-element="container" data-vc-grid-tags-list="' . esc_attr( wp_json_encode( $grid_element->shortcodes() ) ) . '"><input data-vc-grid-element="value" type="hidden" name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" value="' . esc_attr( $value ) . '">' . $grid_element->renderParam() . '</div>';
}

/**
 * Loads the grid element param.
 */
function vc_load_vc_grid_element_param() {
	vc_add_shortcode_param( 'vc_grid_element', 'vc_vc_grid_element_form_field' );
}

add_action( 'vc_load_default_params', 'vc_load_vc_grid_element_param' );
