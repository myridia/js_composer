<?php
/**
 * Control Save Buttons template.
 *
 * @var Wp_Post $post
 * @var bool $is_mobile
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$post_type = get_post_type_object( $post->post_type );
$can_publish = current_user_can( $post_type->cap->publish_posts );

?>
<li class="vc_pull-right vc_save-buttons vc_hide-desktop-more <?php echo $is_mobile ? 'vc_hide-desktop' : 'vc_hide-mobile'; ?>">
<?php
if ( ! in_array( $post->post_status, [
	'publish',
	'future',
	'private',
], true ) ) :
	?>
	<?php if ( 'draft' === $post->post_status ) : ?>
	<a href="javascript:;"
		id="vc_button-save-draft"
		class="<?php echo $is_mobile ? 'vc_icon-btn ' : 'vc_btn vc_btn-default vc_navbar-btn '; ?> vc_btn-save-draft"
		title="<?php esc_attr_e( 'Save Draft', 'js_composer' ); ?>">
		<i class="vc_hide-desktop vc-composer-icon vc-c-icon-save-draft"></i>
		<p><?php esc_html_e( 'Save Draft', 'js_composer' ); ?></p>
	</a>
	<?php elseif ( 'pending' === $post->post_status && $can_publish ) : ?>
		<a href="javascript:;"
			id="vc_button-save-as-pending"
			class="<?php echo $is_mobile ? 'vc_icon-btn ' : 'vc_btn vc_btn-primary vc_navbar-btn '; ?> vc_btn-save"
			title="<?php esc_attr_e( 'Save as Pending', 'js_composer' ); ?>">
			<i class="vc_hide-desktop vc-composer-icon vc-c-icon-save-draft"></i>
			<p><?php esc_html_e( 'Save as Pending', 'js_composer' ); ?></p>
		</a>
	<?php endif ?>
		<?php if ( $can_publish ) : ?>
		<a href="javascript:;"
			id="vc_button-update"
			class="<?php echo $is_mobile ? 'vc_icon-btn ' : 'vc_btn vc_btn-primary vc_navbar-btn '; ?> vc_btn-save"
			title="<?php esc_attr_e( 'Publish', 'js_composer' ); ?>"
			data-change-status="publish">
			<i class="vc_hide-desktop vc-composer-icon vc-c-icon-publish"></i>
			<p><?php esc_html_e( 'Publish', 'js_composer' ); ?></p>
		</a>
	<?php else : ?>
		<a href="javascript:;"
			id="vc_button-update"
			class="<?php echo $is_mobile ? 'vc_icon-btn ' : 'vc_btn vc_btn-primary vc_navbar-btn '; ?> vc_btn-save"
			title="<?php esc_attr_e( 'Submit for Review', 'js_composer' ); ?>"
			data-change-status="pending">
			<i class="vc_hide-desktop vc-composer-icon vc-c-icon-publish"></i>
			<p><?php esc_html_e( 'Submit for Review', 'js_composer' ); ?></p>
		</a>
	<?php endif ?>
<?php else : ?>
	<a href="javascript:;"
		id="vc_button-update"
		class="<?php echo $is_mobile ? 'vc_icon-btn ' : 'vc_btn vc_btn-primary vc_navbar-btn '; ?> vc_btn-save"
		title="<?php esc_attr_e( 'Update', 'js_composer' ); ?>">
		<i class="vc_hide-desktop vc-composer-icon vc-c-icon-publish"></i>
		<p><?php esc_html_e( 'Update', 'js_composer' ); ?></p>
	</a>
<?php endif ?>
</li>
