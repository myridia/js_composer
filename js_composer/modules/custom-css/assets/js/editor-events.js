( function () {
	document.addEventListener( 'wpbAceEditorContentChanged', function ( event ) {
		if ( !event.detail.wpb_css_editor ) {
			return;
		}

		updateFrontEditorCurrentValue( event );
	});

	document.addEventListener( 'wpbPageSettingRollBack', function ( event ) {
		if ( !event.detail.wpb_css_editor ) {
			return;
		}

		updateFrontEditorCurrentValue( event );
		vc.$custom_css.val( event.detail.wpb_css_editor.previousValue );
	});

	function updateFrontEditorCurrentValue ( event ) {
		var currentValue = event.detail.wpb_css_editor.currentValue;
		if ( vc.frame_window ) {
			vc.frame_window.vc_iframe.loadCustomCss( currentValue );
		}
	}
}() );
