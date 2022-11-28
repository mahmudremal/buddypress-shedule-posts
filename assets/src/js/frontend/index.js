
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
			this.iScheduled = siteConfig?.iScheduled ?? false;
			this.defaulTime = siteConfig?.defaulTime ?? '12:00:00 AM';
			this.hideSubmit = siteConfig?.hideSubmit ?? false;
			this.confirmDelete = siteConfig?.confirmDelete ?? 'Click okay to make sure you want to delete it.';
			this.calendar = null;
      
			this.init();
		}

		init() {
			const thisClass = this;
			if( thisClass.iScheduled ) {
				thisClass.interVal = setInterval( () => {
					thisClass.scheduleButton();
					if( thisClass.hideSubmit ) {
						thisClass.hideSubmitBtn();
					}
					thisClass.startCountDown();
					thisClass.removeRandomUploader();
					thisClass.activityDelete();
					thisClass.managePopup();
				}, 1000 );
				thisClass.ajaxLoad();
			}
    }
		scheduleButton() {
			const thisClass = this;var selector, hasit, hasName, div, button, input, node, span, a;
			selector = '.fwp-bsp #buddypress form#whats-new-form #whats-new-submit';
			hasName = 'fwp-whats-new-submit-schedule';
			hasit = document.querySelectorAll( selector + ' #' + hasName );
			if( hasit.length <= 0 ) {
				input = document.createElement( 'input' );input.type = 'submit';input.id = hasName;input.classList.add( 'button' );input.name = hasName;input.value = 'Schedule';
				if( document.querySelector( selector ) ) {
					document.querySelector( selector ).insertBefore( input, document.querySelector( selector + ' #aw-whats-new-submit' ) );
					document.querySelector( selector + ' #' + hasName ).addEventListener( 'click', function( e ) {
						e.preventDefault();
						document.querySelector( '.fwp-bsp .fwp-bsp-schedule-field' ).classList.toggle( 'fwp-bsp-visible' );
						node = document.querySelector( '.fwp-bsp .fwp-bsp-schedule-field #fwp-bsp-scheduled-action' );
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
		startCountDown() {
			document.querySelectorAll( '.fwp-bsf-post-schedule-timer' ).forEach( function( e, i ) {
				if( e.dataset.handled && e.dataset.handled == true ) {
					// Exists.
				} else {
					e.dataset.handled = true;var wrap = '.fwp-bsf-schedule-wrap ';
					var countDownDate = new Date( e.dataset.timing ).getTime();
					// Update the count down every 1 second
					var counterX = setInterval(function() {
						// Get today's date and time
						var now = new Date().getTime();

						// Find the distance between now and the count down date
						var distance = countDownDate - now;

						// Time calculations for days, hours, minutes and seconds
						var days = Math.floor(distance / (1000 * 60 * 60 * 24));
						var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
						var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((distance % (1000 * 60)) / 1000);

						// Display the result in the element with id="demo"
						// document.getElementById("demo").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
						e.querySelector( wrap + '.fwp-bsf-day' ).innerHTML = days;
						e.querySelector( wrap + '.fwp-bsf-hour' ).innerHTML = hours;
						e.querySelector( wrap + '.fwp-bsf-minute' ).innerHTML = minutes;
						e.querySelector( wrap + '.fwp-bsf-second' ).innerHTML = seconds;

						// If the count down is finished, write some text
						if (distance < 0) {
							clearInterval( counterX );
							[ 'day', 'hour', 'minute', 'second' ].forEach( function( f, j ) {
								e.querySelector( wrap + '.fwp-bsf-' + f ).innerHTML = ( e.dataset.nothing ) ? e.dataset.nothing : '00';
							} );
						}
					}, 1000);
				}
			} );
		}
		removeRandomUploader() {
			document.querySelectorAll( '.fwp-bsp #buddypress #whats-new-form > .rtmedia-uploader-div' ).forEach( function( e, i ) {
				if( i !== 0 ) {
						e.remove();
				}
			} );
		}

		fullCalander( events ) {
			const thisClass = this;var calendar;
			// document.addEventListener('DOMContentLoaded', function() {
				var calendarEl = document.getElementById('fwp-schedule-calendar');
				if( calendarEl ) {
					thisClass.calendar = new FullCalendar.Calendar(calendarEl, {
						initialView: 'dayGridMonth',
						initialDate: new Date,
						headerToolbar: {
							left: 'prev,next today',
							center: 'title',
							right: 'dayGridMonth,timeGridWeek,timeGridDay'
						},
						// headerToolbar: { center: 'dayGridMonth,timeGridWeek' }, // buttons for switching between views
						// events: '/myfeed.php'
						// events: thisClass.ajaxLoad(),
						events: events,
						// events: [
						// 	{
						// 		id: '3',
						// 		title: "Event with HTML !",
						// 		start: "2022-11-10",
						// 		extendedProps: {
						// 			isHTML: true,
						// 			html: '<a href="#">Company name B<img src="https://github.githubassets.com/images/icons/emoji/unicode/1f64f.png"/></a>',
						// 		},
						// 		// url: 'http://google.com/',
						// 		// textEscape: true
						// 	},
						// ],
						// textEscape: false,   // new option to disable the auto escape
						// color: "yellow",   // an option!
						// textColor: "black", // an option!
						// eventRender: function( event, element ) {
						// 	// element.find(".fc-title").append('<strong>bla bla bla</strong>');
						// },
						eventContent: function(arg) {
							let divEl = document.createElement( 'div' );var node, dom, div, elem, span, a, i, img;
							// console.log( arg );
							if( arg.event._def.extendedProps.isHTML && arg.event._def.extendedProps.html ) {
								divEl.innerHTML = arg.event._def.extendedProps.html;
							} else {
								divEl.innerHTML = arg.event.title;
							}
							let arrayOfDomNodes = [ divEl ];
							return { domNodes: arrayOfDomNodes };
						}
					} );
					thisClass.calendar.render();
					// thisClass.dateClick();
				}
			// });
		}
		dateClick() {
			const thisClass = this;
			thisClass.calendar.on('dateClick', function(info) {
				console.log( info );
				console.log('clicked on ' + info.dateStr);
			});
		}
		ajaxLoad() {
			const thisClass = this;
			// https://stackoverflow.com/questions/12019130/how-can-i-load-all-events-on-calendar-using-ajax
			$.ajax({
				url: thisClass.ajaxUrl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'fwp_bsp_get_user_scheduled_posts',
					nonce: thisClass.ajaxNonce,
					currentab: 'upcoming',
					// start: start.format(),
					// end: end.format()
				},
				success: function( json ) {
					var events = [];
					if( json.success ) {
						if( true ) {
							events = json.data;
						} else {
							$.map( json.data, function( row ) {
								events.push({
									id: row.id,
									title: row.title,
									start: row.date_start,
									end: row.date_end
								} );
							} );
						}
						thisClass.fullCalander( events );
					}
					// console.log( events, json );
					// callback(events);
					return events;
				}
			} );

		}
		activityDelete() {
			const thisClass = this;
			document.querySelectorAll( '.fwp-bsp-EventDelete:not([data-handled])' ).forEach( function( e, i ) {
				e.dataset.handled = true;
				e.addEventListener( 'click', function( event ) {
					event.preventDefault();var elem = this;
					if( e.dataset.activity && ( e.dataset.activity != '' ) && confirm( thisClass.confirmDelete ) ) {
						jQuery.ajax( {
							url: thisClass.ajaxUrl,
							type: 'POST',
							dataType: 'json',
							data: {
								action: 'fwp_bsp_shedule_post_delete',
								nonce: thisClass.ajaxNonce,
								activity: e.dataset.activity,
							},
							success: function( json ) {
								if( json.success ) {
									alert( json.data );
									$( elem ).parent( '.fwp-bsp--event-post' ).remove();
								} else {
									if( json.data ) {
										alert( json.data );
									} else {
										// console.log( json );
									}
								}
							},
							error: function( err ) {
								console.log( err );
							}
						} );
					}
				} );
			} );
		}
		managePopup() {
			const thisClass = this;var popup;popup = document.getElementById( 'fwp-sheduled-activity-edit-form' );
			document.querySelectorAll( '.fwp-bsp-quickedit:not([data-handled])' ).forEach( function( e, i ) {
				e.dataset.handled = true;
				e.addEventListener( 'click', function( event ) {
					event.preventDefault();
					if( popup ) {
						thisClass.openPopup( popup, e, this );
					}
				} );
			} );
			thisClass.fixPopup( popup );
		}
		openPopup( popup, e, that ) {
			const thisClass = this;
			popup.classList.add( 'reign-window-popup-open' );
			document.body.classList.add( 'modal-active' );
			if( e.dataset.content && e.dataset.content != '' ) {
				document.getElementById( 'fwp-bsp-activity-content' ).innerHTML = e.dataset.content;
			}
			if( e.dataset.schedule && e.dataset.schedule != '' ) {
				document.getElementById( 'fwp-bsp-activity-schedule' ).value = e.dataset.schedule;
			}
			if( e.dataset.activity && e.dataset.activity != '' ) {
				document.getElementById( 'fwp-bsp-activity-id' ).value = e.dataset.activity;
			}
		}
		closePopup( popup ) {
			const thisClass = this;
			popup.classList.remove( 'reign-window-popup-open' );
			document.body.classList.remove( 'modal-active' );
			document.getElementById( 'fwp-bsp-activity-content' ).innerHTML = '';
			document.getElementById( 'fwp-bsp-activity-schedule' ).value = '';
		}
		fixPopup( popup ) {
			const thisClass = this;var dom;
			dom = document.querySelector( '#fwp-sheduled-activity-edit-form' );
			if( dom ) {
				dom.addEventListener( 'click', function( event ) {
					thisClass.closePopup( popup );
				} );
			}
			dom = document.querySelector( '#fwp-sheduled-activity-edit-form .modal-content .modal-body' );
			if( dom ) {
				dom.addEventListener( 'click', function( event ) {
					event.stopPropagation();
				} );
			}
			dom = document.querySelector( '#fwp-bsp-close-popup' );
			if( dom ) {
				dom.addEventListener( 'click', function( event ) {
					thisClass.closePopup( popup );
				} );
			}
		}
	}

	new FUTUREWORDPRESS_PROJECT_FROTEND_MAIN();
} )( jQuery );
