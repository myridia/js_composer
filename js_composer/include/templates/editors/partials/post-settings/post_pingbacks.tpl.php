<?php
/**
 * Allow/disable pingbacks section in page settings panel.
 *
 * @since 8.2
 */

global $post;
$ping_status = 'open' === $post->ping_status ? 'checked' : '';
?>

<div class="vc_col-xs-12 vc_column">
	<div class="wpb-toggle-wrapper vc_settings-comments">
		<input type="checkbox" id="vc_post_pingbacks" name="post_pingbacks" <?php echo esc_attr( $ping_status ); ?> />
		<label for="vc_post_pingbacks"></label>
		<div class="wpb_element_label"><?php esc_html_e( 'Allow trackbacks and pingbacks on this page', 'js_composer' ); ?></div>
	</div>
</div>
