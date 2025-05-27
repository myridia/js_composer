<?php
/**
 * Post tags input in page settings panel template.
 *
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$post_id = get_the_ID();
$selected_tags = wp_get_post_tags( $post_id );
if ( ! is_array( $selected_tags ) ) {
	$selected_tags = [];
}
?>

<div class="vc_col-sm-12 vc_column" id="vc_settings-post-tags">
	<div class="wpb_settings-title">
		<div class="wpb_element_label"><?php esc_html_e( 'Tags', 'js_composer' ); ?></div>
	</div>
	<div class="edit_form_line">
		<select id="vc_post-tags" name="vc_post-tags" multiple="multiple">
			<?php foreach ( $selected_tags as $tag ) : ?>
				<option value="<?php echo esc_attr( $tag->term_id ); ?>" selected="selected">
					<?php echo esc_html( $tag->name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
