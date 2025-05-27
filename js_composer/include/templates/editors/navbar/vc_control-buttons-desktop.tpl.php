<?php
/**
 * Control button save post in backend editor for desktop template.
 *
 * @var string $save_text
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<li class="vc_pull-right vc_hide-mobile vc_save-backend">
	<a class="vc_btn vc_btn-white vc_btn-sm vc_navbar-btn vc_control-preview">
		<?php esc_html_e( 'Preview', 'js_composer' ); ?>
	</a>
	<a class="vc_btn vc_btn-sm vc_navbar-btn vc_btn-white vc_control-save" id="wpb-save-post">
		<?php echo esc_html( $save_text ); ?>
	</a>
</li>
