<?php
/**
 * Lib of hooks for grid item attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Build css classes from terms of the post.
 *
 * @param string $value
 * @param array $data
 *
 * @return string
 * @since 4.4
 */
function vc_gitem_template_attribute_filter_terms_css_classes( $value, $data ) {
	$output = '';
	// @var null|Wp_Post $post - post object.
	extract( array_merge( [
		'post' => null,
	], $data ) );
	if ( isset( $post->filter_terms ) && is_array( $post->filter_terms ) ) {
		foreach ( $post->filter_terms as $t ) {
			$output .= ' vc_grid-term-' . $t; // @todo fix #106154391786878 $t is array
		}
	}

	return $output;
}

/**
 * Get image for post
 *
 * @param array $data
 * @return mixed|string
 */
function vc_gitem_template_attribute_post_image( $data ) {
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );
	if ( 'attachment' === $post->post_type ) {
		return wp_get_attachment_image( $post->ID, 'large' );
	}
	$html = get_the_post_thumbnail( $post->ID );

	return apply_filters( 'vc_gitem_template_attribute_post_image_html', $html );
}

/**
 * Retrieves and includes the featured image template for a grid item.
 *
 * @param string $value
 * @param array $data
 * @return mixed
 */
function vc_gitem_template_attribute_featured_image( $value, $data ) {
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );

	return vc_include_template( 'params/vc_grid_item/attributes/featured_image.php', [
		'post' => $post,
		'data' => $data,
	] );
}

/**
 * Create new btn.
 *
 * @param string $value
 * @param array $data
 *
 * @return mixed
 * @since 4.5
 */
function vc_gitem_template_attribute_vc_btn( $value, $data ) {
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );

	return vc_include_template( 'params/vc_grid_item/attributes/vc_btn.php', [
		'post' => $post,
		'data' => $data,
	] );
}

/**
 * Get post image url
 *
 * @param string $value
 * @param array $data
 *
 * @return string
 */
function vc_gitem_template_attribute_post_image_url( $value, $data ) {
	$output = '';
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );
	$extra_image_meta = explode( ':', $data );
	$size = 'large'; // default size.
	if ( isset( $extra_image_meta[1] ) ) {
		$size = $extra_image_meta[1];
	}
	if ( 'attachment' === $post->post_type ) {
		$src = vc_get_image_by_size( $post->ID, $size );
	} else {
		$attachment_id = get_post_thumbnail_id( $post->ID );
		$src = vc_get_image_by_size( $attachment_id, $size );
	}

	if ( ! empty( $src ) ) {
		$output = is_array( $src ) ? $src[0] : $src;
	} else {
		$output = vc_asset_url( 'vc/vc_gitem_image.png' );
	}

	return apply_filters( 'vc_gitem_template_attribute_post_image_url_value', $output );
}

/**
 * Get post image url.
 *
 * @param string $value
 * @param array $data
 *
 * @return string
 */
function vc_gitem_template_attribute_post_full_image_url( $value, $data ) {
	$output = '';
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );
	$extra_image_meta = explode( ':', $data );
	$size = 'full'; // default size.
	if ( isset( $extra_image_meta[1] ) ) {
		$size = $extra_image_meta[1];
	}
	if ( 'attachment' === $post->post_type ) {
		$src = vc_get_image_by_size( $post->ID, $size );
	} else {
		$attachment_id = get_post_thumbnail_id( $post->ID );
		$src = vc_get_image_by_size( $attachment_id, $size );
	}

	if ( ! empty( $src ) ) {
		$output = is_array( $src ) ? $src[0] : $src;
	} else {
		$output = vc_asset_url( 'vc/vc_gitem_image.png' );
	}

	return apply_filters( 'vc_gitem_template_attribute_post_image_url_value', $output );
}

/**
 * Get post image url with href for a dom element
 *
 * @param string $value
 * @param array $data
 *
 * @return string
 */
function vc_gitem_template_attribute_post_image_url_href( $value, $data ) {
	$link = vc_gitem_template_attribute_post_image_url( $value, $data );

	return strlen( $link ) ? ' href="' . esc_url( $link ) . '"' : '';
}

/**
 * Get post image url with href for a dom element
 *
 * @param string $value
 * @param array $data
 *
 * @return string
 */
function vc_gitem_template_attribute_post_full_image_url_href( $value, $data ) {
	$link = vc_gitem_template_attribute_post_full_image_url( $value, $data );

	return strlen( $link ) ? ' href="' . esc_url( $link ) . '"' : '';
}

/**
 * Add image url as href with css classes for lightbox js plugin.
 *
 * @param string $value
 * @param array $data
 *
 * @return string
 */
