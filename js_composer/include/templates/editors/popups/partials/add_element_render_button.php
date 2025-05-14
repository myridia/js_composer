<?php
/**
 * Add element render button template.
 *
 * @var array $params
 * @var string $data_atts
 * @var string $class
 * @var string $class_out
 * @var string $category_css_classes
 * @var string $this
 * @var string $icon
 * @var string $deprecated
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<li class="wpb-layout-element-button<?php echo esc_attr( $deprecated . $category_css_classes . $class_out ); ?>" <?php echo $data_atts; // phpcs:ignore:WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="vc_el-container">
		<a id="<?php echo esc_attr( $params['base'] ); ?>" data-tag="<?php echo esc_attr( $params['base'] ); ?>" class="dropable_el vc_shortcode-link<?php echo esc_attr( $class ); ?>" href="javascript:;" data-vc-clickable>
			<?php echo $icon; // phpcs:ignore:WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span data-vc-shortcode-name><?php echo esc_html( stripslashes( $params['name'] ) ); ?></span>
			<?php echo empty( $params['description'] ) ? '' : '<span class="vc_element-description">' . esc_html( $params['description'] ) . '</span>'; ?>
		</a>
	</div>
</li>
