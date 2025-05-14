<?php
/**
 * Beta version field template.
 *
 * @var boolean $checked
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="wpb-settings-beta-version">
	<?php
	if ( vc_license()->isActivated() ) {
		vc_include_template( 'editors/partials/param-info.tpl.php', [
			'description' => esc_html__( "Enable the plugin's beta version for this website for testing purposes. This feature is not recommended for production/live websites.", 'js_composer' ),
		] );
		?>
		<label>
			<input type="checkbox"<?php echo esc_attr( $checked ) ? ' checked' : ''; ?> value="1" id="<?php echo esc_attr( 'wpb_js_beta_version' ); ?>" name="<?php echo esc_attr( 'wpb_js_beta_version' ); ?>">
			<?php esc_html_e( 'Enable', 'js_composer' ); ?><br>
		</label>
		<p class="wpb-beta-title-agreement">
			<?php esc_html_e( 'By enabling beta, I agree to be contacted by WPBakery to collect feedback.', 'js_composer' ); ?>
		</p>
		<?php
	} else {
		esc_html_e( 'An active license is needed to access beta version controls.', 'js_composer' );
	}
	?>
</div>
