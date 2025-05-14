<?php
/**
 * Adobe fonts settings field button sync template.
 *
 * @var string $field_value
 * @var string $field_name
 */

?>

<div class="vc-settings-adobe-fonts">
	<?php
	vc_include_template(
		'editors/partials/param-info.tpl.php',
		[
			'print' => true,
			'format' => 'Synchronize Adobe Fonts to update the font list with your Adobe web project fonts. You can find your Adobe Project ID %s here %s.',
			'format_arguments' => [
				'<a href="https://fonts.adobe.com/my_fonts#web_projects-section" target="_blank">',
				'</a>',
			],
		]
	);
	?>
	<input id="<?php echo esc_attr( $field_name ); ?>" type="text" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $field_value ); ?>" class="css-control">
	<a href="#" class="button" id="vc_synchronize_adobe_fonts_button"><?php esc_html_e( 'Synchronize', 'js_composer' ); ?></a>
</div>
