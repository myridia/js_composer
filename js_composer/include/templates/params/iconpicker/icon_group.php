<?php
/**
 * Template for element param iconpicker icon group.
 *
 * @var array $icons
 * @var string $group
 * @var mixed $value
 *
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<optgroup label="<?php echo esc_attr( $group ); ?>">
	<?php
	foreach ( $icons as $label ) {
		$class_key = key( $label );
		vc_include_template( 'params/iconpicker/single_icon.php',
			[
				'class_key' => $class_key,
				'selected' => null !== $value && 0 === strcmp( $class_key, $value ) ? 'selected' : '',
				'icon' => current( $label ),
			]
		);
	}
	?>
</optgroup>
