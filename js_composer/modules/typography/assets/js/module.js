jQuery( document ).ready( function ( $ ) {
	$( '#vc_synchronize_google_fonts_button' ).on( 'click', function ( e ) {
		if ( $( this ).attr( 'disabled' ) ) {
			e.preventDefault();
			return;
		}
		process_google_fonts_sync( e, this );
	});

	$( '#vc_synchronize_adobe_fonts_button' ).on( 'click', function ( e ) {
		if ( $( this ).attr( 'disabled' ) ) {
			e.preventDefault();
			return;
		}
		process_adobe_fonts_sync( e, this );
	});

	function process_google_fonts_sync ( e, _this ) {
		e.preventDefault();
		add_preloader( _this );

		var data = {
			action: 'wpb_google_fonts',
			_vcnonce: window.vcAdminNonce
		};

		$.ajax({
			type: 'POST',
			url: window.ajaxurl,
			data: data
		}).done( function ( response ) {
			if ( response.success ) {
				add_field_message( _this, window.i18nLocaleSettings.google_fonts_synced, 'success' );
			} else {
				add_field_message( _this, response.data );
				add_field_message( _this, response.data, 'error' );
			}
		}).fail( function () {
			add_field_message( _this, window.i18nLocaleSettings.google_fonts_sync_failed, 'error' );
		});
	}

	function process_adobe_fonts_sync ( e, _this ) {
		e.preventDefault();
		add_preloader( _this );
		var adobe_id_input = $( '#wpb_js_adobe_fonts_web_project_id' );
		var web_project_id = adobe_id_input.val();
		if ( ! web_project_id ) {
			add_field_message( _this, window.i18nLocaleSettings.enter_adobe_sync_web_project_id, 'error' );
			return;
		}

		var data = {
			action: 'wpb_adobe_set_fonts',
			web_project_id: web_project_id,
			_vcnonce: window.vcAdminNonce
		};

		$.ajax({
			type: 'POST',
			url: window.ajaxurl,
			data: data
		}).done( function ( response ) {
			if ( response.success ) {
				var hidden_input = $( 'input[name="wpb_js_adobe_fonts_data"]' );
				hidden_input.val( response.data.body );
				add_field_message( _this, window.i18nLocaleSettings.adobe_fonts_synced, 'success' );
			} else {
				add_field_message( _this, response.data, 'error' );
			}
		}).fail( function () {
			add_field_message( _this, window.i18nLocaleSettings.adobe_fonts_sync_failed, 'error' );
		});
	}

	function add_field_message ( _this, message, result ) {
		var all_messages = $( '.wpb_message_placeholder' );
		all_messages.hide();
		var message_placeholder = $( '.wpb_message_placeholder.notice.notice-' + result );
		message_placeholder.show();

		if ( message_placeholder.length ) {
			message_placeholder.find( 'p' ).html( message );
		}
		remove_preloader( _this );
	}

	function add_preloader ( element ) {
		var preloader = '<span class="vc_ui-wp-spinner vc_ui-wp-spinner-dark"></span>';
		var elementObj = $( element );
		elementObj.prepend( preloader );
		elementObj.attr( 'disabled','disabled' );
	}

	function remove_preloader ( element ) {
		var elementObj = $( element );
		$( element ).find( '.vc_ui-wp-spinner' ).remove();
		elementObj.removeAttr( 'disabled' );
	}
});
