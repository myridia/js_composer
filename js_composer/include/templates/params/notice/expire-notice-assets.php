<?php
/**
 * Expire notice param assets template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<script>
	window.vcAdminNonce = '<?php echo esc_js( vc_generate_nonce( 'vc-admin-nonce' ) ); ?>';

	(function ( $ ) {
		var dismissExpireNoticeList = function () {
			var data = {
				action: 'wpb_dismiss_expire_notice',
				_vcnonce: window.vcAdminNonce
			};
			$.ajax( {
				type: 'POST',
				url: window.ajaxurl,
				data: data,
			}).fail( function ( response ) {
				console.error( 'Failed to add notice to disable list', response)
			});
		};

		$(document).off('click.notice-dismiss').on('click', '.wpb-expire-notice .notice-dismiss', function (e) {
			e.preventDefault();
			dismissExpireNoticeList();
		});
	})( window.jQuery );
</script>
