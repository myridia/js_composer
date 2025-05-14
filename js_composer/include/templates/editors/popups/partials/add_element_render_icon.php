<?php
/**
 * Add element render icon template.
 *
 * @var string $icon
 * @var string $data
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<i class="vc_general vc_element-icon<?php echo esc_attr( $icon ); ?>"<?php echo $data; // phpcs:ignore:WordPress.Security.EscapeOutput.OutputNotEscaped ?>></i>
