<?php
/**
 * Post type dropdown in page settings panel template.
 *
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
global $post, $user_ID;
$current_user = wp_get_current_user();
$post_type_object = get_post_type_object( $post->post_type );

$cap_publish       = $post_type_object->cap->publish_posts;
$cap_edit          = $post_type_object->cap->edit_posts;
$cap_edit_others   = $post_type_object->cap->edit_others_posts;

$can_publish       = current_user_can( $cap_publish );
$can_edit          = current_user_can( $cap_edit );
$can_edit_others   = current_user_can( $cap_edit_others );
$can_submit_for_review = current_user_can( $cap_edit ) && ! current_user_can( $cap_publish );

$current_post_status = get_post_status( get_the_ID() );
?>

<?php if ( current_user_can( 'edit_posts' ) && 'draft' !== $current_post_status ) : ?>
<div class="vc_col-sm-12 vc_column" id="vc_settings-post_status">
	<div class="wpb_element_label"><?php esc_html_e( 'Post status', 'js_composer' ); ?></div>
	<select id="vc_post_status" name="post_status" class="wpb_vc_param_value wpb-input wpb-select wpb-select--full">
		<?php if ( $can_edit ) : ?>
			<option value="draft" <?php selected( $current_post_status, 'draft' ); ?>>
				<?php esc_html_e( 'Draft', 'js_composer' ); ?>
			</option>
		<?php endif; ?>
		<?php if ( $can_submit_for_review ) : ?>
			<option value="pending" <?php selected( $current_post_status, 'pending' ); ?>>
				<?php esc_html_e( 'Pending Review', 'js_composer' ); ?>
			</option>
		<?php endif; ?>
		<?php if ( $can_publish ) : ?>
			<option value="publish" <?php selected( $current_post_status, 'publish' ); ?>>
				<?php esc_html_e( 'Published', 'js_composer' ); ?>
			</option>
		<?php endif; ?>
	</select>
</div>
<?php endif; ?>
