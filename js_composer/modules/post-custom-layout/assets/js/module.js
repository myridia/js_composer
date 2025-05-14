jQuery( document ).ready( function ( $ ) {

	$( '.vc_post-custom-layout' ).on( 'click', selectLayout );
	$( '#vc_ui-panel-post-settings .vc_post-custom-layout' )
		.on( 'click', setLayoutToPageSettingsStorage )
		.on( 'click', setSettingsLayout );
	$( document ).on( 'wpbPageSettingRollBack', function ( event ) {
		if ( event.detail.custom_layout ) {
			switchLayout( event.detail.custom_layout.currentValue );
		}
	});

	function selectLayout ( e ) {
		var selectedLayout = $( e.currentTarget );
		var layoutName = selectedLayout.attr( 'data-post-custom-layout' );
		var editorWrapper = $( '#wpb_wpbakery' );

		$( '#vc_settings-post_template' ).toggle( layoutName !== 'blank' );

		// add class that help us to hide some elements on a page that should not
		// be visible when layout is selected
		if( editorWrapper ) {
			selectedLayout = $( '#vc_ui-panel-post-settings .vc_post-custom-layout[data-post-custom-layout=' + layoutName + ']' );

			editorWrapper.find( '.vc_navbar' ).addClass( 'vc_post-custom-layout-selected' );
			editorWrapper.find( '.metabox-composer-content' ).addClass( 'vc_post-custom-layout-selected' );
		}

		switchLayout( layoutName, selectedLayout );
	}

	function switchLayout ( layoutName, selectedLayout = false ) {
		if ( ! selectedLayout ) {
			selectedLayout = $( '.vc_post-custom-layout[data-post-custom-layout=' + layoutName + ']' );
		}

		selectedLayout.addClass( 'vc-active-post-custom-layout' );
		selectedLayout.siblings().removeClass( 'vc-active-post-custom-layout' );

		// set input that help us save layout values to post meta
		$( 'input[name=vc_post_custom_layout]' ).val( layoutName );
	}

	function setSettingsLayout ( e ) {
		if ( window.vc_mode !== 'admin_frontend_editor' ) {
			return;
		}

		e.preventDefault();

		var currentUrl = new URL( window.location.href );
		var params = currentUrl.searchParams;

		params.delete( 'vc_post_custom_layout' );

		// Update the browser's URL without reloading the page
		window.history.replaceState({}, '', currentUrl.origin + currentUrl.pathname + '?' + params.toString() );

		selectLayout( e );
	}

	function setLayoutToPageSettingsStorage ( e ) {
		var selectedLayout = $( e.currentTarget );
		var currentValue = selectedLayout.attr( 'data-post-custom-layout' );
		var previousValue = currentValue === 'default' ? 'blank' : 'default';
		var id = 'custom_layout';
		var payload = window.vc.pagesettingseditor || {};
		payload[ id ] = {
			currentValue: currentValue,
			previousValue: previousValue
		};

		window.vc.pagesettingseditor = payload;
	}
});
