<?php
/**
 * Block Patterns
 *
 * @package FutureWordPress BSP
 */

namespace FUTUREWORDPRESS_PROJECT\Inc;

use FUTUREWORDPRESS_PROJECT\Inc\Traits\Singleton;

class Hooks {
	use Singleton;

	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
    add_action( 'bp_ready', [ $this, 'hookStatus' ], 1, 0 ); // bp_init bp_ready wp_loaded
    
    add_action( 'bp_activity_post_form_options', [ $this, 'activityFormOptions' ], 30, 0 );
    // add_action( 'bp_after_activity_post_form', [ $this, 'activityFormAfter' ], 10, 0 );
    // add_filter( 'bp_activity_custom_update', [ $this, 'buddyPressACU' ], 10, 4 );

    // add_action( 'bp_register_activity_actions', [ $this, 'cpraAction' ] );
    add_action( 'bp_activity_posted_update', [ $this, 'bp_activity_posted_update' ], 10, 4 );
    add_action( 'bp_groups_posted_update', [ $this, 'bp_activity_posted_update' ], 10, 4 );
    add_action( 'yz_activity_posted_update', [ $this, 'bp_activity_posted_update' ], 10, 4 );
    add_action( 'yz_groups_posted_update', [ $this, 'bp_activity_posted_update' ], 10, 4 );

    add_filter( 'bp_get_activity_content_body', [ $this, 'bp_get_activity_content_body' ], 10, 2 );
    // add_filter( 'bp_bsp_activity_entry_content', [ $this, 'bp_bsp_activity_entry_content' ], 10, 2 );

    add_filter( 'bp_notifications_get_registered_components', [ $this, 'notiRegComponent' ], 10, 1 );
    add_filter( 'bp_notifications_get_notifications_for_user', [ $this, 'notiGetForUser' ], 10, 5 );

