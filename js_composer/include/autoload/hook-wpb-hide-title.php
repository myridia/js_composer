<?php
/**
 * Autoload hooks plugin hide title functionality.
 *
 * @note we require our autoload files everytime and everywhere after plugin load.
 * @since 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

add_filter( 'the_title', function ( $title, $post_id = null ) {
	if ( empty( $post_id ) ) {
		return $title;
	}

	if ( wpb_is_hide_title( $post_id ) ) {
		return '';
	}

	return $title;
}, 10, 2 );
