( function() {
	document.addEventListener( 'DOMContentLoaded', function() {
		var notice = document.getElementById( 'lcn-notice' );

		if ( ! notice ) {
			return;
		}

		var key = notice.getAttribute( 'data-dismiss-key' );
		var isDismissed = false;

		try {
			isDismissed = key && window.localStorage.getItem( key ) === '1';
		} catch ( e ) {}

		if ( isDismissed ) {
			notice.remove();
			return;
		}

		notice.classList.add( 'lcn-visible' );

		var dismiss = function() {
			try {
				if ( key ) {
					window.localStorage.setItem( key, '1' );
				}
			} catch ( e ) {}
			notice.remove();
		};

		var closeBtn = notice.querySelector( '.lcn-close' );
		if ( closeBtn ) {
			closeBtn.addEventListener( 'click', dismiss );
		}

		var overlay = notice.querySelector( '.lcn-overlay' );
		if ( overlay ) {
			overlay.addEventListener( 'click', function() {
				if ( closeBtn ) {
					dismiss();
				}
			} );
		}
	} );
} )();
