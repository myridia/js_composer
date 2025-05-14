<?php
/**
 * Preset panel editor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Preset_Panel_Editor
 *
 * @since 5.2
 */
class Vc_Preset_Panel_Editor {
	/**
	 * Indicates whether the editor has been initialized.
	 *
	 * @since 5.2
	 * @var bool
	 */
	protected $initialized = false;

	/**
	 * Initializes the editor by adding necessary AJAX hooks and filters.
	 *
	 * @since 5.2
	 * Add ajax hooks, filters.
	 */
	public function init() {
		if ( $this->initialized ) {
			return;
		}
		$this->initialized = true;
	}

	/**
	 * Renders the UI for the presets.
	 *
	 * @since 5.2
	 */
	public function renderUIPreset() {
		vc_include_template( 'editors/popups/vc_ui-panel-preset.tpl.php', [
			'box' => $this,
		] );

		return '';
	}

	/**
	 * Get list of all presets for specific shortcode
	 *
	 * @return array E.g. array(id1 => title1, id2 => title2, ...)
	 * @since 5.2
	 */
	public function listPresets() {
		$list = [];

		$args = [
			'post_type' => 'vc_settings_preset',
			'orderby' => [ 'post_date' => 'DESC' ],
			'posts_per_page' => - 1,
		];

		$posts = get_posts( $args );
		foreach ( $posts as $post ) {

			$preset_parent_name = self::constructPresetParent( $post->post_mime_type );

			$list[ $post->ID ] = [
				'title' => $post->post_title,
				'parent' => $preset_parent_name,
			];
		}

		return $list;
	}

	/**
	 * Single preset html
	 *
	 * @return string
	 * @since 5.2
	 */
	public function getPresets() {
		$list_presets = $this->listPresets();
		$output = '';

		foreach ( $list_presets as $preset_id => $preset ) {
			$output .= '<div class="vc_ui-template">';
			$output .= '<div class="vc_ui-list-bar-item">';
			$output .= '<button type="button" class="vc_ui-list-bar-item-trigger" title="' . esc_attr( $preset['title'] ) . '"
						data-vc-ui-element="template-title">' . esc_html( $preset['title'] ) . '</button>';
			$output .= '<div class="vc_ui-list-bar-item-actions">';

			$output .= '<button id="' . esc_attr( $preset['parent'] ) . '" type="button" class="vc_general vc_ui-control-button" title="' . esc_attr__( 'Add element', 'js_composer' ) . '" data-template-handler="" data-preset="' . esc_attr( $preset_id ) . '" data-tag="' . esc_attr( $preset['parent'] ) . '" data-vc-ui-add-preset>';
			$output .= '<i class="vc-composer-icon vc-c-icon-add"></i>';
			$output .= '</button>';

			$output .= '<button type="button" class="vc_general vc_ui-control-button" data-vc-ui-delete="preset-title" data-preset="' . esc_attr( $preset_id ) . '" data-preset-parent="' . esc_attr( $preset['parent'] ) . '" title="' . esc_attr__( 'Delete element', 'js_composer' ) . '">';
			$output .= '<i class="vc-composer-icon vc-c-icon-delete_empty"></i>';
			$output .= '</button>';

			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * Get preset parent shortcode name from post mime type
	 *
	 * @param string $preset_mime_type
	 *
	 * @return string
	 * @since 5.2
	 */
	public static function constructPresetParent( $preset_mime_type ) {
		return str_replace( '-', '_', str_replace( 'vc-settings-preset/', '', $preset_mime_type ) );
	}
}
