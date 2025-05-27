<?php
/**
 * Backend editor footer template.
 *
 * @var Vc_Backend_Editor $editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$post = $editor->post;

vc_include_template( 'editors/partials/footer.tpl.php',
	[
		'editor' => $editor,
		'post' => $post,
	]
);

// [shortcode edit layout]
vc_include_template( 'editors/partials/backend-shortcodes-templates.tpl.php' );
