<?php
/**
 * Post author selectbox in page settings panel template.
 *
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
global $post, $user_ID;
$post_type_object = get_post_type_object( $post->post_type );
?>

<div class="vc_col-sm-12 vc_column" id="vc_settings-post_author">
	<div class="wpb_settings-title">
		<div class="wpb_element_label"><?php esc_html_e( 'Author', 'js_composer' ); ?></div>
	</div>
	<div class="edit_form_line">
		<?php
		wp_dropdown_users( [
			'capability' => [ $post_type_object->cap->edit_posts ],
			'selected' => empty( $post->ID ) ? $user_ID : $post->post_author,
			'include_selected' => true,
			'orderby' => 'display_name',
			'order' => 'ASC',
			'show' => 'display_name_with_login',
			'id' => 'vc_post_author',
			'name' => 'post_author',
			'class' => 'wpb_vc_param_value wpb-input input vc_post_author',
		] );
		?>
	</div>
</div>
