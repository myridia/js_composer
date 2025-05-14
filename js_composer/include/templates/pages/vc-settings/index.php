<?php
/**
 * Settings page wrapper template.
 *
 * @var Vc_Page $active_page
 * @var array $pages
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="wrap vc_settings" id="wpb-js-composer-settings">
	<h2><?php esc_html_e( 'WPBakery Page Builder Settings', 'js_composer' ); ?></h2>
	<?php settings_errors(); ?>
	<?php
	vc_include_template( '/pages/partials/_settings_tabs.php',
	[
		'active_tab' => $active_page->getSlug(),
		'tabs' => $pages,
	] );
	?>
	<?php $active_page->render(); ?>
</div>
