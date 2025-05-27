<?php
/**
 * Control button save post in backend editor for mobile template.
 *
 * @var string $save_text
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<li class="vc_pull-right vc_save-backend">
	<a class="vc_control-preview vc_icon-btn">
		<i class="vc_hide-desktop vc-composer-icon vc-c-icon-preview"></i>
		<p><?php esc_html_e( 'Preview', 'js_composer' ); ?></p>
	</a>
	<a class="vc_icon-btn vc_control-save" id="wpb-save-post">
		<i class="vc_hide-desktop vc-composer-icon vc-c-icon-publish"></i>
		<p><?php echo esc_html( $save_text ); ?></p>
	</a>
</li>
