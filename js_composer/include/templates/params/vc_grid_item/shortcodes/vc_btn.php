<?php
/**
 * Button grid builder shortcode element.
 *
 * @var array $atts
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return '{{ vc_btn: ' . http_build_query( $atts ) . ' }}';
