<?php
/**
 * Template for element param iconpicker single icon.
 *
 * @var string $class_key
 * @var string $selected
 * @var array $icon
 *
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<option value="<?php echo esc_attr( $class_key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $icon ); ?></option>