function vc_gitem_template_attribute_post_image_url_attr_lightbox( $value, $data ) {
	$data_default = $data;
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );
	$href = vc_gitem_template_attribute_post_full_image_url_href( $value, [
		'post' => $post,
		'data' => '',
	] );
	$rel = ' data-lightbox="' . esc_attr( 'lightbox[rel-' . md5( vc_request_param( 'shortcode_id' ) ) . ']' ) . '"';

	return $href . $rel . ' class="' . esc_attr( $data ) . '" title="' . esc_attr( apply_filters( 'vc_gitem_template_attribute_post_title', $post->post_title, $data_default ) ) . '"';
}

/**
 * Add image url as href with css classes for lightbox js plugin.
 *
 * @param string $value
 * @param array $data
 *
 * @return string
 */
function vc_gitem_template_attribute_post_full_image_url_attr_lightbox( $value, $data ) {
	$data_default = $data;
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );
	$href = vc_gitem_template_attribute_post_image_url_href( $value, [
		'post' => $post,
		'data' => '',
	] );
	$rel = ' data-lightbox="' . esc_attr( 'lightbox[rel-' . md5( vc_request_param( 'shortcode_id' ) ) . ']' ) . '"';

	return $href . $rel . ' class="' . esc_attr( $data ) . '" title="' . esc_attr( apply_filters( 'vc_gitem_template_attribute_post_title', $post->post_title, $data_default ) ) . '"';
}

/**
 * Loader for vc_gitem_template_attribute_post_image_url_attr_lightbox.
 *
 * @param string $value
 * @param array $data
 * @return string
 * @depreacted 6.6.0
 */
function vc_gitem_template_attribute_post_image_url_attr_prettyphoto( $value, $data ) {
	return vc_gitem_template_attribute_post_image_url_attr_lightbox( $value, $data );
}
/**
 * Loader for vc_gitem_template_attribute_post_full_image_url_attr_lightbox.
 *
 * @param string $value
 * @param array $data
 * @return string
 */
function vc_gitem_template_attribute_post_full_image_url_attr_prettyphoto( $value, $data ) {
	return vc_gitem_template_attribute_post_full_image_url_attr_lightbox( $value, $data );
}

/**
 * Get post image alt
 *
 * @param mixed $value
 * @param array $data
 * @return string
 */
function vc_gitem_template_attribute_post_image_alt( $value, $data ) {
	if ( empty( $data['post']->ID ) ) {
		return '';
	}

	if ( 'attachment' === $data['post']->post_type ) {
		$attachment_id = $data['post']->ID;
	} else {
		$attachment_id = get_post_thumbnail_id( $data['post']->ID );
	}

	if ( ! $attachment_id ) {
		return '';
	}

	$alt = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
	$title = trim( wp_strip_all_tags( $data['post']->post_title ) );

	if ( empty( $alt ) ) {
		$alt = trim( wp_strip_all_tags( $data['post']->post_excerpt ) ); // If not, Use the Caption.
	}
	if ( empty( $alt ) ) {
		$alt = $title;
	}

	return apply_filters( 'vc_gitem_template_attribute_post_image_url_value', $alt );
}

/**
 * Get post image url
 *
 * @param string $value
 * @param array $data
 * @return string
 */
function vc_gitem_template_attribute_post_image_background_image_css( $value, $data ) {
	$output = '';
	// @var null|Wp_Post $post - post object.
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );
	$size = 'large'; // default size.
	if ( ! empty( $data ) ) {
		$size = $data;
	}
	if ( 'attachment' === $post->post_type ) {
		$src = vc_get_image_by_size( $post->ID, $size );
	} else {
		$attachment_id = get_post_thumbnail_id( $post->ID );
		$src = vc_get_image_by_size( $attachment_id, $size );
	}
	if ( ! empty( $src ) ) {
		$output = 'background-image: url(\'' . ( is_array( $src ) ? $src[0] : $src ) . '\') !important;';
	} else {
		$output = 'background-image: url(\'' . vc_asset_url( 'vc/vc_gitem_image.png' ) . '\') !important;';
	}

	return apply_filters( 'vc_gitem_template_attribute_post_image_background_image_css_value', $output );
}

/**
 * Get post link.
 *
 * @param mixed $value
 * @param array $data
 * @return bool|string
 */
function vc_gitem_template_attribute_post_link_url( $value, $data ) {
	extract( array_merge( [
		'post' => null,
	], $data ) );

	return get_permalink( $post->ID );
}

/**
 * Get post date.
 *
 * @param mixed $value
 * @param array $data
 * @return bool|int|string
 */
function vc_gitem_template_attribute_post_date( $value, $data ) {
	extract( array_merge( [
		'post' => null,
	], $data ) );

	return get_the_date( '', $post->ID );
}

/**
 * Get post date time.
 *
 * @param string $value
 * @param array $data
 * @return bool|int|string
 */
