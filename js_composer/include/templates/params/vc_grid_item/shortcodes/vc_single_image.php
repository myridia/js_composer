<?php
/**
 * Single image grid builder shortcode element.
 *
 * @var WPBakeryShortCode_Vc_Single_image $this
 * @var array $atts
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$el_class = $image = $img_size = $img_link = $img_link_target = $img_link_large = $title = $alignment = $css_animation = $css = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$default_src = vc_asset_url( 'vc/no_image.png' );
$style = ( '' !== $style ) ? $style : '';
$border_color = ( '' !== $border_color ) ? ' vc_box_border_' . $border_color : '';

$img_id = preg_replace( '/[^\d]/', '', $image );

switch ( $source ) {
	case 'media_library':
		$img = wpb_getImageBySize( [
			'attach_id' => $img_id,
			'thumb_size' => $img_size,
			'class' => 'vc_single_image-img',
		] );

		break;

	case 'external_link':
		$dimensions = vc_extract_dimensions( $img_size );
		$hwstring = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';

		$custom_src = $custom_src ? esc_attr( $custom_src ) : $default_src;

		$img = [
			'thumbnail' => '<img class="vc_single_image-img" ' . $hwstring . ' src="' . esc_url( $custom_src ) . '" />',
		];
		break;

	default:
		$img = false;
}

if ( ! $img ) {
	$img = is_array( $img ) ? $img : [];
	$img['thumbnail'] = '<img class="vc_single_image-img" src="' . esc_url( $default_src ) . '" />';
}

$wrapper_class = 'vc_single_image-wrapper ' . $style . ' ' . $border_color;
$link = vc_gitem_create_link( $atts, $wrapper_class );

$image_string = ! empty( $link ) ? '<' . $link . '>' . $img['thumbnail'] . '</a>' : '<div class="' . $wrapper_class . '"> ' . $img['thumbnail'] . ' </div>';

$class_to_filter = 'wpb_single_image wpb_content_element vc_align_' . $alignment . ' ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$output = '
	<div class="' . esc_attr( $css_class ) . '">
		' . wpb_widget_title( [
	'title' => $title,
	'extraclass' => 'wpb_singleimage_heading',
] ) . '
		<figure class="wpb_wrapper vc_figure">
			' . $image_string . '
		</figure>
	</div>
';

return $output;
