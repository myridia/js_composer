<?php
/**
 * Get more navbar menu template.
 *
 * @var $_this Vc_Navbar
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

?>

<li class="vc_pull-right vc_hide-desktop vc_show-mobile">
	<div class="vc_dropdown vc_dropdown-more" id="vc_more-options">
		<a class="vc_dropdown-toggle vc_icon-btn" title="More">
			<i class="vc-composer-icon vc-c-icon-more"></i>
		</a>
		<ul class="vc_dropdown-list">
			<?php
			$_this->outputGetMoreMenuButtons();
			?>
		</ul>
	</div>
</li>
