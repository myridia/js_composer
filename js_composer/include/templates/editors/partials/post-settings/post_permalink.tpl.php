<?php
/**
 * Post permalink input in page settings panel template.
 *
 * @var array $permalink
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$post_slug = $permalink['post_slug'];
$post_url_with_slug = $permalink['post_url_with_slug'];
$post_url_without_slug = $permalink['post_url_without_slug'];
$can_user_edit_permalink = $permalink['can_user_edit_permalink'];

?>

<div class="vc_col-sm-12 vc_column" id="vc_settings-post_permalink">
	<div class="wpb_settings-title">
		<div class="wpb_element_label"><?php esc_html_e( 'Slug', 'js_composer' ); ?></div>
		<?php
			vc_include_template( 'editors/partials/param-info.tpl.php', [ 'description' => sprintf( esc_html__( 'Change the destination link to the %s (Note: permalink settings as defined in WordPress Dashboard - Settings - Permalinks).', 'js_composer' ), esc_html( get_post_type() ) ) ] );
		?>
	</div>
	<div class="edit_form_line">
			<input name="post_name" class="wpb-textinput vc_post_permalink" type="text" value="<?php echo esc_attr( $post_slug ); ?>" id="vc_post_name">
			<p class="wpb_form-link-container">
				<a class="wpb_form-link" id="wpb-post-url" href="<?php echo esc_attr( $post_url_with_slug ); ?>" target="_blank" rel="noopener noreferrer">
					<span class="wpb-post-url--prefix"><?php echo esc_html( $post_url_without_slug ); ?></span>
					<?php if ( $can_user_edit_permalink ) : ?>
						<span class="wpb-post-url--slug"><?php echo esc_html( $post_slug ); ?></span>
					<?php endif; ?>
				</a>
			</p>
	</div>
</div>
