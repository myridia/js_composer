<?php
/**
 * Color picker settings template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>


<div id='picker-preview-container'>
	<p> <?php esc_html_e( 'Define the color schema available in the WPBakery color picker as color presets. Add your brand or frequently used colors for quick access. These settings affect only the color picker and will not affect any theme settings. ', 'js_composer' ); ?></p>
	<div id='preview-picker'></div>
</div>