function vc_gitem_template_attribute_post_datetime( $value, $data ) {
	extract( array_merge( [
		'post' => null,
	], $data ) );

	return get_the_time( 'F j, Y g:i', $post->ID );
}

/**
 * Get custom fields.
 *
 * @param mixed $value
 * @param array $data
 * @return mixed|string
 */
function vc_gitem_template_attribute_post_meta_value( $value, $data ) {
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );

	return strlen( $data ) > 0 ? get_post_meta( $post->ID, $data, true ) : $value;
}

/**
 * Get post data. Used as wrapper for others post data attributes.
 *
 * @param mixed $value
 * @param array $data
 * @return mixed|string
 */
function vc_gitem_template_attribute_post_data( $value, $data ) {
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );

	return strlen( $data ) > 0 ? apply_filters( 'vc_gitem_template_attribute_' . $data, ( isset( $post->$data ) ? $post->$data : '' ), [
		'post' => $post,
		'data' => '',
	] ) : $value;
}

/**
 * Get post excerpt. Used as wrapper for others post data attributes.
 *
 * @param mixed $value
 * @param array $data
 * @return mixed|string
 */
function vc_gitem_template_attribute_post_excerpt( $value, $data ) {
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );

	return apply_filters( 'the_excerpt', apply_filters( 'get_the_excerpt', $value, get_post( $post ) ) );
}

/**
 * Get post excerpt. Used as wrapper for others post data attributes.
 *
 * @param mixed $value
 * @param array $data
 * @return mixed|string
 */
function vc_gitem_template_attribute_post_title( $value, $data ) {
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );
	$id = 0;
	if ( isset( $data['post'] ) ) {
		$id = apply_filters( 'wpml_object_id', $id, 'post', true );
	}

	return get_the_title( $id );
}

/**
 * Get post author.
 *
 * @param mixed $value
 * @param array $data
 * @return string|null
 */
function vc_gitem_template_attribute_post_author( $value, $data ) {
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );

	return get_the_author();
}

/**
 * Get post author href.
 *
 * @param mixed $value
 * @param array $data
 * @return string
 */
function vc_gitem_template_attribute_post_author_href( $value, $data ) {
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );

	return get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) );
}

/**
 * Get post categories.
 *
 * @param mixed $value
 * @param array $data
 * @return mixed
 */
function vc_gitem_template_attribute_post_categories( $value, $data ) {
	extract( array_merge( [
		'post' => null,
		'data' => '',
	], $data ) );
	$atts_extended = [];
	parse_str( $data, $atts_extended );

	return vc_include_template( 'params/vc_grid_item/attributes/post_categories.php', [
		'post' => $post,
		'atts' => $atts_extended['atts'],
	] );
}

/**
 * Adding filters to parse grid template.
 */
add_filter( 'vc_gitem_template_attribute_filter_terms_css_classes', 'vc_gitem_template_attribute_filter_terms_css_classes', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_image', 'vc_gitem_template_attribute_post_image', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_full_image', 'vc_gitem_template_attribute_post_full_image', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_image_url', 'vc_gitem_template_attribute_post_image_url', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_full_image_url', 'vc_gitem_template_attribute_post_full_image_url', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_image_url_href', 'vc_gitem_template_attribute_post_image_url_href', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_full_image_url_href', 'vc_gitem_template_attribute_post_full_image_url_href', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_image_url_attr_prettyphoto', 'vc_gitem_template_attribute_post_image_url_attr_prettyphoto', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_full_image_url_attr_prettyphoto', 'vc_gitem_template_attribute_post_full_image_url_attr_prettyphoto', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_image_alt', 'vc_gitem_template_attribute_post_image_alt', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_link_url', 'vc_gitem_template_attribute_post_link_url', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_date', 'vc_gitem_template_attribute_post_date', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_datetime', 'vc_gitem_template_attribute_post_datetime', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_meta_value', 'vc_gitem_template_attribute_post_meta_value', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_data', 'vc_gitem_template_attribute_post_data', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_image_background_image_css', 'vc_gitem_template_attribute_post_image_background_image_css', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_excerpt', 'vc_gitem_template_attribute_post_excerpt', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_title', 'vc_gitem_template_attribute_post_title', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_author', 'vc_gitem_template_attribute_post_author', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_author_href', 'vc_gitem_template_attribute_post_author_href', 10, 2 );
add_filter( 'vc_gitem_template_attribute_post_categories', 'vc_gitem_template_attribute_post_categories', 10, 2 );
add_filter( 'vc_gitem_template_attribute_featured_image', 'vc_gitem_template_attribute_featured_image', 10, 2 );
add_filter( 'vc_gitem_template_attribute_vc_btn', 'vc_gitem_template_attribute_vc_btn', 10, 2 );
