<?php
/**
 * Renders navigation bar for Editors.
 *
 * @noinspection PhpMissingParentCallCommonInspection
 * @package WPBakery
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'EDITORS_DIR', 'navbar/class-vc-navbar.php' );

/**
 * Class Vc_Navbar_Grid_Item
 */
class Vc_Navbar_Grid_Item extends Vc_Navbar {
	/**
	 * Get navbar controls.
	 *
	 * @var array
	 */
	protected $controls = [
		'templates',
		'save_backend',
		'preview_template',
		'more',
		'animation_list',
		'preview_item_width',
		'edit',
	];

	/**
	 * Get control templates html.
	 *
	 * @return string
	 */
	public function getControlTemplates() {
		return vc_get_template( 'editors/navbar/vc_control-templates-button.php' );
	}

	/**
	 * Get control preview template html.
	 *
	 * @param bool $is_mobile since 8.0.
	 *
	 * @return string
	 */
	public function getControlPreviewTemplate( $is_mobile = false ) {
		return '<li class="vc_pull-right vc_preview-template vc_hide-mobile vc_hide-desktop-more">
	                <a href="#" class="' . ( $is_mobile ? 'vc_icon-btn' : 'vc_btn vc_btn-white vc_btn-sm vc_navbar-btn' ) . '" data-vc-navbar-control="preview">
	                    <i class="vc_hide-desktop vc-composer-icon vc-c-icon-preview"></i>
	                    <p>' . esc_html__( 'Preview', 'js_composer' ) . '</p>
	                </a>
	            </li>';
	}

	/**
	 * Get control edit html.
	 *
	 * @return string
	 */
	public function getControlEdit() {
		return '<li class="vc_pull-right vc_hide-mobile vc_hide-desktop-more">
				<a data-vc-navbar-control="edit" class="vc_icon-btn vc_post-settings" title="' . esc_attr__( 'Grid element settings', 'js_composer' ) . '">
					<i class="vc-composer-icon vc-c-icon-cog"></i>
					<p class="vc_hide-desktop">' . __( 'Settings', 'js_composer' ) . '</p>
				</a>
			</li>';
	}

	/**
	 * Get control save backend html.
	 *
	 * @param bool $is_mobile since 8.0.
	 * @return string
	 */
	public function getControlSaveBackend( $is_mobile = false ) {
		return '<li class="vc_pull-right vc_save-backend vc_hide-mobile vc_hide-desktop-more">
					<a class="' . ( $is_mobile ? 'vc_icon-btn vc_control-save' : 'vc_btn vc_btn-sm vc_navbar-btn vc_btn-white vc_control-save' ) . '" id="wpb-save-post">
						<i class="vc_hide-desktop vc-composer-icon vc-c-icon-publish"></i>
						<p>' . esc_html__( 'Update', 'js_composer' ) . '</p>
					</a>
				</li>';
	}

	/**
	 * Get control preview item width html.
	 *
	 * @return string
	 */
	public function getControlPreviewItemWidth() {
		$output = '<li class="vc_pull-right vc_gitem-navbar-dropdown vc_gitem-navbar-preview-width" data-vc-grid-item="navbar_preview_width"><select data-vc-navbar-control="preview_width">';
		for ( $i = 1; $i <= 12; $i++ ) {
			$output .= '<option value="' . esc_attr( $i ) . '">' . sprintf( esc_html__( '%s/12 width', 'js_composer' ), $i ) . '</option>';
		}
		$output .= '</select></li>';

		return $output;
	}

	/**
	 * Get control animation list html.
	 *
	 * @return string
	 */
	public function getControlAnimationList() {
		VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_Vc_Gitem_Animated_Block' );

		$output = '';

		$animations = WPBakeryShortCode_Vc_Gitem_Animated_Block::animations();
		if ( is_array( $animations ) ) {
			$output .= '<li class="vc_pull-right vc_gitem-navbar-dropdown"><select data-vc-navbar-control="animation">';
			foreach ( $animations as $value => $key ) {
				$output .= '<option value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';
			}
			$output .= '</select></li>';
		}

		return $output;
	}

	/**
	 * Output individual control elements.
	 *
	 * @since 8.0
	 */
	public function outputIndividualControlElements() {
		echo wp_kses_post( $this->getControlEdit() );
		echo wp_kses_post( $this->getControlPreviewTemplate( true ) );
		echo wp_kses_post( $this->getControlSaveBackend( true ) );
	}
}
