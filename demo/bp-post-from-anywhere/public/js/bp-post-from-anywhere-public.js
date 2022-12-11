(function (jq) {
	'use strict';
	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	jq(
		function () {
			/* Hide Forums Post Form */
			if ('-1' === window.location.search.indexOf( 'new' ) && jq( 'div.forums' ).length) {
				jq( '#new-topic-post' ).hide();
			} else {
				jq( '#new-topic-post' ).show();
			}

			/* Activity filter and scope set */
			if ( typeof bp_init_activity == 'function' ) {
				bp_init_activity();
			}

			var objects    = ['members', 'groups', 'blogs', 'forums', 'group_members'],
				$whats_new = jq( '#bppfa-whats-new' );
			/* Object filter and scope set. */
			if ( typeof bp_init_objects == 'function' ) {
				bp_init_objects( objects );
			}
			/* @mention Compose Scrolling */
			if ($whats_new.length && bppfa_bp_get_querystring( 'r' )) {
				var $member_nicename = $whats_new.val();
				jq( '#bppfa-whats-new-options' ).slideDown();
				$whats_new.animate(
					{
						height: '60px'
					}
				);
				jq.scrollTo(
					$whats_new, 500, {
						offset: -125,
						easing: 'swing'
					}
				);
				$whats_new.val( '' ).focus().val( $member_nicename );
			} else {
				jq( '#bppfa-whats-new-options' ).hide();
			}
			/**** Activity Posting ********************************************************/

			/* Textarea focus */
			$whats_new.focus(
				function () {
					jq( '#bppfa-whats-new-options' ).slideDown();
					jq( this ).animate(
						{
							height: '60px'
						}
					);
					jq( '#bppfa-aw-whats-new-submit' ).prop( 'disabled', false );
					jq( this ).parent().addClass( 'active' );
					jq( '#bppfa-whats-new-content' ).addClass( 'active' );
					var whats_new_form = jq( 'form#bppfa-whats-new-form' ),
					$activity_all      = jq( '#activity-all' );
					if (whats_new_form.hasClass( 'submitted' )) {
						whats_new_form.removeClass( 'submitted' );
					}

					// Return to the 'All Members' tab and 'Everything' filter,
					// to avoid inconsistencies with the heartbeat integration
					if ($activity_all.length) {
						if ( ! $activity_all.hasClass( 'selected' )) {
							// reset to everything
							jq( '#activity-filter-select select' ).val( '-1' );
							$activity_all.children( 'a' ).trigger( 'click' );
						} else if ('-1' !== jq( '#activity-filter-select select' ).val()) {
							jq( '#activity-filter-select select' ).val( '-1' );
							jq( '#activity-filter-select select' ).trigger( 'change' );
						}
					}
				}
			);
			/* For the "What's New" form, do the following on focusout. */
			jq( '#bppfa-whats-new-form' ).on(
				'focusout', function (e) {
					var elem = jq( this );
					// Let child hover actions passthrough.
					// This allows click events to go through without focusout.
					setTimeout(
						function () {
							if ( ! elem.find( ':hover' ).length) {
								// Do not slide up if textarea has content.
								if ('' !== $whats_new.val()) {
									return;
								}
								jq( '#bppfa-whats-new-options' ).slideUp();
								$whats_new.animate(
									{
										height: '43px'
									}
								);
								jq( '#bppfa-aw-whats-new-submit' ).prop( 'disabled', true );
								jq( '#bppfa-whats-new-content' ).removeClass( 'active' );
								$whats_new.parent().removeClass( 'active' );
							}
						}, 0
					);
				}
			);
			/* New posts */
			jq( '#bppfa-aw-whats-new-submit' ).on(
				'click', function () {
					jq( ".bppfa-spinner" ).show();
					var last_date_recorded = 0,
					button                 = jq( this ),
					form                   = button.closest( 'form#bppfa-whats-new-form' ),
					inputs                 = {}, post_data;
					// Get all inputs and organize them into an object {name: value}
					jq.each(
						form.serializeArray(), function (key, input) {
							// Only include public extra data
							if ('_' !== input.name.substr( 0, 1 ) && 'whats-new' !== input.name.substr( 0, 9 )) {
								if ( ! inputs[ input.name ]) {
									inputs[ input.name ] = input.value;
								} else {
									// Checkboxes/dropdown list can have multiple selected value
									if ( ! jq.isArray( inputs[ input.name ] )) {
										inputs[ input.name ] = new Array( inputs[ input.name ], input.value );
									} else {
										inputs[ input.name ].push( input.value );
									}
								}
							}
						}
					);
					form.find( '*' ).each(
						function () {
							if (jq.nodeName( this, 'textarea' ) || jq.nodeName( this, 'input' )) {
								jq( this ).prop( 'disabled', true );
							}
						}
					);
					/* Remove any errors */
					jq( 'div.error' ).remove();
					button.addClass( 'loading' );
					button.prop( 'disabled', true );
					form.addClass( 'submitted' );
					/* Default POST values */
					var object       = '';
					var item_id      = jq( '#bppfa-whats-new-post-in' ).val();
					var content      = jq( '#bppfa-whats-new' ).val();
					var firstrow     = jq( '#buddypress ul.activity-list li' ).first();
					var activity_row = firstrow;
					var timestamp    = null;
					// Checks if at least one activity exists
					if (firstrow.length) {

						if (activity_row.hasClass( 'load-newest' )) {
							activity_row = firstrow.next();
						}

						timestamp = activity_row.prop( 'class' ).match( /date-recorded-([0-9]+)/ );
					}

					if (timestamp) {
						last_date_recorded = timestamp[1];
					}

					/* Set object for non-profile posts */
					object = jq( '#bppfa-whats-new-post-object' ).val();


					post_data = jq.extend(
						{
							action: 'post_update',
							'cookie':  ( typeof bp_get_cookies == 'function' ) ? bp_get_cookies() : '',
							'_wpnonce_post_update': jq( '#_wpnonce_post_update' ).val(),
							'content': content,
							'object': object,
							'item_id': item_id,
							'since': last_date_recorded,
							'_bp_as_nonce': jq( '#_bp_as_nonce' ).val() || ''
						}, inputs
					);
					jq.post(
						bppfa_ajax_obj.ajaxurl, post_data, function (response) {
							jq( ".bppfa-spinner" ).hide();
							form.find( '*' ).each(
								function () {
									if (jq.nodeName( this, 'textarea' ) || jq.nodeName( this, 'input' )) {
										jq( this ).prop( 'disabled', false );
									}
								}
							);
							/* Check for errors and append if found. */
							if (response[0] + response[1] === '-1') {
								notify(
									{
										type: "error", // alert | success | error | warning | info
										title: "Error",
										position: {
											x: "center", // right | left | center
											y: "center" // top | bottom | center
										},
										icon: '<img src="' + bppfa_ajax_obj.bppfa_url + 'public/icons/paper_plane.png" />',
										message: "Please enter some content to post.",
										size: "normal", // normal | full | small
										overlay: false, // true | false
										closeBtn: true, // true | false
										overflowHide: false, // true | false
										spacing: 20, // number px
										theme: "dark-theme", // default | dark-theme
										autoHide: true, // true | false
										delay: 5000, // number ms
										onShow: null, //function
										onClick: null, //function
										onHide: null, //function
										template: '<div class="notify"><div class="notify-text"></div></div>'
									}
								);
							} else {
								if (0 === jq( 'ul.activity-list' ).length) {
									jq( 'div.error' ).slideUp( 100 ).remove();
									jq( '#message' ).slideUp( 100 ).remove();
									jq( 'div.activity' ).append( '<ul id="activity-stream" class="activity-list item-list">' );
								}

								if (firstrow.hasClass( 'load-newest' )) {
									firstrow.remove();
								}

								jq( '#activity-stream' ).prepend( response );
								if ( ! last_date_recorded) {
									jq( '#activity-stream li:first' ).addClass( 'new-update just-posted' );
								}

								if (0 !== jq( '#latest-update' ).length) {
									var l = jq( '#activity-stream li.new-update .activity-content .activity-inner p' ).html(),
									v     = jq( '#activity-stream li.new-update .activity-content .activity-header p a.view' ).attr( 'href' ),
									ltext = jq( '#activity-stream li.new-update .activity-content .activity-inner p' ).text(),
									u     = '';
									if (ltext !== '') {
										u = l + ' ';
									}

									u += '<a href="' + v + '" rel="nofollow">' + BP_DTheme.view + '</a>';
									jq( '#latest-update' ).slideUp(
										300, function () {
											jq( '#latest-update' ).html( u );
											jq( '#latest-update' ).slideDown( 300 );
										}
									);
								}

								jq( 'li.new-update' ).hide().slideDown( 300 );
								jq( 'li.new-update' ).removeClass( 'new-update' );
								jq( '#bppfa-whats-new' ).val( '' );
								form.get( 0 ).reset();
								// reset vars to get newest activities
								var newest_activities      = '';
								var activity_last_recorded = 0;								
								notify(
									{
										type: "success", // alert | success | error | warning | info
										title: "Success",
										position: {
											x: "center", // right | left | center
											y: "center" // top | bottom | center
										},
										icon: '<img src="' + bppfa_ajax_obj.bppfa_url + 'public/icons/paper_plane.png" />',
										message: "Activity posted successfully.",
										size: "normal", // normal | full | small
										overlay: false, // true | false
										closeBtn: true, // true | false
										overflowHide: false, // true | false
										spacing: 20, // number px
										theme: "dark-theme", // default | dark-theme
										autoHide: true, // true | false
										delay: 5000, // number ms
										onShow: null, //function
										onClick: null, //function
										onHide: null, //function
										template: '<div class="notify"><div class="notify-text"></div></div>'
									}
								);
							}

							jq( '#bppfa-whats-new-options' ).slideUp();
							jq( '#bppfa-whats-new-form textarea' ).animate(
								{
									height: '43px'
								}
							);
							jq( '#bppfa-aw-whats-new-submit' ).prop( 'disabled', true ).removeClass( 'loading' );
							jq( '#bppfa-whats-new-content' ).removeClass( 'active' );

						}
					);
					return false;
				}
			);
			
			jq( '#bppfa-whats-new-post-in' ).on('change', function () {
				
				jq('#bppfa-whats-new-post-object').val(jq(this).find(':selected').data( 'value'));
			});
			// For Widgets
			var whats_new_w = jq( '#wbppfa-whats-new' );
			/* @mention Compose Scrolling */
			if (whats_new_w.length && bppfa_bp_get_querystring( 'r' )) {
				$wmember_nicename = whats_new_w.val();
				jq( '#wbppfa-whats-new-options' ).slideDown();
				whats_new_w.animate(
					{
						height: '60px'
					}
				);
				jq.scrollTo(
					whats_new_w, 500, {
						offset: -125,
						easing: 'swing'
					}
				);
				whats_new_w.val( '' ).focus().val( $wmember_nicename );
			} else {
				jq( '#wbppfa-whats-new-options' ).hide();
			}
			/**** Activity Posting ********************************************************/

			/* Textarea focus */
			whats_new_w.focus(
				function () {
					jq( '#wbppfa-whats-new-options' ).slideDown();
					jq( this ).animate(
						{
							height: '60px'
						}
					);
					jq( '#wbppfa-aw-whats-new-submit' ).prop( 'disabled', false );
					jq( this ).parent().addClass( 'active' );
					jq( '#wbppfa-whats-new-content' ).addClass( 'active' );
					var whats_new_form = jq( 'form#wbppfa-whats-new-form' ),
					$activity_all      = jq( '#activity-all' );
					if (whats_new_form.hasClass( 'submitted' )) {
						whats_new_form.removeClass( 'submitted' );
					}

					// Return to the 'All Members' tab and 'Everything' filter,
					// to avoid inconsistencies with the heartbeat integration
					if ($activity_all.length) {
						if ( ! $activity_all.hasClass( 'selected' )) {
							// reset to everything
							jq( '#activity-filter-select select' ).val( '-1' );
							$activity_all.children( 'a' ).trigger( 'click' );
						} else if ('-1' !== jq( '#activity-filter-select select' ).val()) {
							jq( '#activity-filter-select select' ).val( '-1' );
							jq( '#activity-filter-select select' ).trigger( 'change' );
						}
					}
				}
			);
			/* For the "What's New" form, do the following on focusout. */
			jq( '#wbppfa-whats-new-form' ).on(
				'focusout', function (e) {
					var elem = jq( this );
					// Let child hover actions passthrough.
					// This allows click events to go through without focusout.
					setTimeout(
						function () {
							if ( ! elem.find( ':hover' ).length) {
								// Do not slide up if textarea has content.
								if ('' !== whats_new_w.val()) {
									return;
								}

								whats_new_w.animate(
									{
										height: '43px'
									}
								);
								jq( '#wbppfa-whats-new-options' ).slideUp();
								jq( '#wbppfa-aw-whats-new-submit' ).prop( 'disabled', true );
								jq( '#wbppfa-whats-new-content' ).removeClass( 'active' );
								whats_new_w.parent().removeClass( 'active' );
							}
						}, 0
					);
				}
			);
			/* New posts */
			jq( '#wbppfa-aw-whats-new-submit' ).on(
				'click', function () {
					jq( ".wbppfa-spinner" ).show();
					var last_date_recorded = 0,
					button                 = jq( this ),
					form                   = button.closest( 'form#wbppfa-whats-new-form' ),
					inputs                 = {}, post_data;
					// Get all inputs and organize them into an object {name: value}
					jq.each(
						form.serializeArray(), function (key, input) {
							// Only include public extra data
							if ('_' !== input.name.substr( 0, 1 ) && 'whats-new' !== input.name.substr( 0, 9 )) {
								if ( ! inputs[ input.name ]) {
									inputs[ input.name ] = input.value;
								} else {
									// Checkboxes/dropdown list can have multiple selected value
									if ( ! jq.isArray( inputs[ input.name ] )) {
										inputs[ input.name ] = new Array( inputs[ input.name ], input.value );
									} else {
										inputs[ input.name ].push( input.value );
									}
								}
							}
						}
					);
					form.find( '*' ).each(
						function () {
							if (jq.nodeName( this, 'textarea' ) || jq.nodeName( this, 'input' )) {
								jq( this ).prop( 'disabled', true );
							}
						}
					);
					/* Remove any errors */
					jq( 'div.error' ).remove();
					button.addClass( 'loading' );
					button.prop( 'disabled', true );
					form.addClass( 'submitted' );
					/* Default POST values */
					var object       = '';
					var item_id      = jq( '#bppfa-whats-new-post-in' ).val();
					var content      = jq( '#wbppfa-whats-new' ).val();
					var firstrow     = jq( '#buddypress ul.activity-list li' ).first();
					var activity_row = firstrow;
					var timestamp    = null;
					// if(content != ''){
					// Checks if at least one activity exists
					if (firstrow.length) {

						if (activity_row.hasClass( 'load-newest' )) {
							activity_row = firstrow.next();
						}

						timestamp = activity_row.prop( 'class' ).match( /date-recorded-([0-9]+)/ );
					}

					if (timestamp) {
						last_date_recorded = timestamp[1];
					}

					/* Set object for non-profile posts */
					if (item_id > 0) {
						object = jq( '#wbppfa-whats-new-post-object' ).val();
					}

					post_data = jq.extend(
						{
							action: 'post_update',
							'cookie': ( typeof bp_get_cookies == 'function' ) ? bp_get_cookies() : '',
							'_wpnonce_post_update': jq( '#_wpnonce_post_update' ).val(),
							'content': content,
							'object': object,
							'item_id': item_id,
							'since': last_date_recorded,
							'_bp_as_nonce': jq( '#_bp_as_nonce' ).val() || ''
						}, inputs
					);
					jq.post(
						bppfa_ajax_obj.ajaxurl, post_data, function (response) {
							jq( ".wbppfa-spinner" ).hide();
							form.find( '*' ).each(
								function () {
									if (jq.nodeName( this, 'textarea' ) || jq.nodeName( this, 'input' )) {
										jq( this ).prop( 'disabled', false );
									}
								}
							);
							/* Check for errors and append if found. */
							if (response[0] + response[1] === '-1') {
								notify(
									{
										type: "error", // alert | success | error | warning | info
										title: "Error",
										position: {
											x: "center", // right | left | center
											y: "center" // top | bottom | center
										},
										icon: '<img src="' + bppfa_ajax_obj.bppfa_url + 'public/icons/paper_plane.png" />',
										message: "Please enter some content to post.",
										size: "normal", // normal | full | small
										overlay: false, // true | false
										closeBtn: true, // true | false
										overflowHide: false, // true | false
										spacing: 20, // number px
										theme: "dark-theme", // default | dark-theme
										autoHide: true, // true | false
										delay: 5000, // number ms
										onShow: null, //function
										onClick: null, //function
										onHide: null, //function
										template: '<div class="notify"><div class="notify-text"></div></div>'
									}
								);
							} else {
								if (0 === jq( 'ul.activity-list' ).length) {
									jq( 'div.error' ).slideUp( 100 ).remove();
									jq( '#message' ).slideUp( 100 ).remove();
									jq( 'div.activity' ).append( '<ul id="activity-stream" class="activity-list item-list">' );
								}

								if (firstrow.hasClass( 'load-newest' )) {
									firstrow.remove();
								}

								jq( '#activity-stream' ).prepend( response );
								if ( ! last_date_recorded) {
									jq( '#activity-stream li:first' ).addClass( 'new-update just-posted' );
								}

								if (0 !== jq( '#latest-update' ).length) {
									var l = jq( '#activity-stream li.new-update .activity-content .activity-inner p' ).html(),
									v     = jq( '#activity-stream li.new-update .activity-content .activity-header p a.view' ).attr( 'href' ),
									ltext = jq( '#activity-stream li.new-update .activity-content .activity-inner p' ).text(),
									u     = '';
									if (ltext !== '') {
										u = l + ' ';
									}

									u += '<a href="' + v + '" rel="nofollow">' + BP_DTheme.view + '</a>';
									jq( '#latest-update' ).slideUp(
										300, function () {
											jq( '#latest-update' ).html( u );
											jq( '#latest-update' ).slideDown( 300 );
										}
									);
								}

								jq( 'li.new-update' ).hide().slideDown( 300 );
								jq( 'li.new-update' ).removeClass( 'new-update' );
								jq( '#wbppfa-whats-new' ).val( '' );
								form.get( 0 ).reset();
								// reset vars to get newest activities
								var newest_activities      = '';
								var activity_last_recorded = 0;
								notify(
									{
										type: "success", // alert | success | error | warning | info
										title: "Success",
										position: {
											x: "center", // right | left | center
											y: "center" // top | bottom | center
										},
										icon: '<img src="' + bppfa_ajax_obj.bppfa_url + 'public/icons/paper_plane.png" />',
										message: "Activity posted successfully.",
										size: "normal", // normal | full | small
										overlay: false, // true | false
										closeBtn: true, // true | false
										overflowHide: false, // true | false
										spacing: 20, // number px
										theme: "dark-theme", // default | dark-theme
										autoHide: true, // true | false
										delay: 5000, // number ms
										onShow: null, //function
										onClick: null, //function
										onHide: null, //function
										template: '<div class="notify"><div class="notify-text"></div></div>'
									}
								);
							}

							jq( '#wbppfa-whats-new-options' ).slideUp();
							jq( '#wbppfa-whats-new-form textarea' ).animate(
								{
									height: '43px'
								}
							);
							jq( '#wbppfa-aw-whats-new-submit' ).prop( 'disabled', true ).removeClass( 'loading' );
							jq( '#wbppfa-whats-new-content' ).removeClass( 'active' );
						}
					);
					return false;
				}
			);
		}
	);
	/* jshint unused: false */

	function bppfa_bp_get_querystring(n) {
		var half = location.search.split( n + '=' )[1];
		return half ? decodeURIComponent( half.split( '&' )[0] ) : null;
	}
})( jQuery );
