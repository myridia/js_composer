( function () {
	document.addEventListener( 'wpbAceEditorContentChanged', function ( event ) {
		updateFrontEditorCurrentValue( event );
	});

	document.addEventListener( 'wpbPageSettingRollBack', function ( event ) {
		updateFrontEditorCurrentValue( event );

		if ( event.detail.wpb_js_header_editor ) {
			vc.$custom_js_header.val( event.detail.wpb_js_header_editor.previousValue );
		}
		if ( event.detail.wpb_js_footer_editor ) {
			vc.$custom_js_footer.val( event.detail.wpb_js_footer_editor.previousValue );
		}
	});

	function updateFrontEditorCurrentValue ( event ) {
		if ( !vc.frame_window ) {
			return;
		}

		if ( event.detail.wpb_js_header_editor ) {
			var currentValue = event.detail.wpb_js_header_editor.currentValue;
			vc.frame_window.vc_iframe.loadCustomJsHeader( currentValue );
		}

		if ( event.detail.wpb_js_footer_editor ) {
			var currentValue = event.detail.wpb_js_footer_editor.currentValue;
			vc.frame_window.vc_iframe.loadCustomJsFooter( currentValue );
		}
	}
}() );
