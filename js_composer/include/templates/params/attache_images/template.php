<?php
/**
 * Template for element param attached images.
 *
 * @var array $settings
 * @var string $value
 * @var string $tag
 * @var bool $single
 * @var string $param_value
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<input type="hidden" class="wpb_vc_param_value gallery_widget_attached_images_ids <?php echo esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ); ?>" name="<?php echo esc_attr( $settings['param_name'] ); ?>" value="<?php echo esc_attr( $value ); ?>" id="<?php echo ! empty( $settings['id'] ) ? esc_attr( $settings['id'] ) : esc_attr( $settings['param_name'] ); ?>"/>
<div class="gallery_widget_attached_images">
	<ul class="gallery_widget_attached_images_list">
		<?php
        // phpcs:ignore
        echo '' !== $param_value ? vc_field_attached_images( explode( ',', $value ) ) : ''
		?>
	</ul>
	</div>
<div class="gallery_widget_site_images"></div>
<?php
if ( true === $single ) {
	?>
	<a class="gallery_widget_add_images" href="javascript:;" use-single="true" title="<?php esc_attr_e( 'Add image', 'js_composer' ); ?>"><i class="vc-composer-icon vc-c-icon-add"></i><?php esc_html_e( 'Add image', 'js_composer' ); ?></a>
	<?php
} else {
	?>
	<a class="gallery_widget_add_images" href="javascript:;" title="<?php esc_attr_e( 'Add images', 'js_composer' ); ?>"><i class="vc-composer-icon vc-c-icon-add"></i><?php esc_html_e( 'Add images', 'js_composer' ); ?></a>
	<?php
}
