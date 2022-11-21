
( function ( $ ) {
	/**
	 * Class Loadmore.
	 */
	class FUTUREWORDPRESS_PROJECT_FROTEND_MAIN {
		/**
		 * Contructor.
		 */
		constructor() {
			this.ajaxUrl = siteConfig?.ajaxUrl ?? '';
			this.ajaxNonce = siteConfig?.ajax_nonce ?? '';
			this.iSheduled = siteConfig?.iSheduled ?? false;
			this.defaulTime = siteConfig?.defaulTime ?? '12:00:00 AM';
			this.hideSubmit = siteConfig?.hideSubmit ?? false;
      
			this.init();
		}

		init() {
			const thisClass = this;
			if( thisClass.iSheduled ) {
				thisClass.interVal = setInterval( () => {
					thisClass.sheduleButton();
					if( thisClass.hideSubmit ) {
						thisClass.hideSubmitBtn();
					}
				}, 1000 );
			}
    }
		sheduleButton() {
			const thisClass = this;var selector, hasit, hasName, div, button, input, node, span, a;
			selector = '.fwp-bsp #buddypress form#whats-new-form #whats-new-submit';
			hasName = 'fwp-whats-new-submit-shedule';
			hasit = document.querySelectorAll( selector + ' #' + hasName );
			if( hasit.length <= 0 ) {
				input = document.createElement( 'input' );input.type = 'submit';input.id = hasName;input.classList.add( 'button' );input.name = hasName;input.value = 'Shedule';
				if( document.querySelector( selector ) ) {
					document.querySelector( selector ).insertBefore( input, document.querySelector( selector + ' #aw-whats-new-submit' ) );
					document.querySelector( selector + ' #' + hasName ).addEventListener( 'click', function( e ) {
						e.preventDefault();
						document.querySelector( '.fwp-bsp .fwp-bsp-shedule-field' ).classList.toggle( 'fwp-bsp-visible' );
						node = document.querySelector( '.fwp-bsp .fwp-bsp-shedule-field #fwp-bsp-sheduled-action' );
						if( node ) {
							switch ( node.value ) {
								case '':
									node.value = 'hasit';
									break;
								default:
									node.value = '';
									break;
							}
						}
					} );
					// clearInterval( thisClass.interVal );
				}
			}
		}
		hideSubmitBtn() {
			const thisClass = this;var selector, hasit, hasName, div, button, input, node, span, a;
			selector = '.fwp-bsp #buddypress form#whats-new-form #whats-new-submit';
			hasit = document.querySelectorAll( selector + ' #aw-whats-new-submit' );
			if( hasit.length >= 1 ) {
				hasit.forEach( function( e, i ) {
					e.remove();
				} );
			}
		}
	}

	new FUTUREWORDPRESS_PROJECT_FROTEND_MAIN();
} )( jQuery );
