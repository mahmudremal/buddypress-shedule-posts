<?php
/**
 * Loadmore Single Posts
 *
 * @package FutureWordPress BSP
 */

namespace FUTUREWORDPRESS_PROJECT\Inc;

use FUTUREWORDPRESS_PROJECT\Inc\Traits\Singleton;
// use \WP_Query;

class Dashboard {

	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
    add_action( 'bp_setup_nav', [ $this, 'bp_setup_nav' ], 10, 0 );
    // bp_activity_set_{scope}_scope_args https://www.buddyboss.com/resources/reference/hooks/bp_activity_set_scope_scope_args/
    add_filter( 'bp_activity_set_scheduled_scope_args', [ $this, 'bp_activity_set_scheduled_scope_args' ], 10, 2 );
    // add_filter( 'bp_activity_set_activity_scope_args', [ $this, 'bp_activity_set_activity_scope_args' ], 10, 2 );
    // add_filter( 'bp_nouveau_feedback_messages', [ $this, 'bp_nouveau_feedback_messages' ], 10, 1 );

    add_shortcode( 'scheduled-activity', [ $this, 'scheduledActivity' ] );


    add_action( 'bp_setup_nav', [ $this, 'add_scheduledTabs' ], 10, 0 );

    add_action( 'wp_ajax_fwp_bsp_get_user_scheduled_posts', [ $this, 'ajaxData' ], 10, 0 );
    // add_action( 'wp_ajax_nopriv_fwp_bsp_get_user_scheduled_posts', [ $this, 'ajaxData' ], 10, 0 );

    add_action( 'wp_ajax_fwp_bsp_shedule_post_delete', [ $this, 'ajaxDelete' ], 10, 0 );
    // add_action( 'wp_ajax_nopriv_fwp_bsp_shedule_post_delete', [ $this, 'ajaxDelete' ], 10, 0 );

