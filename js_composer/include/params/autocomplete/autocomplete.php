<?php
/**
 * Param type 'autocomplete'
 * Used to create input field with predefined or ajax values suggestions.
 *
 * @see usage example in bottom of this file.Visual Composer AutoComplete Field
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/#vc_map()-ParametersofparamsArray
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_AutoComplete
 *
 * @since 4.4
 */
class Vc_AutoComplete {
	/**
	 * Param settings
	 *
	 * @since 4.4
	 * @var array $settings
	 */
	protected $settings;
	/**
	 * Current param value (if multiple it is splitted by ',' comma to make array).
	 *
	 * @since 4.4
	 * @var string $value
	 */
	protected $value;
	/**
	 * Shortcode name(base).
	 *
	 * @since 4.4
	 * @var string $tag
	 */
	protected $tag;

	/**
	 * Vc_AutoComplete constructor.
	 *
	 * @param array $settings - param settings (from vc_map).
	 * @param string $value - current param value.
	 * @param string $tag - shortcode name(base).
	 *
	 * @since 4.4
	 */
	public function __construct( $settings, $value, $tag ) {
		$this->tag = $tag;
		$this->settings = $settings;
		$this->value = $value;
	}

	/**
	 * Render autocomplete param.
	 *
	 * @return string
	 * @since 4.4
	 * vc_filter: vc_autocomplete_{shortcode_tag}_{param_name}_render - hook to define output for autocomplete item
	 */
	public function render() {
		$output = sprintf( '<div class="vc_autocomplete-field"><ul class="vc_autocomplete%s">', ( isset( $this->settings['settings'], $this->settings['settings']['display_inline'] ) && true === $this->settings['settings']['display_inline'] ) ? ' vc_autocomplete-inline' : '' );

		if ( isset( $this->value ) && strlen( $this->value ) > 0 ) {
			$values = explode( ',', $this->value );
			foreach ( $values as $key => $val ) {
				$value = [
					'value' => trim( $val ),
					'label' => trim( $val ),
				];
				if ( isset( $this->settings['settings'], $this->settings['settings']['values'] ) && ! empty( $this->settings['settings']['values'] ) ) {
					foreach ( $this->settings['settings']['values'] as $data ) {
						if ( trim( $data['value'] ) === trim( $val ) ) {
							$value['label'] = $data['label'];
							break;
						}
					}
				} else {
					// Magic is here. this filter is used to render value correctly ( must return array with 'value', 'label' keys ).
					$value = apply_filters( 'vc_autocomplete_' . $this->tag . '_' . $this->settings['param_name'] . '_render', $value, $this->settings, $this->tag );
				}

				if ( is_array( $value ) && isset( $value['value'], $value['label'] ) ) {
					$output .= '<li data-value="' . $value['value'] . '"  data-label="' . $value['label'] . '" data-index="' . $key . '" class="vc_autocomplete-label vc_data"><span class="vc_autocomplete-label">' . $value['label'] . '</span> <a class="vc_autocomplete-remove">&times;</a></li>';
				}
			}
		}

		$output .= sprintf( '<li class="vc_autocomplete-input"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input class="vc_auto_complete_param" type="text" placeholder="%s" value="%s" autocomplete="off"></li><li class="vc_autocomplete-clear"></li></ul>', esc_attr__( 'Click here and start typing...', 'js_composer' ), $this->value );

		$output .= sprintf( '<input name="%s" class="wpb_vc_param_value  %s %s_field" type="hidden" value="%s" %s /></div>', $this->settings['param_name'], $this->settings['param_name'], $this->settings['type'], $this->value, ( isset( $this->settings['settings'] ) && ! empty( $this->settings['settings'] ) ) ? ' data-settings="' . htmlentities( wp_json_encode( $this->settings['settings'] ), ENT_QUOTES, 'utf-8' ) . '" ' : '' );

		return $output;
	}
}

add_action( 'wp_ajax_vc_get_autocomplete_suggestion', 'vc_get_autocomplete_suggestion' );
/**
 * Handles AJAX requests for autocomplete suggestions.
 *
 * @since 4.4
 */
function vc_get_autocomplete_suggestion() {
	vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie();

	$query = vc_post_param( 'query' );
	$tag = wp_strip_all_tags( vc_post_param( 'shortcode' ) );
	$param_name = vc_post_param( 'param' );
	vc_render_suggestion( $query, $tag, $param_name );
}

/**
 * Renders autocomplete suggestions for a given query.
 *
 * @param string $query
 * @param string $tag
 * @param string $param_name
 *
 * @see vc_filter: vc_autocomplete_{tag}_{param_name}_callback - hook to get suggestions from ajax. (here you need to hook).
 * @since 4.4
 */
function vc_render_suggestion( $query, $tag, $param_name ) {
	$suggestions = apply_filters( 'vc_autocomplete_' . stripslashes( $tag ) . '_' . stripslashes( $param_name ) . '_callback', $query, $tag, $param_name );
	if ( is_array( $suggestions ) && ! empty( $suggestions ) ) {
		die( wp_json_encode( $suggestions ) );
	}
	die( wp_json_encode( [] ) ); // if nothing found..
}

/**
 * Function for rendering param in edit form (add element)
 * Parse settings from vc_map and entered values.
 *
 * @param array $settings
 * @param string $value
 * @param string $tag
 *
 * @return mixed rendered template for params in edit form
 * @since 4.4
 * @see vc_filter: vc_autocomplete_render_filter - hook to override output of edit for field "autocomplete"
 */
function vc_autocomplete_form_field( $settings, $value, $tag ) {

	$auto_complete = new Vc_AutoComplete( $settings, $value, $tag );

	return apply_filters( 'vc_autocomplete_render_filter', $auto_complete->render() );
}
