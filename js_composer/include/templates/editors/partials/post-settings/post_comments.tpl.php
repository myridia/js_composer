<?php
/**
 * Allow/disable comments section in page settings panel.
 *
 * @since 8.2
 */

global $post;
$comment_status = 'open' === $post->comment_status ? 'checked' : '';
?>

<div class="vc_col-xs-12 vc_column">
	<div class="wpb-toggle-wrapper vc_settings-comments">
		<input type="checkbox" id="vc_post_comments" name="post_comments" <?php echo esc_attr( $comment_status ); ?> />
		<label for="vc_post_comments"></label>
		<div class="wpb_element_label"><?php esc_html_e( 'Allow Comments', 'js_composer' ); ?></div>
	</div>
</div>
