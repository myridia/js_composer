<?php
/**
 * Template for element param google fonts.
 *
 * @var array $settings
 * @var string $value
 * @var array $fields
 * @var array $values
 * @var Vc_Google_Fonts $this
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="vc_row-fluid vc_column">
	<div class="wpb_element_label"><?php esc_html_e( 'Font Family', 'js_composer' ); ?></div>
	<div class="vc_google_fonts_form_field-font_family-container">
		<select class="vc_google_fonts_form_field-font_family-select"
				default[font_style]="<?php echo esc_attr( $values['font_style'] ); ?>">
			<?php
			$fonts = $this->_vc_google_fonts_get_fonts();
			$typography_module = vc_modules_manager()->get_module( 'vc-typography' );
			$separator_list = [
				$typography_module->adobe_controller->get_adobe_dropdown_separator(),
				$typography_module->get_default_dropdown_separator(),
			];

			foreach ( $fonts as $font_data ) :
				$is_separator = in_array( $font_data->font_family, $separator_list );
				$is_disabled = false;
				$font_family_slug = empty( $font_data->font_family_slug ) ? $font_data->font_family : $font_data->font_family_slug;
				// we use google as default vendor.
				$font_vendor = empty( $font_data->font_vendor ) ? '' : $font_data->font_vendor;
				$font_url = empty( $font_data->font_url ) ? '' : $font_data->font_url;
				if ( $is_separator ) {
					$is_disabled = true;
				}
				$font_family_low_case = strtolower( $font_family_slug );
				$value_family_low_case = strtolower( $values['font_family'] );
				$font_family_low_case_with_style = $font_family_low_case . ':' . $font_data->font_styles;
				$selected =
					$value_family_low_case === $font_family_low_case ||
					$value_family_low_case === $font_family_low_case_with_style ?
						'selected' : '';
				?>
				<option value="<?php echo esc_attr( $font_family_slug ) . ':' . esc_attr( $font_data->font_styles ); ?>"
					<?php echo $is_disabled ? 'disabled="disabled"' : ''; ?>
					data[font_types]="<?php echo esc_attr( $font_data->font_types ); ?>"
					data[font_family]="<?php echo esc_attr( $font_family_slug ); ?>"
					data[font_styles]="<?php echo esc_attr( $font_data->font_styles ); ?>"
					data[font_vendor]="<?php echo esc_attr( $font_vendor ); ?>"
					data[font_url]="<?php echo esc_attr( $font_url ); ?>"
					class="<?php echo esc_attr( vc_build_safe_css_class( $font_data->font_family ) ); ?>"
					<?php echo esc_attr( $selected ); ?>
				><?php echo esc_html( $font_data->font_family ); ?></option>
			<?php endforeach ?>
		</select>
	</div>
	<?php if ( isset( $fields['font_family_description'] ) && strlen( $fields['font_family_description'] ) > 0 ) : ?>
		<span class="vc_description clear"><?php echo esc_html( $fields['font_family_description'] ); ?></span>
	<?php endif ?>
</div>

<?php if ( isset( $fields['no_font_style'] ) && false === $fields['no_font_style'] || ! isset( $fields['no_font_style'] ) ) : ?>
	<div class="vc_row-fluid vc_column">
		<div class="wpb_element_label"><?php esc_html_e( 'Font style', 'js_composer' ); ?></div>
		<div class="vc_google_fonts_form_field-font_style-container">
			<select class="vc_google_fonts_form_field-font_style-select"></select>
		</div>
	</div>
	<?php if ( isset( $fields['font_style_description'] ) && strlen( $fields['font_style_description'] ) > 0 ) : ?>
		<span class="vc_description clear"><?php echo esc_html( $fields['font_style_description'] ); ?></span>
	<?php endif ?>
<?php endif ?>

<div class="vc_row-fluid vc_column vc_google_fonts_form_field-preview-wrapper">
	<div class="wpb_element_label"><?php esc_html_e( 'Font preview', 'js_composer' ); ?>:</div>
	<div class="vc_google_fonts_form_field-preview-container">
		<span><?php esc_html_e( 'Grumpy wizards make toxic brew for the evil Queen and Jack.', 'js_composer' ); ?></span>
	</div>
	<div class="vc_google_fonts_form_field-status-container"><span></span></div>
</div>

<input name="<?php echo esc_attr( $settings['param_name'] ); ?>"
		class="wpb_vc_param_value  <?php echo esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ); ?>_field" type="hidden"
		value="<?php echo esc_attr( $value ); ?>"/>
