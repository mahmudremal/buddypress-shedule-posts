<?php
/**
 * Register Menus
 *
 * @package FutureWordPress BSP
 */

namespace FUTUREWORDPRESS_PROJECT\Inc;

use FUTUREWORDPRESS_PROJECT\Inc\Traits\Singleton;

class Menus {

	use Singleton;
	private $getIcons = null;

	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		add_filter( 'futurewordpress/project/settings/fields', [ $this, 'menus' ], 10, 1 );
	}
	public function menus( $args ) {
    // get_fwp_option( 'key', 'default' )
		// is_FwpActive( 'key' )
		$args = [];
		$args['standard'] = [
			'title'					=> __( 'General', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
			'description'			=> __( 'Generel fields comst commonly used to changed.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
			'fields'				=> [
				[
					'id' 			=> 'fwp_bsp_enabled',
					'label'					=> __( 'Enable Schedule posts', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'			=> __( 'Mark to enable schedule posts features on Buddypress activity post.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
				[
					'id' 			=> 'fwp_bsp_dashboardwidget',
					'label'					=> __( 'Dashboard Widget', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'			=> __( 'Show Scheduled Posts in Dashboard Widget.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
				[
					'id' 			=> 'fwp_bsp_posttimer',
					'label'			=> __( 'CountDown timer', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'	=> __( 'Enable timer on scheduled posts. This will show a countdown timer after post content with Days, Hour, Minutes and Seconds parameter.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
				// [
				// 	'id' 			=> 'fwp_bsp_defaultime',
				// 	'label'			=> __( 'Default Time', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
				// 	'description'	=> __( 'Set Default Schedule Time fro activity posts.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
				// 	'type'			=> 'time',
				// 	'default'		=> true
				// ],
				// [
				// 	'id' 			=> 'fwp_bsp_hidepostnow',
				// 	'label'			=> __( 'Post Immediately', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
				// 	'description'	=> __( 'Hide Post Immediately or "post Update" button. If you check it, then buddypress default "post Update" button will be hidden.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
				// 	'type'			=> 'checkbox',
				// 	'default'		=> false
				// ],
			]
		];
		$args['notify'] = [
			'title'					=> __( 'Notification', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
			'description'			=> __( 'Setup notification information and function which should be enabled on reacting on Schedule posts. Also customize your notification text and additional informations.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
			'fields'				=> [
				[
					'id' 			=> 'fwp_bsp_notifybuddypress',
					'label'					=> __( 'Enable Schedule posts', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'			=> __( 'Mark to enable schedule posts features on Buddypress activity post.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
				[
					'id' 			=> 'fwp_bsp_notifydate-formate',
					'label'					=> __( 'Date formate', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'			=> __( 'Notification date formate which willl be replace withh notification default date formate. Don\'t forget to keep it in PHP date formate.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'text',
					'default'		=> 'M d, Y H:i A'
				],
				[
					'id' 			=> 'fwp_bsp_notifypublish-text',
					'label'					=> __( 'On Pulished Text', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'			=> __( 'Notification title on activity post publish time. This is under translation. If you change it, you should review your translation to update it. Use {id} for Activity ID and {datetime} for activity scheduled published date.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'text',
					'default'		=> 'Activity id {id} has been published on {datetime}.'
				],
				[
					'id' 			=> 'fwp_bsp_notifypaused-text',
					'label'					=> __( 'On Pulished Text', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'			=> __( 'Notification title on activity post publish time. This is under translation. If you change it, you should review your translation to update it. Use {id} for Activity ID and {datetime} for activity scheduled published date.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'text',
					'default'		=> 'Scheduled activity ({id}) saved successfully. Will publish on {datetime}.'
				],
				[
					'id' 			=> 'fwp_bsp_notify-link',
					'label'					=> __( 'Notification link', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'description'			=> __( 'Kepp it enabled to have notification item link. Otherwise notification will be just a information notification without any link.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
			]
		];
		return $args;
	}

}
