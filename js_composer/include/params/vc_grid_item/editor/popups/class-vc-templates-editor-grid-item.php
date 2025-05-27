<?php
/**
 * Base templates editor for grid item.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'EDITORS_DIR', 'popups/class-vc-templates-panel-editor.php' );
require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/class-vc-grid-item.php' );

/**
 * Class Vc_Templates_Editor_Grid_Item
 */
class Vc_Templates_Editor_Grid_Item extends Vc_Templates_Panel_Editor {
	/**
	 * This prevents for loading default templates
	 *
	 * @var array
	 */
	protected $default_templates = [];

	/**
	 * Vc_Templates_Editor_Grid_Item constructor.
	 */
	public function __construct() {
		add_filter( 'vc_templates_render_category', [
			$this,
			'renderTemplateBlock',
		], 10, 2 );
		add_filter( 'vc_templates_render_template', [
			$this,
			'renderTemplateWindowGrid',
		], 10, 2 );
	}

	/**
	 * Render template block for certain category.
	 *
	 * @param array $category
	 * @return array
	 */
	public function renderTemplateBlock( $category ) {
		if ( 'grid_templates' === $category['category'] || 'grid_templates_custom' === $category['category'] ) {
			$category['output'] = '<div class="vc_col-md-12">';
			if ( isset( $category['category_name'] ) ) {
				$category['output'] .= '<h3>' . esc_html( $category['category_name'] ) . '</h3>';
			}
			if ( isset( $category['category_description'] ) ) {
				$category['output'] .= '<p class="vc_description">' . esc_html( $category['category_description'] ) . '</p>';
			}
			$category['output'] .= '</div>';

			$category['output'] .= '
			<div class="vc_column vc_col-sm-12">
				<div class="vc_ui-template-list vc_templates-list-my_templates vc_ui-list-bar" data-vc-action="collapseAll">';
			if ( ! empty( $category['templates'] ) ) {
				foreach ( $category['templates'] as $template ) {
					$category['output'] .= $this->renderTemplateListItem( $template );
				}
			}
			$category['output'] .= '
				</div>
			</div>';
		}

		return $category;
	}

	/**
	 * Output rendered template in modal dialog.
	 *
	 * @param string $template_name
	 * @param array $template_data
	 *
	 * @return string
	 * @since 4.4
	 */
	public function renderTemplateWindowGrid( $template_name, $template_data ) {
		if ( 'grid_templates' === $template_data['type'] || 'grid_templates_custom' === $template_data['type'] ) {
			return $this->renderTemplateWindowGridTemplate( $template_name, $template_data );
		}

		return $template_name;
	}

	/**
	 * Render html output for template window grid.
	 *
	 * @param string $template_name
	 * @param array $template_data
	 *
	 * @return string
	 * @since 4.4
	 */
	protected function renderTemplateWindowGridTemplate( $template_name, $template_data ) {

		ob_start();

		$template_id = esc_attr( $template_data['unique_id'] );
		$template_name = esc_html( $template_name );
		$preview_template_title = esc_attr__( 'Preview template', 'js_composer' );
		$add_template_title = esc_attr__( 'Preview template', 'js_composer' );

        // phpcs:ignore:Universal.CodeAnalysis.NoEchoSprintf.Found
		echo sprintf( '<button type="button" class="vc_ui-list-bar-item-trigger" title="%s"
				data-template-handler=""
				data-vc-ui-element="template-title">%s</button>
			<div class="vc_ui-list-bar-item-actions">
				<button type="button" class="vc_general vc_ui-control-button" title="%s"
					 	data-template-handler=""
						data-vc-ui-element="template-title">
					<i class="vc-composer-icon vc-c-icon-add"></i>
				</button>
				<button type="button" class="vc_general vc_ui-control-button" title="%s"
					data-vc-preview-handler data-vc-container=".vc_ui-list-bar" data-vc-target="[data-template_id=%s]">
					<i class="vc-composer-icon vc-c-icon-arrow_drop_down"></i>
				</button>
			</div>', esc_attr( $add_template_title ), esc_html( $template_name ), esc_attr( $add_template_title ), esc_attr( $preview_template_title ), esc_attr( $template_id ) );

		return ob_get_clean();
	}

	/**
	 * Load output for predefined template.
	 *
	 * @param bool $template_id
	 */
	public function load( $template_id = false ) {
		if ( ! $template_id ) {
			$template_id = vc_post_param( 'template_unique_id' );
		}
		if ( ! isset( $template_id ) || '' === $template_id ) {
			echo 'Error: TPL-02';
			die;
		}
		$predefined_template = Vc_Grid_Item::predefinedTemplate( $template_id );
		if ( $predefined_template ) {
			echo esc_html( trim( $predefined_template['template'] ) );
		}
	}

	/**
	 * Load custom template content.
	 *
	 * @param bool $template_id
	 * @return string
	 */
	public function loadCustomTemplate( $template_id = false ) {
		if ( ! $template_id ) {
			$template_id = vc_post_param( 'template_unique_id' );
		}
		if ( ! isset( $template_id ) || '' === $template_id ) {
			echo 'Error: TPL-02';
			die();
		}

		$post = get_post( $template_id );

		if ( $post && Vc_Grid_Item_Editor::postType() === $post->post_type ) {
			return $post->post_content;
		}

		return '';
	}

	/**
	 * Get all templates.
	 *
	 * @return array|mixed|void
	 */
	public function getAllTemplates() {
		$data = [];
		$grid_templates = $this->getGridTemplates();
		// this has only 'name' and 'template' key  and index 'key' is template id.
		if ( ! empty( $grid_templates ) ) {
			$arr_category = [
				'category' => 'grid_templates',
				'category_name' => esc_html__( 'Grid Templates', 'js_composer' ),
				'category_weight' => 10,
			];
			$category_templates = [];
			foreach ( $grid_templates as $template_id => $template_data ) {
				$category_templates[] = [
					'unique_id' => $template_id,
					'name' => $template_data['name'],
					'type' => 'grid_templates',
					// for rendering in backend/frontend with ajax.
				];
			}
			$arr_category['templates'] = $category_templates;
			$data[] = $arr_category;
		}
		$custom_grid_templates = $this->getCustomTemplateList();
		if ( ! empty( $custom_grid_templates ) ) {
			$arr_category = [
				'category' => 'grid_templates_custom',
				'category_name' => esc_html__( 'Custom Grid Templates', 'js_composer' ),
				'category_weight' => 10,
			];
			$category_templates = [];
			foreach ( $custom_grid_templates as $template_name => $template_id ) {
				$category_templates[] = [
					'unique_id' => $template_id,
					'name' => $template_name,
					'type' => 'grid_templates_custom',
					// for rendering in backend/frontend with ajax).
				];
			}
			$arr_category['templates'] = $category_templates;
			$data[] = $arr_category;
		}

		// To get any other 3rd "Custom template" - do this by hook filter 'vc_get_all_templates'.
		return apply_filters( 'vc_grid_get_all_templates', $data );
	}

	/**
	 * Get custom template list.
	 *
	 * @return array
	 */
	protected function getCustomTemplateList() {
		$list = [];
		$templates = get_posts( [
			'post_type' => Vc_Grid_Item_Editor::postType(),
			'numberposts' => - 1,
		] );
		foreach ( $templates as $template ) {
			$id = $template->ID;
			$list[ $template->post_title ] = $id;
		}

		return $list;
	}

	/**
	 * Get grid templates.
	 *
	 * @return bool|mixed
	 */
	public function getGridTemplates() {
		$list = Vc_Grid_Item::predefinedTemplates();

		return $list;
	}
}
