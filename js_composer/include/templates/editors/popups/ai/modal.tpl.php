<?php
/**
 * AI modal template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<div class="vc_modal modal-backdrop vc_modal-popup-container" id="vc_ui-helper-modal-ai">
	<div class="vc_ui-font-open-sans vc_media-xs vc_modal-popup-content" >
		<div class="vc_ui-panel-window-inner">
			<?php
			$is_license_activated = false;
			if ( function_exists( 'vc_license' ) ) {
				$is_license_activated = vc_license()->isActivated();
			}

			vc_include_template('editors/popups/vc_ui-header.tpl.php', [
				'title' => esc_html__( 'WPBakery AI (Beta)', 'js_composer' ),
				'controls' => [ 'close' ],
				'header_css_class' => 'vc_ui-post-settings-header-container',
				'header_tabs_template' => '',
				'is_ai_token_usage' => $is_license_activated,
			]);
			?>
			<div class="vc_ui-helper-modal-ai-placeholder vc_ui-helper-modal-ai-promo vc_ui-helper-popup-promo vc_ui-hidden">
				<div class="vc_ui-helper-popup-promo--inner">
					<?php
					vc_include_template( 'editors/popups/ai/happy-ai-logo.tpl.php' );
					?>
					<p class="vc_description"><?php esc_html_e( 'WPBakery AI is now generating the content …', 'js_composer' ); ?></p>
					<p class="vc_description"><?php esc_html_e( 'large content generation can take up to several minutes', 'js_composer' ); ?>: <span class="vc_ai-timer">00:00</span></p>
				</div>
			</div>
			<div class="vc_ui-helper-modal-ai-preloader">
				<div class="vc_ui-wp-spinner vc_ui-wp-spinner-dark vc_ui-wp-spinner-lg"></div>
			</div>
			<!-- param window footer-->
			<?php
			vc_include_template('editors/popups/vc_ui-footer.tpl.php', [
				'controls' => [
					[
						'name' => 'close',
						'label' => esc_html__( 'Close', 'js_composer' ),
					],
					[
						'name' => 'save',
						'label' => esc_html__( 'Insert', 'js_composer' ),
						'css_classes' => 'vc_ui-button-fw',
						'style' => 'action',
					],
				],
			]);
			?>
		</div>
	</div>
</div>

<?php
// Add HTML after Form.
do_action( 'wpb_add_after_settings_form' );
