<?php
/**
 * Featured image control page settings panel template.
 *
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
global $post;
$featured_image = get_post_thumbnail_id( $post->ID );
$featured_image = strval( $featured_image );
if ( '0' === $featured_image ) {
	// set to empty string to prevent default image from being selected in media library.
	$featured_image = '';
}
?>

<div class="vc_col-sm-12 vc_column wpb_el_type_attach_image vc_wrapper-param-type-attach_image" id="vc_settings-featured-image">
	<div class="wpb_settings-title">
		<div class="wpb_element_label"><?php esc_html_e( 'Featured image', 'js_composer' ); ?></div>
	</div>
	<div class="edit_form_line">
		<?php
		$param_value = wpb_removeNotExistingImgIDs( $featured_image );

		vc_include_template( 'params/attache_images/template.php', [
			'settings' => [
				'param_name' => 'featured_image',
				'type' => 'attach_image',
				'id' => 'vc_featured_image',
			],
			'value' => $featured_image,
			'tag' => '',
			'single' => true,
			'param_value' => $param_value,
		] );
		?>
	</div>
</div>
