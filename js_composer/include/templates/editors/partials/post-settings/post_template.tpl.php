<?php
/**
 * Post template dropdown in page settings panel template.
 *
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


global $post;
$post_type = get_post_type( $post );
$templates = [];
$post_id = $post->ID;
$saved_template = get_post_meta( $post_id, '_wp_page_template', true ) ?: 'default';

if ( 'page' === $post_type ) {
	$templates = get_page_templates( $post );
} else {
	$all_post_templates = wp_get_theme()->get_post_templates();
	if ( ! empty( $all_post_templates[ $post_type ] ) ) {
		$templates = $all_post_templates[ $post_type ];
	}
}

if ( ! empty( $templates ) ) :
	?>
	<div class="vc_col-sm-12 vc_column" id="vc_settings-post_template">
		<div class="wpb_settings-title">
			<div class="wpb_element_label"><?php esc_html_e( 'Template', 'js_composer' ); ?></div>
			<?php
				vc_include_template( 'editors/partials/param-info.tpl.php', [ 'description' => sprintf( esc_html__( 'Select a template for your %s type from templates defined in WordPress.', 'js_composer' ), esc_html( get_post_type() ) ) ] );
			?>
		</div>
		<select id="vc_post_template" name="vc_post_template" class="wpb_vc_param_value wpb-input wpb-select wpb-select--full">
			<option value="default" <?php selected( $saved_template, 'default' ); ?>>
				<?php esc_html_e( 'Default', 'js_composer' ); ?>
			</option>
			<?php foreach ( $templates as $key => $value ) : ?>
				<?php
				$template_file = 'page' === $post_type ? $value : $key;
				$template_name = 'page' === $post_type ? $key : $value;
				?>
				<option value="<?php echo esc_attr( $template_file ); ?>"<?php selected( $saved_template, $template_file ); ?>>
					<?php echo esc_html( $template_name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
<?php endif; ?>