    add_action( 'admin_post_fwp-bsp-activity-re-edit', [ $this, 'updateActivity' ], 10, 0 );
    // add_action( 'admin_post_nopriv_fwp-bsp-activity-re-edit', [ $this, 'updateActivity' ], 10, 0 );
	}
  private function allowAllProfile() {
    return true;
  }
  public function bp_setup_nav() {
      global $bp;
      if( ! is_user_logged_in() ) {return;}
      $current_profile_id = intval( bp_displayed_user_id() );
		  $logged_user_id     = intval( get_current_user_id() );
      if( $current_profile_id != $logged_user_id ) {return;}
      
      // if ( is_user_logged_in() && $logged_user_id == $current_profile_id ) {
      //   $title = esc_html__( 'My scheduled', 'cbxwpbookmarkaddon' );
      // } else {
      //   $title = sprintf( esc_html__( '%s\'s scheduled', 'cbxwpbookmarkaddon' ), bp_core_get_user_displayname( $current_profile_id ) );
      // }
      // if ( bp_is_active( 'activity' ) && $this->allowAllProfile() ) {
      //   //trying custom bookmarked stream
      //   // Determine user to use.
      //   if ( bp_displayed_user_domain() ) {
      //     $user_domain = bp_displayed_user_domain();
      //   } elseif ( bp_loggedin_user_domain() ) {
      //     $user_domain = bp_loggedin_user_domain();
      //   } else {
      //     return;
      //   }
      // }
      // $slug          = bp_get_activity_slug();
			// $activity_link = trailingslashit( $user_domain . $slug );

      // $bnews_general_settings = get_option( 'bnews_general_settings' );
      // // print_r( $bnews_general_settings );wp_die();
      // $first_tab              = ( isset( $bnews_general_settings['first_tab'] ) ) ? $bnews_general_settings['first_tab'] : 'scheduled';
      $parent_slug = 'activity';$position = 20;
      // if ( 'scheduled' == $first_tab ) {$position = 0;}
      
      // Add subnav item.
      bp_core_new_subnav_item( [
        'name'                => __( 'Scheduled', 'domain' ),
        'slug'                => 'scheduled',
        'position'            => $position,
        'parent_url'          => $bp->displayed_user->domain . $parent_slug . '/',
        'parent_slug'         => $parent_slug,
        'screen_function'     => [ $this, 'bp_activity_screen_my_activity' ],
        'link'                => $bp->displayed_user->domain . $parent_slug . '/scheduled',
        'show_for_displayed_user' => false,
        'default_subnav_slug' => 'scheduled',
        'item_css_id'         => $bp->messages->id // 'activity-scheduled'
      ] );
  }
  public function bp_activity_screen_my_activity() {
    add_action( 'bp_template_title', [ $this, 'bp_template_title' ], 10, 0 );
		add_action( 'bp_template_content', [ $this, 'bp_template_content' ], 10, 0 );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}
  public function bp_template_title() {
    esc_html_e( 'Scheduled Activity', 'domain' );
  }
	public function bp_template_content() {
		if ( is_user_logged_in() ) {
			bp_get_template_part( 'activity/post-form' );
    // } else {
      // wp_login_form( [ 'echo' => true ] );
      // echo do_shortcode( '[scheduled-activity]' );
    }
	}
  public function bp_activity_set_scheduled_scope_args( $retval = [], $filter = [] ) {

		// Determine the user_id.
    $user_id = bp_loggedin_user_id();
    /*
      if ( ! empty( $filter['user_id'] ) ) {
        $user_id = $filter['user_id'];
      } else {
        $user_id = bp_displayed_user_id()
          ? bp_displayed_user_id()
          : bp_loggedin_user_id();
      }
    */

		// Determine the scheduled posts IDs.
		$scheduledIds = $this->getScheduled();
    if( count( $scheduledIds ) <= 0 ) {
      $scheduledIds = [ -1 ];
    }
    
		$retval = [
			'relation' => 'AND',
			[
				'column'  => 'id',
				'compare' => 'IN',
				'value'   => $scheduledIds
			],
      // [
			// 	'column' => 'user_id',
			// 	'compare' => '=',
			// 	'value'  => $user_id
      // ],
      // [
			// 	'column' => 'hide_sitewide',
			// 	// 'compare' => '=',
			// 	'value'  => 1
      // ],
			// Overrides.
			'override' => [
				'display_comments' => false,
				'filter'           => [ 'user_id' => $user_id ],
				'show_hidden'      => true
      ],
    ];

		return $retval;
	}
  public function bp_activity_set_activity_scope_args( $retval = [], $filter = [] ) {

		$scheduledIds = $this->getScheduled();
    if( count( $scheduledIds ) <= 0 ) {
      $scheduledIds = [ -1 ];
    }
    
		$retval[ 'relation' ] = 'AND';
		$retval[] = [
      'column'  => 'id',
      'compare' => 'IN',
      'value'   => $scheduledIds
    ];

		return $retval;
	}
  public function bp_nouveau_feedback_messages( $args ) {
    global $bp;
    $args[ 'activity-loop-none' ] = [
      'type'    => 'info',
      'message' => __( 'Sorry, there was no scheduled activity.', 'domain' )
    ];
    return $args;
  }

  public function scheduledActivity( $args ) {
    $args = wp_parse_args( $args, [
      'limit'       => 12
    ] );
    ?>
    <?php
  }
  private function getScheduled( $args = [] ) {
    global $wpdb;$IDs = [];$user_id = bp_loggedin_user_id();
    if( ! $user_id || empty( $user_id ) ) {return $IDs;}
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT act_m.activity_id FROM {$wpdb->prefix}bp_activity act LEFT JOIN {$wpdb->prefix}bp_activity_meta act_m ON act.id = act_m.activity_id WHERE act_m.meta_key = 'fwpbsp_meta_schedule' AND act_m.meta_value != '' AND act.hide_sitewide = 1 AND act.user_id = %d AND NOW() <= date(act_m.meta_value);", $user_id ) );
    if( $results && count( $results ) >= 1 ) {
      foreach( $results as $i => $row ) {
        $IDs[] = $row->activity_id;
      }
    }
    // $this->die( $IDs );
    return $IDs;
  }



  public function add_scheduledTabs() {
    global $bp;
    if( ! is_user_logged_in() ) {return;}
    $current_profile_id = intval( bp_displayed_user_id() );
    $logged_user_id     = intval( get_current_user_id() );
    if( $current_profile_id != $logged_user_id ) {return;}
    
    bp_core_new_nav_item( array(
      'name'                  => 'Schedule Activities',
      'slug'                  => 'schedule-activities',
      'parent_url'            => $bp->displayed_user->domain,
      'parent_slug'           => $bp->profile->slug,
      'screen_function'       => [ $this, 'schedule_posts_screen' ],			
      'position'              => 200,
      'default_subnav_slug'   => 'schedule-activities'
    ) );
      
    // bp_core_new_subnav_item( array(
    //   'name'              => 'Upcoming',
    //   'slug'              => 'upcoming',
    //   'parent_url'        => trailingslashit( bp_displayed_user_domain() . 'schedule-activities' ),
    //   'parent_slug'       => 'schedule-activities',
    //   'screen_function'   => [ $this, 'upcoming_screen' ],
    //   'position'          => 100,
    //   'user_has_access'   => bp_is_my_profile()
    // ) );
    // bp_core_new_subnav_item( array(
    //   'name'              => 'Day Passed',
    //   'slug'              => 'day-passed',
    //   'parent_url'        => trailingslashit( bp_displayed_user_domain() . 'schedule-activities' ),
    //   'parent_slug'       => 'schedule-activities',
    //   'screen_function'   => [ $this, 'day_passed_screen' ],
    //   'position'          => 150,
    //   'user_has_access'   => bp_is_my_profile()
    // ) );
  
  }
  public function schedule_posts_screen() {
      add_action( 'bp_template_title', [ $this, 'schedule_posts_screen_title' ] );
      add_action( 'bp_template_content', [ $this, 'schedule_posts_screen_content' ] );
      bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
  }
  public function schedule_posts_screen_title() { 
    esc_html_e( 'Scheduled Activities', 'domain' );
  }
  public function schedule_posts_screen_content() { 
    ?>
    <div id="fwp-schedule-calendar"></div>
    <?php
    include_once FUTUREWORDPRESS_PROJECT_DIR_PATH . '/template-parts/activity-edit-popup.php';
  }
  
  
  public function upcoming_screen() {
    add_action( 'bp_template_content', [ $this, 'upcoming_screen_content' ] );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
  }
  public function upcoming_screen_content() { 
    ?>
    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Culpa mollitia ad consequuntur earum in eius pariatur et ea rem distinctio, sed asperiores dolores quibusdam aliquid voluptas totam obcaecati natus velit!
    <?php
  }
  
  public function day_passed_screen() {
      add_action( 'bp_template_content', [ $this, 'day_passed_screen_content' ] );
      bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
  }
  public function day_passed_screen_content() { 
    echo 'this_month'; 
  }
  
  
  
  public function die( $args = [] ) {
    print_r( $args );// wp_die();
  }

  private function getScheduledItems( $args = [] ) {
    global $wpdb;$IDs = [];$user_id = bp_loggedin_user_id();
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}bp_activity WHERE user_id = %d AND type = %s ORDER BY id DESC LIMIT 0, 500;", $user_id, 'activity_update' ) );
    if( $results && count( $results ) >= 1 ) {
      foreach( $results as $i => $row ) {
        $IDs[] = $row->id;
      }
    }
    // $this->die( $IDs );
    return $IDs;
  }

  public function ajaxData() {
    if( ! isset( $_POST[ 'nonce' ] ) || empty( $_POST[ 'nonce' ] ) || ! wp_verify_nonce( $_POST[ 'nonce' ], 'fwp_bsp_ajax_post_nonce' ) ) {
      wp_send_json_error( __( 'Illigal request detected.', 'domain' ), 200 );
    }
    $args = [];$query = '&action=activity_update';$query .= '&show_hidden=true';
    if( isset( $_POST[ 'currentab' ] ) && $_POST[ 'currentab' ] == 'upcoming' ) {
      $IDs = $this->getScheduledItems();$query .= ( count( $IDs ) >= 1 ) ? '&include=' . implode( ',', $IDs ) : '';
    }
    if( bp_has_activities( bp_ajax_querystring( 'activity' ) . $query ) ) :
      while( bp_activities() ) : bp_the_activity();
        global $activities_template;$content = ''; // bp_get_activity_content_body()
        if( isset( $activities_template->activity ) && isset( $activities_template->activity->content ) && ! in_array( $activities_template->activity->content, [ '&nbsp;', '&#8203;', '' ] ) ) {
          $content = $activities_template->activity->content;$content = str_replace( [ '<!-- Bypass embed -->' ], [ '' ], $content );
        }
        $scheduled_on = bp_activity_get_meta( bp_get_activity_id(), 'fwpbsp_meta_schedule' );
        $scheduled_on = empty( $scheduled_on ) ? bp_get_activity_date_recorded() : $scheduled_on;
        // locate_template( array( 'activity/entry.php' ), true, false );
        $args[] = [
          // id: bp_get_activity_id(),
          // 'title'         => bp_get_the_title,
          'start'         => '2022-11-10',
          'extendedProps' => [
            'isHTML'      => true,
            // url: 'http://google.com/',
            // textEscape: true
            'html'         => '
            <div class="fc-content">
              <div class="fwp-bsp--event-post" data-activity="257769" data-post-type="' . bp_get_activity_type() . '">
                <div class="postlink">
                  <span class="posttime">[' . wp_date( 'h:i a', strtotime( $scheduled_on ) ) . ']</span>
                </div>
                <div class="postactions">
                  <div class="action-bars">
                    <a class="fwp-bsp-quickedit" href="javascript:void(0)" data-type="quickedit" data-activity="' . esc_attr( bp_get_activity_id() ) . '" data-schedule="' . esc_attr( $scheduled_on ) . '" data-content="' . esc_attr( $content ) . '"><i class="dashicons dashicons-welcome-write-blog"></i><span>' . esc_html__( 'Quick Edit', 'domain' ) . '</span></a>
                    <a class="fwp-bsp-EventDelete" href="javascript:void(0)" data-activity="' . esc_attr( bp_get_activity_id() ) . '"><i class="dashicons dashicons-trash"></i> <span>' . esc_html__( 'Delete', 'domain' ) . '</span></a>
                    <a href="' . esc_url( bp_activity_get_permalink( bp_get_activity_id() ) ) . '" target="_blank"><i class="dashicons dashicons-external"></i> <span>' . esc_html__( 'View', 'domain' ) . '</span></a>
                  </div>
                </div>
              </div>
            </div>'
          ]
        ];
      endwhile;
    endif;
    wp_send_json_success( $args, 200 );
  }
  public function ajaxDelete() {
    if( ! isset( $_POST[ 'nonce' ] ) || empty( $_POST[ 'nonce' ] ) || ! wp_verify_nonce( $_POST[ 'nonce' ], 'fwp_bsp_ajax_post_nonce' ) ) {
      wp_send_json_error( __( 'Illigal request detected.', 'domain' ), 200 );
    }
    // if( ! bp_is_activity_component() ) {wp_send_json_error( __( 'Activity dsiabled. You\'ve to re-enable first.', 'domain' ), 200 );}
    if( ! isset( $_POST[ 'activity' ] ) || empty( $_POST[ 'activity' ] ) ) {
      wp_send_json_error( __( 'Specific activity not detected.', 'domain' ), 200 );
    }
    $activity_id = $_POST[ 'activity' ];$activityUserId = get_current_user_id();
    do_action( 'bp_activity_before_action_delete_activity', $activity_id, $activityUserId );
    if ( bp_activity_delete( array( 'id' => $activity_id, 'user_id' => $activityUserId ) ) ) {
      wp_send_json_success( __( 'Successfully deleted this activity.', 'domain' ), 200 );
    }
    do_action( 'bp_activity_action_delete_activity', $activity_id, $activityUserId );

  }
  public function updateActivity() {
    global $wp, $wpdb, $bp;
    // print_r( $bp->activity->table_name );
    if( ! isset( $_POST[ 'activity-re-edit' ] ) || empty( $_POST[ 'activity-re-edit' ] ) || ! wp_verify_nonce( $_POST[ 'activity-re-edit' ], 'fwp-bsp-activity-re-edit' ) ) {
      wp_die( __( 'Illigal request detected.', 'domain' ) );
    }
    if( isset( $_POST[ 'activity' ] ) && count( $_POST[ 'activity' ] ) >= 3 ) {
      $request = $_POST[ 'activity' ];
      if( isset( $request[ 'id' ] ) && ! empty( $request[ 'id' ] ) &&  isset( $request[ 'content' ] ) && ! empty( $request[ 'content' ] ) &&  isset( $request[ 'schedule' ] ) && ! empty( $request[ 'schedule' ] ) ) {
        $has_activity = true;
        // $has_activity = bp_activity_get( [// bp_has_activities
        //   'in'            => [ $request[ 'id' ] ],
        //   'max'           => 1,
        //   'user_id'       => get_current_user_id(),
        //   'show_hidden'   => true
        // ] );
        if( true || ( $has_activity && isset( $has_activity[ 'activities' ] ) && isset( $has_activity[ 'activities' ][0] ) ) ) {
          // $activity_id = bp_activity_add( [
          //   'id'              => $request[ 'id' ],
          //   'content'         => $request[ 'content' ],
          //   'hide_sitewide'   => true
          // ] );
          $wpdb->query( $wpdb->prepare(
            "UPDATE {$bp->activity->table_name} SET content = %s, hide_sitewide = 1 WHERE id = %d AND user_id = %d;",
            stripslashes( $request[ 'content' ] ), $request[ 'id' ], get_current_user_id()
          ) );
          $activity_id = true;
          // date( 'Y-m-d h:i:s a', strtotime( $request[ 'schedule' ] ) );
          // print_r( [
          //   $has_activity,
          //   $activity_id,
          //   bp_activity_update_meta( $request[ 'id' ], 'fwpbsp_meta_schedule', $request[ 'schedule' ] ),
          //   $request[ 'id' ], 'fwpbsp_meta_schedule', $request[ 'schedule' ]
          //   ] );
          if( $activity_id ) {
            bp_activity_update_meta( $request[ 'id' ], 'fwpbsp_meta_schedule', $request[ 'schedule' ] );
            wp_safe_redirect( site_url( wp_get_referer() ) );
          } else {
            wp_die( __( 'Something happens while tring to validate this activity and updating it.', 'domain' ), __( 'Error detected while processing.', 'domain' ) );
          }
        } else {
          wp_die( __( 'Activity failed to validate.', 'domain' ), __( 'Activity not found.', 'domain' ) );
        }
      } else {
        wp_die( __( 'Problem detected in your request. Please fillup all things first.', 'domain' ), __( 'Form validation error.', 'domain' ) );
      }
    }
  }

  private function maybeNecessery() {
    // bp_activity_post_update https://www.buddyboss.com/resources/reference/functions/bp_activity_post_update/
  }
}
