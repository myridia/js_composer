<?php
/**
 * Get navbar template button.
 *
 * @var $_this Vc_Navbar
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

?>

<li>
	<a href="javascript:;" class="vc_icon-btn vc_templates-button"  id="vc_templates-editor-button" title="<?php esc_attr__( 'Templates', 'js_composer' ); ?>">
		<i class="vc-composer-icon vc-c-icon-add_template"></i>
	</a>
</li>
