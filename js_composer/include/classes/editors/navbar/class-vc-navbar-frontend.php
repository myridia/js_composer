<?php
/**
 * Navbar Frontend functionality.
 *
 * @package WPBakeryPageBuilder
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'EDITORS_DIR', 'navbar/class-vc-navbar.php' );

/**
 * Class Vc_Navbar_Frontend
 */
class Vc_Navbar_Frontend extends Vc_Navbar {
	/**
	 * List of controls specific to the frontend navbar.
	 *
	 * @var array
	 */
	protected $controls = [
		'add_element',
		'templates',
		'view_post',
		'more',
		'save_buttons',
		'screen_size',
		'custom_css',
	];
	/**
	 * Filter name for the frontend controls.
	 *
	 * @var string
	 */
	protected $controls_filter_name = 'vc_nav_front_controls';
	/**
	 * URL for the frontend brand logo.
	 *
	 * @var string
	 */
	protected $brand_url = 'https://wpbakery.com/?utm_source=wpb-plugin&utm_medium=frontend-editor&utm_campaign=info&utm_content=logo';
	/**
	 * CSS class for the frontend navbar.
	 *
	 * @var string
	 */
	protected $css_class = 'vc_navbar vc_navbar-frontend';
	/**
	 * Renders the screen size controls.
	 *
	 * @return string
	 */
	public function getControlScreenSize() {
		$disable_responsive = vc_settings()->get( 'not_responsive_css' );
		if ( '1' !== $disable_responsive ) {
            // phpcs:ignore
			$screen_sizes = apply_filters( 'wpb_navbar_getControlScreenSize', array(
				[
					'title' => esc_html__( 'Desktop', 'js_composer' ),
					'size' => '100%',
					'key' => 'default',
					'active' => true,
				],
				[
					'title' => esc_html__( 'Tablet landscape mode', 'js_composer' ),
					'size' => '1024px',
					'key' => 'landscape-tablets',
				],
				[
					'title' => esc_html__( 'Tablet portrait mode', 'js_composer' ),
					'size' => '768px',
					'key' => 'portrait-tablets',
				],
				[
					'title' => esc_html__( 'Smartphone portrait mode', 'js_composer' ),
					'size' => '480px',
					'key' => 'portrait-smartphones',

				],
			) );
			$output = '<li class="vc_pull-right vc_hide-mobile"><div class="vc_dropdown" id="vc_screen-size-control"><a href="#" class="vc_dropdown-toggle vc_icon-btn" title="' . esc_attr__( 'Responsive preview', 'js_composer' ) . '"><i class="vc-composer-icon vc_current-layout-icon vc-c-icon-layout_default" id="vc_screen-size-current"></i></a><ul class="vc_dropdown-list">';
			$screen = current( $screen_sizes );
			while ( $screen ) {
				$output .= '<li><a href="#" title="' . esc_attr( $screen['title'] ) . '" class="vc_screen-width vc_icon-btn vc-composer-icon vc-c-icon-layout_' . esc_attr( $screen['key'] ) . ( isset( $screen['active'] ) && $screen['active'] ? ' active' : '' ) . '" data-size="' . esc_attr( $screen['size'] ) . '"></a></li>';
				next( $screen_sizes );
				$screen = current( $screen_sizes );
			}
			$output .= '</ul></div></li>';

			return $output;
		}

		return '';
	}


	/**
	 * Renders the save buttons control with appropriate label based on post status and user capabilities.
	 *
	 * @since 8.0
	 * @param bool $is_mobile
	 * @return string
	 */
	public function getControlSaveButtons( $is_mobile = false ) {
		return vc_get_template(
			'editors/navbar/vc_control-save-buttons.tpl.php',
			[
				'post' => $this->post(),
				'is_mobile' => $is_mobile,
			]
		);
	}

	/**
	 * Controls html for view post functionality.
	 *
	 * @since 8.0
	 * @param bool $is_mobile
	 * @return string
	 */
	public function getControlViewPost( $is_mobile = false ) {
		return vc_get_template(
			'editors/navbar/vc_control-view-post.tpl.php',
			[
				'is_mobile' => $is_mobile,
				'post_id'   => $this->post(),
			]
		);
	}


	/**
	 * Controls html for save and update functionality.
	 *
	 * @return string
	 * @deprecated 8.0
	 */
	public function getControlSaveUpdate() {
		_deprecated_function( __METHOD__, '8.0', 'Vc_Navbar_Frontend::getControlMore' );

		return $this->getControlMore();
	}

	/**
	 * Renders the more control.
	 *
	 * @since 8.0
	 * @return string
	 */
	public function getControlMore() {
		$post = $this->post();
		ob_start();
		?>
		<li class="vc_pull-right vc_show-mobile">
			<div class="vc_dropdown vc_dropdown-more" id="vc_more-options">
				<a class="vc_dropdown-toggle vc_icon-btn" title="More">
					<i class="vc-composer-icon vc-c-icon-more"></i>
				</a>
				<ul class="vc_dropdown-list">
					<?php
					$undo_redo = apply_filters( $this->controls_filter_name, [] );
					foreach ( $undo_redo as $control ) :
						// @codingStandardsIgnoreLine
						print $control[1];
					endforeach;
					echo wp_kses_post( $this->getControlCustomCss() );
					echo wp_kses_post( $this->getControlSaveButtons( true ) );
					?>
					<li class="vc_dropdown-list-item">
						<?php
						if ( vc_user_access()->part( 'backend_editor' )->can()->get() ) {
							?>
							<a href="<?php echo esc_url( get_edit_post_link( $post ) ) . '&wpb-backend-editor'; ?>">
								<i class="vc_hide-desktop vc-composer-icon vc-c-icon-backend-editor"></i>
								<p><?php esc_html_e( 'Backend Editor', 'js_composer' ); ?></p>

							</a>
							<?php
						}
						?>
					</li>
					<li class="vc_dropdown-list-item">
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>">
							<i class="vc_hide-desktop vc-composer-icon vc-c-icon-preview"></i>
							<p><?php esc_html_e( 'View Page', 'js_composer' ); ?></p>
						</a>
					</li>
					<?php
					echo wp_kses_post( $this->getControlViewPost( true ) );
					?>
				</ul>
			</div>
		</li>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