    add_filter( 'fwp_bsp_notification_html', [ $this, 'fwp_bsp_notification_html' ], 10, 5 );
    add_filter( 'fwp_bsp_notification_object', [ $this, 'fwp_bsp_notification_object' ], 10, 5 );

	}
  public function activityFormOptions() {
    ?>
    <div class="fwp-bsp-schedule-field">
      <div class="fwp-bsp-schedule-wrap">
        <div class="fwp-bsp-schedule">
          <span class="fwp-bsp-input-prepend"><i class="fa fa-clock"></i></span>
          <input type="hidden" name="timezone" class="ac-input" id="fwp-bsp-scheduled-timezone">
          <input type="datetime-local" name="fwp-bsp-scheduled[]" class="ac-input">
          <input type="hidden" id="fwp-bsp-scheduled-action" name="fwp-bsp-scheduled-action" value="">
        </div>
      </div>
    </div> 
    <?php
  }
  public function activityFormAfter() {
    ?>
    Lorem 
    <?php
  }
  public function buddyPressACU( $default, $object, $item_id = false, $content = '' ) {
    // bp_actions bp_activity_custom_update
    // if ( ! is_user_logged_in() || ! bp_is_activity_component() || ! bp_is_current_action( 'post' ) ) {return false;}
    // check_admin_referer( 'post_update', '_wpnonce_post_update' );

    wp_send_json_error( [ $default, $object, $item_id, $content ] );
  }
  public function cpraAction() {
    // https://codex.buddypress.org/developer/function-examples/bp_activity_set_action/
    // Your plugin is creating a custom BuddyPress component
    $component_id = 'activity';
    // You can also use one of the BuddyPress component
    $component_id = buddypress()->activity->id;
    bp_activity_set_action(
        $component_id,
        'activity_update',
        __( 'Posted a status update', 'buddypress' ),
        [ $this, 'bp_activity_format_activity_action_activity_update' ],
        __( 'Updates', 'buddypress' ),
        [ 'activity' ]
    );
  }
  public function bp_activity_format_activity_action_activity_update( $action, $activity ) {
		$action = sprintf( __( '%s created a Scheduled post', 'fwp-bsp' ), bp_core_get_userlink( $activity->user_id ) );
		return apply_filters( 'bp_activity_new_poll_action', $action, $activity );
  }

  public function bp_activity_posted_update( $content, $user_id, $activity_id, $g_activity_id = null ) {
    global $wpdb;
    if ( isset( $g_activity_id ) ) {
			$activity_id = $g_activity_id;
		}
    $activity_tbl = $wpdb->base_prefix . 'bp_activity';
    if( true ) {
      $fwpbsp_option = [];
			foreach( (array) ( isset( $_POST['fwp-bsp-scheduled'] ) ? $_POST['fwp-bsp-scheduled'] : [] ) as $key => $value ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				if( ! empty( $value ) ) {
					$fwpbsp_option[] = $value; // date( 'Y-m-d H:i:s', strtotime( $value ) );
				}
			}
			// $fwpbsp_meta = [
			// 	'schedule_option'              => $fwpbsp_option,
			// 	'multiselect'              => ( count( isset( $_POST['fwp-bsp-scheduled'] ) ? $_POST['fwp-bsp-scheduled'] : [] ) >= 2 ),
			// 	'additionals'              => [],
      //   'updatedon'                => date( 'Y-m-d H:i:s' ),
      //   'expiry'                   => ( isset( $_POST['fwp-bsp-scheduled-expiry'] ) && ! empty( $_POST['fwp-bsp-scheduled-expiry'] ) ) ? $_POST['fwp-bsp-scheduled-expiry'] : false
      // ];
      // bp_activity_update_meta( $activity_id, 'fwpbsp_meta', $fwpbsp_meta );
      // bp_activity_update_meta( $activity_id, 'fwpbsp_meta_schedule', ( isset( $fwpbsp_option[0] ) ? date( 'Y-m-d H:i:s', strtotime( $fwpbsp_option[0] ) ) : '' ) );
      
      $schedule = isset( $fwpbsp_option[0] ) ? date( 'Y-m-d H:i:s', strtotime( $fwpbsp_option[0] ) ) : '';
      if( ! empty( $schedule ) ) {
        bp_activity_update_meta( $activity_id, 'fwpbsp_meta', [
          'scheduled_on'              => $schedule,
          'timezone'                  => isset( $_POST[ 'timezone' ] ) ? $_POST[ 'timezone' ] : '',
          'updatedon'                 => date( 'Y-m-d H:i:s' ),
          'expiry'                    => false
        ] );
        bp_activity_update_meta( $activity_id, 'fwpbsp_meta_schedule', apply_filters( 'futurewordpress/project/filter/server/time', $schedule, [
          'timezone'                  => isset( $_POST[ 'timezone' ] ) ? $_POST[ 'timezone' ] : ''
        ] ) );
      }

      if( isset( $fwpbsp_option[0] ) && ! empty( $fwpbsp_option[0] ) ) {
        if( ! $this->isAvailble( $activity_id ) && $this->setStatus( $activity_id, 1 ) ) {
          $this->notify( $row->activity_id, 0 ); // Successed.
        }
      }
    }
  }

  public function bp_get_activity_content_body( $activity_content, $activity_obj ) {
    $activity_id = $activity_obj->id;

    // return $activity_content;
    // return $activity_content . ( $this->isAvailble( $activity_id ) ? 'Yes' : 'No' );
    return $activity_content . $this->bp_bsp_activity_entry_content( $activity_id, $activity_obj );
  }
  public function bp_bsp_activity_entry_content( $act = null, $activity_obj = array() ) {
		global $current_user;		
		$user_id      = get_current_user_id();
		$author_id = isset( $activity_obj->user_id ) ? $activity_obj->user_id : '';
		$activity_id  = isset( $activity_obj->id ) ? $activity_obj->id : '';
    if( $user_id != $author_id ) {return '';}

    $activity_id = ( isset( $act ) && null !== $act ) ? $act : $activity_id;
		$activity_type = '';

		if ( ! empty( $activity_obj ) && '' !== $activity_obj->type ) {
			$activity_type = $activity_obj->type;
		}

		// $activity_meta = (array) bp_activity_get_meta( $activity_id, 'fwpbsp_meta' );
    
		$fwpbsp_closing = false;
		// if ( isset( $activity_meta['expiry'] ) && $activity_meta['expiry'] != 0 ) {
		// 	$current_time    = new DateTime( date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ) );
		// 	$close_date      = $activity_meta['expiry'];
		// 	$close_date_time = new DateTime( $close_date );
		// 	if ( $close_date_time > $current_time ) {
		// 		$fwpbsp_closing = true;
		// 	}
		// }
		$fwpbsp_meta_schedule = bp_activity_get_meta( $activity_id, 'fwpbsp_meta_schedule' );
    if( ! $fwpbsp_meta_schedule || empty( $fwpbsp_meta_schedule ) ) {return '';}
    // if( $fwpbsp_meta_schedule && ! empty( $fwpbsp_meta_schedule ) ) {
    //   $to_post_time = strtotime( $fwpbsp_meta_schedule );$current_time = time();
    //   if( $to_post_time > $current_time ) {
    //     $fwpbsp_closing = true;
    //   }
    // }
    // if( $fwpbsp_closing ) {
    //   return sprintf( __( 'This post will be removed on %s.', 'fwp-bsp' ), date( 'Y-m-d h:i', strtotime( $fwpbsp_meta_schedule ) ) );
    // } else {
    //   return json_encode( [
    //     $fwpbsp_meta_schedule, $activity_id, // $this->isAvailble( $activity_id )
    //   ] );
    // }
    return ( ! $this->isAvailble( $activity_id ) ) ? '
      <div class="fwp-bsf-post-schedule-timer" data-timing="' . esc_attr( date( 'M d, Y h:i:s', strtotime( $fwpbsp_meta_schedule ) ) ) . '" data-nothing="' . esc_attr( __( '00', 'fwp-bsp' ) ) . '">
        <div class="fwp-bsf-schedule-wrap">
          <div class="fwp-bsf-single fwp-bsf-day" data-title="' . esc_attr__( 'Days', 'fwp-bsp' ) . '"></div>
          <div class="fwp-bsf-single fwp-bsf-hour" data-title="' . esc_attr__( 'Hours', 'fwp-bsp' ) . '"></div>
          <div class="fwp-bsf-single fwp-bsf-minute" data-title="' . esc_attr__( 'Minutes', 'fwp-bsp' ) . '"></div>
          <div class="fwp-bsf-single fwp-bsf-second" data-title="' . esc_attr__( 'Seconds', 'fwp-bsp' ) . '"></div>
        </div>
      </div>' : ''; // esc_attr( date( 'M d, Y h:i:s', strtotime( $fwpbsp_meta_schedule ) ) );
  }

  private function setStatus( $id = false, $status = 0 ) {
    if( ! $id ) {return;}
    global $wpdb;
    $wpdb->update(
      $wpdb->base_prefix . 'bp_activity',
      [
        'hide_sitewide' => $status
      ],
      [
        'id'        => $id,
        'user_id'   => get_current_user_id()
      ],
      [ '%d' ],
      [ '%d' ]
    );
    return true;
  }
  private function isAvailble( $id = false ) {
    if( ! $id ) {return false;}
    $fwpbsp_meta_schedule = bp_activity_get_meta( $id, 'fwpbsp_meta_schedule' );
    if( empty( $fwpbsp_meta_schedule ) ) {
      return true;
    } else {
      $status = true;
      // $current_time    = new DateTime( date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ) );
      // $close_date      = $fwpbsp_meta_schedule;
      // $to_post_time = new DateTime( $close_date );
      
      $to_post_time = strtotime( $fwpbsp_meta_schedule );$current_time = time();
      if( $to_post_time > $current_time ) {
        $status = false;
      }
      return $status;
    }
  }
  public function hookStatus() {
    global $wpdb; // NOW()
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT act_m.activity_id FROM {$wpdb->prefix}bp_activity act LEFT JOIN {$wpdb->prefix}bp_activity_meta act_m ON act.id = act_m.activity_id WHERE act_m.meta_key = 'fwpbsp_meta_schedule' AND act_m.meta_value != '' AND act.hide_sitewide = 1 AND %s >= act_m.meta_value;", date( 'Y-m-d H:i:s' ) ) );
    if( $results && count( $results ) >= 1 ) {
      foreach( $results as $i => $row ) {
        if( $this->isAvailble( $row->activity_id ) && $this->setStatus( $row->activity_id, 0 ) ) {
          // Successed.
          $this->notify( $row->activity_id, 1 );
        }
      }
    }
  }
  private function notify( $id, $status = 0 ) {
		if( ! is_FwpActive( 'fwp_bsp_notifybuddypress' ) ) {return;}
    if( ! bp_is_active( 'notifications' ) ) {return;}
    if( ! is_user_logged_in() ) {return;}
    switch( $status ) {
      case 1 :
        bp_notifications_add_notification( [
          'user_id'           => get_current_user_id(),
          'item_id'           => $id,
          'component_name'    => 'scheduled_activity',
          'component_action'  => 'scheduled_activity-published',
          'date_notified'     => bp_core_current_time(),
          'is_new'            => 1,
        ] );
        break;
      default :
        bp_notifications_add_notification( [
          'user_id'           => get_current_user_id(),
          'item_id'           => $id,
          'component_name'    => 'scheduled_activity',
          'component_action'  => 'scheduled_activity-paused',
          'date_notified'     => bp_core_current_time(),
          'is_new'            => 1,
        ] );
        break;
    }
  }

  public function notiRegComponent( $component = [] ) {
    $component = is_array( $component ) ? $component : (array) $component;
    $component[] = 'scheduled_activity';
    return $component;
  }
  public function notiGetForUser( $action, $activity_id, $secondary_item_id, $total_items, $format = 'string' ) {
    if( 'scheduled_activity-published' === $action ) {
      // print_r( [$action, $activity_id, $secondary_item_id, $total_items, $format] );wp_die( 'Got ti' );
      $fwpbsp_meta_schedule = bp_activity_get_meta( $activity_id, 'fwpbsp_meta_schedule' );

      $title = str_replace( [
        '{id}', '{datetime}'
      ], [
        $activity_id, wp_date( get_fwp_option( 'fwp_bsp_notifydate-formate', 'M d, Y H:i A' ), strtotime( $fwpbsp_meta_schedule ) )
      ], __( get_fwp_option( 'fwp_bsp_notifypublish-text', 'Activity id {id} has been published on {datetime}.' ), 'fwp-bsp' ) );
      $text = $title;
      $link  = site_url( '/activity/p/' . $activity_id . '/' );
      // $custom_text = bp_core_get_user_displayname( $secondary_item_id ) . ' liked your activity';

      // WordPress Toolbar
      if ( 'string' === $format ) {
        $return = '<a href="' . ( is_FwpActive( 'fwp_bsp_notify-link' ) ? esc_url( $link ) : 'javascript:void(0);' ) . '" title="' . esc_attr( $title ) . '">' . esc_html( $text ) . '</a>';
        // $return = apply_filters( 'fwp_bsp_notification_html', $return, $link, (int) $total_items, $text, $title );
        // Deprecated BuddyBar
        // wp_die( $return );
        echo wp_kses( $return, [
          'a' => [
            'href' => [],
            'title' => []
          ]
        ] );
        return $return;
      } else {
        $return = [
          'text' => $text,
          'link' => $link
        ];
        // $return = apply_filters( 'fwp_bsp_notification_object', $return, $link, (int) $total_items, $text, $title );
        return $return;
      }
      return $return;
    } else if( 'scheduled_activity-paused' === $action ) {
      $fwpbsp_meta_schedule = bp_activity_get_meta( $activity_id, 'fwpbsp_meta_schedule' );
      $title = sprintf( __( 'Scheduled activity saved successfully. Will publish on %s.', 'fwp-bsp' ), wp_date( 'M d, Y H:i A', strtotime( $fwpbsp_meta_schedule ) ) );
      $title = str_replace( [
        '{id}', '{datetime}'
      ], [
        $activity_id, wp_date( get_fwp_option( 'fwp_bsp_notifydate-formate', 'M d, Y H:i A' ), strtotime( $fwpbsp_meta_schedule ) )
      ], __( get_fwp_option( 'fwp_bsp_notifypaused-text', 'Scheduled activity ({id}) saved successfully. Will publish on {datetime}.' ), 'fwp-bsp' ) );
      $text = $title;
      $link = site_url( '/activity/p/' . $activity_id . '/' );
      if ( 'string' === $format ) {
        $return = '<a href="' . ( is_FwpActive( 'fwp_bsp_notify-link' ) ? esc_url( $link ) : 'javascript:void(0);' ) . '" title="' . esc_attr( $title ) . '">' . esc_html( $text ) . '</a>';
        // $return = apply_filters( 'fwp_bsp_notification_html', $return, $link, (int) $total_items, $text, $title );
        // Deprecated BuddyBar
        // wp_die( $return );
        echo wp_kses( $return, [
          'a' => [
            'href' => [],
            'title' => []
          ]
        ] );
        return $return;
      } else {
        $return = [
          'text' => $text,
          'link' => is_FwpActive( 'fwp_bsp_notify-link' ) ? $link : false
        ];
        return $return;
      }
    } else {
      return $action;
    }
  }

  public function fwp_bsp_notification_html( $html, $link, $total, $text, $title ) {
    return $html;
  }
  public function fwp_bsp_notification_object( $object, $link, $total, $text, $title ) {
    return $object;
  }
}