
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
			this.onDragConfirm = siteConfig?.onDragConfirm ?? false;
			this.confirmDelete = siteConfig?.confirmDelete ?? 'Click okay to make sure you want to delete it.';
			this.confirmSwitch = siteConfig?.confirmSwitch ?? "Are you sure about this change?\nClick on Cancel to dismiss.";
			this.calendar = null;this.editor = false;
			this.standardForm = false;
			this.fixedPopup = false;
      
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
					thisClass.editBtnHighlight();
					thisClass.removeRandomUploader();
					thisClass.activityDelete();
					thisClass.managePopup();
					thisClass.iframeModel();
					// thisClass.activityOnly();
				}, 1000 );
				thisClass.ajaxLoad();
				thisClass.fixCustomTabIssue();
				( ! thisClass.standardForm ) || thisClass.fetchActivityForm();
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
			node = document.querySelector( '.fwp-bsp .fwp-bsp-schedule-field #fwp-bsp-scheduled-timezone:not(.has_handled)' );
			if( node ) {
				node.value = ( Intl ) ? Intl.DateTimeFormat().resolvedOptions().timeZone : '';
				node.classList.add( 'has_handled' );
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
						eventLimit: true,
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
						eventContent: function( arg ) {
							let divEl = document.createElement( 'div' );var node, dom, div, elem, span, a, i, img;
							// console.log( arg );
							if( arg.event._def.extendedProps.isHTML && arg.event._def.extendedProps.html ) {
								divEl.innerHTML = arg.event._def.extendedProps.html;
							} else {
								divEl.innerHTML = arg.event.title;
							}
							let arrayOfDomNodes = [ divEl ];
							return { domNodes: arrayOfDomNodes };
						},
						editable: true,
						eventDrop: function( info ) {
							thisClass.eventDrop( info );
						},
						// eventRender: function( info ) {
						// }
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
			// document.querySelectorAll( '.fwp-bsp-quickedit:not([data-handled])' ).forEach( function( e, i ) {
			// 	e.dataset.handled = true;
			// 	e.addEventListener( 'click', function( event ) {
			// 		event.preventDefault();
			// 		if( popup ) {
			// 			thisClass.openPopup( popup, e, this );
			// 		}
			// 	} );
			// } );
			thisClass.fixPopup( popup );
		}
		openPopup( popup, e, that, tab = false ) {
			const thisClass = this;var id, wrap;
			popup.classList.add( 'reign-window-popup-open' );
			document.body.classList.add( 'modal-active' );
			if( thisClass.standardForm && tab ) {
				wrap = '.fwp-bsp-schedule-field .fwp-bsp-schedule-wrap .fwp-bsp-schedule';
				if( typeof e.dataset !== undefined && e.dataset.content != '' ) {
					document.getElementById( 'whats-new' ).innerHTML = e.dataset.content;
				}
				if( typeof e.dataset !== undefined && e.dataset.schedule != '' ) {
					document.querySelector( wrap + ' input.ac-input' ).value = e.dataset.schedule;
				}
				if( typeof e.dataset !== undefined && e.dataset.activity != '' ) {
					id = document.createElement( 'input' );
					id.value = e.dataset.activity;
					id.id = 'fwp-bsp-activity-id-onwrap';
					id.type = 'hidden';id.name = 'fwp-bsp-scheduled-id';
					document.querySelector( wrap ).appendChild( id );
				}
			} else if( tab ) {
				// if( e.dataset.content && e.dataset.content != '' ) {
				// 	// document.getElementById( 'fwp-bsp-activity-content' ).innerHTML = e.dataset.content;
				// }
				
			} else {}
		}
		closePopup( popup ) {
			const thisClass = this;var elem;
			popup.classList.remove( 'reign-window-popup-open' );
			document.body.classList.remove( 'modal-active' );
			// document.getElementById( 'fwp-bsp-activity-content' ).innerHTML = '';
			elem = document.getElementById( 'fwp-bsp-activity-schedule' );
			if( elem ) {elem.value = '';}
			elem = document.getElementById( 'fwp-bsp-activity-id-onwrap' );
			if( elem ) {elem.remove();}
		}
		fixPopup( popup ) {
			const thisClass = this;var dom;
			if( thisClass.fixedPopup ) {return;}
			thisClass.fixedPopup = true;
			// if( ! popup ) {
				popup = document.getElementById( 'fwp-sheduled-activity-edit-form' );
			// }
			if( popup ) {
				popup.addEventListener( 'click', function( event ) {
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
		fixCustomTabIssue() {
			const thisClass = this;var node = document.querySelector( 'body.fwp-bsp.buddypress .entry-content' );
			if( ! document.querySelector( 'body.fwp-bsp.buddypress #buddypress' ) && node ) {
				node.id = 'buddypress';node.classList.add( 'buddypress-wrap', 'reign-theme', 'bp-single-vert-nav', 'bp-vertical-navs', 'bp-dir-hori-nav' );
			}
		}
		eventDrop( info ) {
			const thisClass = this;var edit;
			// alert( info.event.title + " was dropped on " + info.event.start.toISOString() );
			// alert( info.event.id + " was dropped on " + info.event.start.toISOString() );
			// console.log( info.event.id, info.event.start );
			// console.log( info.event );
			if( ! thisClass.onDragConfirm || confirm( thisClass.confirmSwitch ) ) {
				var d = new Date( info.event.start ),
						date = d.toISOString(); // ("0" + d.getDate()).slice(-2) + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
				date = d.toISOString().slice(0,10) + ' ' + d.toLocaleTimeString();
				$.ajax( {
					url: thisClass.ajaxUrl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'fwp-bsp-activity-switch-schedule',
						nonce: thisClass.ajaxNonce,
						schedule: date, // info.event.start.toDateString(),
						activity: info.event.id,
						timezone: ( Intl ) ? Intl.DateTimeFormat().resolvedOptions().timeZone : ''
					},
					success: function( json ) {
						var events = [];
						if( json.success ) {
							// console.log( json.data );
							edit = document.querySelector( '.fwp-bsp-quickedit[data-activity="' + info.event.id + '"]' );
							if( edit && json.data.date && json.data.date != '' ) {
								edit.dataset.schedule = json.data.date;
							} else {
								console.log( 'Couldn\'t changed edit date' );
							}
						} else {
							info.revert();
						}
					},
					error: function( e ) {
						// e.responseText | e.statusText | e.statusCode | e.status | e.readyState
						console.log( e.responseText );
						info.revert();
					}
				} );
			} else {
				info.revert();
			}
		}

		fetchActivityForm() {
			const thisClass = this;var node = document.querySelector( '.registration-login-form .content' );
			// } else {console.log( node, 'not found' );
			if( node ) {
				node.id = 'bp-nouveau-activity-form';node.classList.add( 'activity-update-form' );
				// if( node.parentNode ) {
				// 	node.parentNode.classList.add( 'activity-update-form' );
				// }
			}
			node = node.querySelector( '#whats-new-form' );
			if( node ) {
				node.classList.add( 'activity-form', 'activity-form-expanded' );
			}
		}

		activityOnly() {
			const thisClass = this;var node, div, elem, span, a, i;
			node = document.querySelector( '.fwp-bsp.fwp-bsp-activity-only' );
			if( thisClass.editor !== false ) {return;}
			if( node ) {
				elem = document.querySelector( '.fwp-bsp.fwp-bsp-activity-only #buddypress #bp-nouveau-activity-form #whats-new' );
				if( elem ) {
					// elem.click();
					ClassicEditor.create( elem ).then( editor => {
						thisClass.editor = editor;
						console.log( editor );
					} ).catch( error => {
						console.error( error );
					} );
				}
			}
		}
		editBtnHighlight() {
			document.querySelectorAll( '.fwp-bsp.fwp-bsp-activity-edit .activity-content .activity-meta .buddyboss_edit_activity:not(.is_highlighted_fwp)' ).forEach( function( e, i ) {
				e.classList.add( 'is_highlighted_fwp' );
				e.addEventListener( 'click', function( event ) {
						this.classList.add( 'is_clicked' );
				} );
			} );
		}
		iframeModel() {
			const thisClass = this;var node, iframe, model, popup, popupTab;
			document.querySelectorAll( '.fwp-bsp-open-popup:not(.is_handled)' ).forEach( function( e, i ) {
				// console.log( e, i );
				e.classList.add( 'is_handled' );
				e.addEventListener( 'click', function( event ) {
					event.preventDefault();
					popup = this.dataset.popup;
					popupTab = this.dataset.popupTab;
					model = document.getElementById( popup );
					if( true ) {
						document.querySelector( '.fwp-bsp .registration-login-form .content.is_active' ).classList.remove( 'is_active' );
						node = document.querySelector( '.fwp-bsp .registration-login-form .content[data-tab="' + popupTab + '"]' );
						if( node ) {
							node.classList.add( 'is_active' );
							if( popupTab == 'edit' ) {iframe = node.querySelector( 'iframe' );iframe.src = ( this.href ? this.href : ( event.href ? event.href : event.getAttribute( 'href' ) ) ) + '&is_iframe=true';}
							if( model ) {thisClass.openPopup( model, event, this, ( popupTab == 'schedule' ) );}
						}
					}
					switch ( popupTab ) {
						case 'create':
						case 'edit':
							thisClass.modelWidely( model, true );
							break;
						case 'schedule':
							if( typeof this.dataset !== undefined && this.dataset.schedule != '' ) {
								document.getElementById( 'fwp-bsp-activity-schedule' ).value = this.dataset.schedule;
							}
							if( typeof this.dataset !== undefined && this.dataset.activity != '' ) {
								document.getElementById( 'fwp-bsp-activity-id' ).value = this.dataset.activity;
							}
							node = document.getElementById( 'fwp-bsp-activity-timezone' );
							if( node && Intl ) {node.value = Intl.DateTimeFormat().resolvedOptions().timeZone;}
							thisClass.modelWidely( model, false );
							break;
						default:
							break;
					}
				} );
			} );
		}
		modelWidely( model, is_wide ) {
			model = document.querySelector( '.fwp-bsp #fwp-sheduled-activity-edit-form .fwp-sheduled-activity-edit-form' );
			// if( ! model ) {return;}
			if( is_wide ) {
				model.classList.add( 'widly' );
			} else {
				model.classList.remove( 'widly' );
			}
		}
	}

	new FUTUREWORDPRESS_PROJECT_FROTEND_MAIN();
} )( jQuery );
