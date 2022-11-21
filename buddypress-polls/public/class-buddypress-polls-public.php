<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Polls
 * @subpackage Buddypress_Polls/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Buddypress_Polls
 * @subpackage Buddypress_Polls/public
 * @author     wbcomdesigns <admin@wbcomdesigns.com>
 */
class Buddypress_Polls_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Polls_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Polls_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $wp_styles, $post;

		$rtl_css = is_rtl() ? '-rtl' : '';
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css' . $rtl_css . '/buddypress-polls-public.css', array(), time(), 'all' );
		wp_register_style( $this->plugin_name . '-time', plugin_dir_url( __FILE__ ) . 'css/jquery.datetimepicker.css', array(), time(), 'all' );

		if ( ! wp_style_is( 'wb-font-awesome', 'enqueued' ) ) {
			wp_register_style( 'wb-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
		}

		wp_register_style(
			$handle = 'wb-icons',
			$src    = plugin_dir_url( __FILE__ ) . 'css/wb-icons.css',
			$deps   = array(),
			$ver    = time(),
			$media  = 'all'
		);

		$current_component = '';
		if ( isset( $post->ID ) && '' !== $post->ID && '0' !== $post->ID ) {
			$_elementor_controls_usage = get_post_meta( $post->ID, '_elementor_controls_usage', true );
			if ( ! empty( $_elementor_controls_usage ) ) {
				foreach ( $_elementor_controls_usage as $key => $value ) {
					if ( 'buddypress_shortcode_activity_widget' === $key || 'bp_newsfeed_element_widget' === $key || 'bbp-activity' === $key ) {
						$current_component = 'activity';
						break;
					}
				}
			}
		}
		$srcs = array_map( 'basename', (array) wp_list_pluck( $wp_styles->registered, 'src' ) );
		if ( is_buddypress()
			|| is_active_widget( false, false, 'bp_poll_activity_graph_widget', true )
			|| is_active_widget( false, false, 'bp_poll_create_poll_widget', true )
			|| ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'activity-listing' ) ) )
			|| ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'bppfa_postform' ) ) )
			|| ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'bp_polls' ) ) )
			|| 'activity' === $current_component
			) {

			wp_enqueue_style( $this->plugin_name );
			wp_enqueue_style( $this->plugin_name . '-time' );
			if ( ! wp_style_is( 'wb-font-awesome', 'enqueued' ) ) {
				wp_enqueue_style( 'wb-font-awesome' );
			}
			if ( ! wp_style_is( 'wb-icons', 'enqueued' ) ) {
				wp_enqueue_style( 'wb-icons' );
			}
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Polls_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Polls_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $post;

		wp_register_script( $this->plugin_name . '-timejs', plugin_dir_url( __FILE__ ) . 'js/jquery.datetimepicker.js', array( 'jquery' ), time(), false );
		wp_register_script( $this->plugin_name . '-timefulljs', plugin_dir_url( __FILE__ ) . 'js/jquery.datetimepicker.full.js', array( 'jquery' ), time(), false );

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-polls-public.js', array( 'jquery' ), time(), false );

		if ( 'REIGN' === wp_get_theme() || 'REIGN Child' === wp_get_theme() ) {
			$body_polls_class = true;
		} else {
			$body_polls_class = false;
		}

		$rt_poll_fix = false;
		if ( class_exists( 'RTMedia' ) ) {
			$rt_poll_fix = true;
		}

		$active_template = get_option( '_bp_theme_package_id' );
		if ( 'legacy' === $active_template ) {
			$nouveau = false;
		} elseif ( 'nouveau' === $active_template ) {
			$nouveau = true;
		}

		$bpolls_settings = get_site_option( 'bpolls_settings' );
		wp_localize_script(
			$this->plugin_name,
			'bpolls_ajax_object',
			array(
				'ajax_url'           => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'         => wp_create_nonce( 'bpolls_ajax_security' ),
				'submit_text'        => __( 'Submitting vote', 'buddypress-polls' ),
				'optn_empty_text'    => __( 'Please select your choice.', 'buddypress-polls' ),
				'reign_polls'        => $body_polls_class,
				'rt_poll_fix'        => $rt_poll_fix,
				'nouveau'            => $nouveau,
				'buddyboss'          => buddypress()->buddyboss,
				'polls_option_lmit'  => ( isset( $bpolls_settings['options_limit'] ) ) ? $bpolls_settings['options_limit'] : 5,
				'poll_limit_voters'  => ( isset( $bpolls_settings['poll_limit_voters'] ) ) ? $bpolls_settings['poll_limit_voters'] : 3,
				/* translators: %d: Polls Max Options */
				'poll_max_options'   => __( 'The max number of allowed options is %d.', 'buddypress-polls' ),
				'add_poll_text'      => __( 'Add a poll', 'buddypress-polls' ),
				'delete_polls_title' => __( 'Delete option', 'buddypress-polls' ),
				'delete_polls_msg'   => __( 'Are you sure that you want to delete this option from the poll?', 'buddypress-polls' ),
				'cancel_polls_btn'   => __( 'Cancel', 'buddypress-polls' ),
				'delete_polls_btn'   => __( 'Delete', 'buddypress-polls' ),
				'poll_revoting'      => ( isset( $bpolls_settings['poll_revoting'] ) ) ? $bpolls_settings['poll_revoting'] : '',
				'poll_user'          => get_current_user_id(),
			)
		);

		$current_component = '';
		if ( isset( $post->ID ) && '' !== $post->ID && '0' !== $post->ID ) {
			$_elementor_controls_usage = get_post_meta( $post->ID, '_elementor_controls_usage', true );
			if ( ! empty( $_elementor_controls_usage ) ) {
				foreach ( $_elementor_controls_usage as $key => $value ) {
					if ( 'buddypress_shortcode_activity_widget' === $key || 'bp_newsfeed_element_widget' === $key || 'bbp-activity' === $key ) {
						$current_component = 'activity';
						break;
					}
				}
			}
		}

		if ( is_buddypress()
				|| is_active_widget( false, false, 'bp_poll_activity_graph_widget', true )
				|| is_active_widget( false, false, 'bp_poll_create_poll_widget', true )
				|| ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'activity-listing' ) ) )
				|| ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'bppfa_postform' ) ) )
				|| ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'bp_polls' ) ) )
				|| 'activity' === $current_component
				) {
			if ( ! wp_script_is( 'jquery-ui-sortable', 'enqueued' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}
			wp_enqueue_media();
			wp_enqueue_script( $this->plugin_name . '-timejs' );
			wp_enqueue_script( $this->plugin_name . '-timefulljs' );

			wp_enqueue_script( $this->plugin_name );

		}
	}

	/**
	 * Bpolls_is_user_allowed_polls
	 */
	public function bpolls_is_user_allowed_polls() {
		$bpolls_settings = get_site_option( 'bpolls_settings' );
		global $current_user;

		if ( isset( $bpolls_settings['limit_poll_activity'] ) && 'user_role' === $bpolls_settings['limit_poll_activity'] ) {
			$exists             = false;
			$allowed_user_roles = ( isset( $bpolls_settings['poll_user_role'] ) ) ? $bpolls_settings['poll_user_role'] : array();
			if ( count( $allowed_user_roles ) > 0 ) {
				foreach ( $current_user->roles as $role ) {
					if ( in_array( $role, $allowed_user_roles, true ) ) {
						$exists = true;
					}
				}
				if ( ! $exists ) {
					return false;
				}
			} else {
				return false;
			}
		}

		if ( isset( $bpolls_settings['limit_poll_activity'] ) && 'member_type' === $bpolls_settings['limit_poll_activity'] ) {
			$member_type = bp_get_member_type( $current_user->ID );
			if ( ! isset( $bpolls_settings['poll_member_type'] ) || ! in_array( $member_type, $bpolls_settings['poll_member_type'], true ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Function to render polls html.
	 *
	 * @since    1.0.0
	 */
	public function bpolls_polls_update_html() {
		if ( $this->bpolls_is_user_allowed_polls() ) {
			$bpolls_settings   = get_site_option( 'bpolls_settings' );
			$polls_option_lmit = ( isset( $bpolls_settings['options_limit'] ) ) ? $bpolls_settings['options_limit'] : 5;

			?>
		<div class="post-elements-buttons-item bpolls-html-container">
			<span class="bpolls-icon bp-tooltip" data-bp-tooltip-pos="up" data-bp-tooltip="<?php esc_attr_e( 'Add a poll', 'buddypress-polls' ); ?>"><i class="wb-icons wb-icon-bar-chart"></i></span>
		</div>
			<?php
		}
	}

	/**
	 * Function to render poll options html
	 *
	 * @since 3.7.3
	 */
	public function bppolls_polls_options_container() {

		if ( ! $this->bpolls_is_user_allowed_polls() ) {
			return false;
		}
		$bpolls_settings = get_site_option( 'bpolls_settings' );

		global $current_user;
		$multi_true = false;
		if ( isset( $bpolls_settings['multiselect'] ) ) {
			$multi_true = true;
		}
		$add_option_true = false;
		if ( isset( $bpolls_settings['user_additional_option'] ) ) {
			$add_option_true = true;
		}
		$hide_results = true;
		if ( isset( $bpolls_settings['hide_results'] ) ) {
			$hide_results = false;
		}

		$poll_cdate = false;
		if ( isset( $bpolls_settings['close_date'] ) ) {
			$poll_cdate = true;
		}

		$image_attachment = false;
		if ( isset( $bpolls_settings['enable_image'] ) ) {
			$image_attachment = true;
		}
		?>
		<div class="bpolls-polls-option-html">
			<div class="bpolls-cancel-div">
				<a class="bpolls-cancel" href="JavaScript:void(0);"><?php esc_html_e( 'Cancel Poll', 'buddypress-polls' ); ?></a>
			</div>
			<div class="polls-option-image-div">
				<div class="bpolls-option-actions-wrap">
					<div class="bpolls-sortable">
						<div class="bpolls-option">
							<a class="bpolls-sortable-handle" title="Move" href="#"><i class="fa fa-arrows-alt"></i></a>
							<input name="bpolls_input_options" class="bpolls-input" placeholder="<?php esc_html_e( 'Option 1', 'buddypress-polls' ); ?>" type="text">
							<a class="bpolls-option-delete" title="Delete" href="JavaScript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
						</div>
						<?php if ( isset( $bpolls_settings['options_limit'] ) && $bpolls_settings['options_limit'] > 1 ) : ?>
						<div class="bpolls-option">
							<a class="bpolls-sortable-handle" title="Move" href="#"><i class="fa fa-arrows-alt"></i></a>
							<input name="bpolls_input_options" class="bpolls-input" placeholder="<?php esc_html_e( 'Option 2', 'buddypress-polls' ); ?>" type="text">
							<a class="bpolls-option-delete" title="Delete" href="JavaScript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
						</div>
						<?php endif; ?>
					</div>
					<div class="bpolls-option-action">
						<a href="JavaScript:void(0);" class="bpolls-add-option button"><?php esc_html_e( 'Add new option', 'buddypress-polls' ); ?></a>
						<?php if ( $poll_cdate ) { ?>
							<div class="bpolls-date-time">
								<input id="bpolls-datetimepicker" name="bpolls-close-date" type="textbox" value="" placeholder="<?php esc_html_e( 'Poll closing date & time', 'buddypress-polls' ); ?>">
							</div>
						<?php } ?>
					</div>
					<?php if ( $multi_true ) { ?>
						<div class="bpolls-checkbox">
							<input id="bpolls-alw-multi" name="bpolls_multiselect" class="bpolls-allow-multiple" type="checkbox" value="yes">
							<label class="lbl" for="bpolls-alw-multi"><?php esc_html_e( 'Allow members to choose multiple poll options.', 'buddypress-polls' ); ?></label>
						</div>
					<?php } ?>

					<?php if ( $add_option_true ) { ?>
						<div class="bpolls-checkbox">
							<input id="bpolls-alw-user-additional-option" name="bpolls_user_additional_option" class="bpolls-allow-user-additional-option" type="checkbox" value="yes">
							<label class="lbl" for="bpolls-alw-user-additional-option"><?php esc_html_e( 'Allow members to add their poll options.', 'buddypress-polls' ); ?></label>
						</div>
					<?php } ?>

					<?php if ( $hide_results ) { ?>
						<div class="bpolls-checkbox">
							<input id="bpolls-alw-user-hide-results" name="bpolls_user_hide_results" class="bpolls-allow-user-hide-results" type="checkbox" value="yes">
							<label class="lbl" for="bpolls-alw-user-hide-results"><?php esc_html_e( 'Hide poll results from members who have not voted yet. ', 'buddypress-polls' ); ?></label>
						</div>
					<?php } ?>

					<?php if ( isset( $bpolls_settings['enable_thank_you_message'] ) ) { ?>
						<div class="bpolls-checkbox bpolls-feedback">
							<span><?php esc_html_e( 'Follow-up', 'buddypress-polls' ); ?></span>
							<input type="text" id="bpolls-thankyou-feedback" name="bpolls_thankyou_feedback" class="bpolls-thankyou-feedback"  value="" placeholder="<?php esc_html_e( 'Enter Message', 'buddypress-polls' ); ?>">

						</div>
					<?php } ?>
					<?php if ( $image_attachment ) { ?>
						<button type='button' class="dashicons dashicons-admin-media" id="bpolls-attach-image"></button>
					<?php } ?>
				</div>
				<?php if ( $image_attachment ) { ?>
					<div class="bpolls-image-upload">
						<img id="bpolls-image-preview" />
						<input type="hidden" id="bpolls-attachment-url" name="bpolls-attachment-url">
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Filters the default activity types to add poll type activity.
	 *
	 * @since 1.0.0
	 *
	 * @param array $types Default activity types to moderate.
	 */
	public function bpolls_add_polls_type_activity( $types ) {
		$types[] = 'activity_poll';
		return $types;
	}

	/**
	 * Register the activity stream actions for poll updates.
	 *
	 * @since 1.0.0
	 */
	public function bpolls_register_activity_actions() {
		$bp = buddypress();
		bp_activity_set_action(
			$bp->activity->id,
			'activity_poll',
			__( 'Polls Update', 'buddypress-polls' ),
			array( $this, 'bp_activity_format_activity_action_activity_poll' ),
			__( 'Poll', 'buddypress-polls' ),
			array( 'activity', 'group', 'member', 'member_groups' )
		);
	}

	/**
	 * Bpolls_activity_action_wall_posts
	 *
	 * @since 1.0.0
	 * @param retval   $retval retval.
	 * @param activity $activity activity.
	 */
	public function bpolls_activity_action_wall_posts( $retval, $activity ) {
		if ( 'activity_poll' !== $activity->type ) {
			return $retval;
		}

		// $retval = sprintf( __( '%s created a poll', 'buddypress-polls' ), bp_core_get_userlink( $activity->user_id ) );
		return $retval;
	}

	/**
	 * Format 'activity_poll' activity actions.
	 *
	 * @since 1.0.0
	 *
	 * @param string $action   Static activity action.
	 * @param object $activity Activity data object.
	 * @return string $action
	 */
	public function bp_activity_format_activity_action_activity_poll( $action, $activity ) {
		/* translators: %s: */
		$action = sprintf( __( '%s created a poll', 'buddypress-polls' ), bp_core_get_userlink( $activity->user_id ) );

		/**
		 * Filters the formatted activity action update string.
		 *
		 * @since 1.0.0
		 *
		 * @param string               $action   Activity action string value.
		 * @param BP_Activity_Activity $activity Activity item object.
		 */
		return apply_filters( 'bp_activity_new_poll_action', $action, $activity );
	}

	/**
	 * To set activity action for poll type activity in group.
	 *
	 * @param string $activity_action The group activity action.
	 * @since 1.0.0
	 */
	public function bpolls_groups_activity_new_update_action( $activity_action ) {
		global $bp;
		$user_id = bp_loggedin_user_id();

		// if (isset($_POST['bpolls_input_options']) && !empty($_POST['bpolls_input_options']) && is_array($_POST['bpolls_input_options']) ) {
		// $activity_action = sprintf(__('%1$s created a poll in the group %2$s', 'buddypress'), bp_core_get_userlink($user_id), '<a href="' . bp_get_group_permalink($bp->groups->current_group) . '">' . esc_attr($bp->groups->current_group->name) . '</a>');
		// }.

		$_check_type = '';
		$_check_type = get_option( 'temp_poll_type' );

		if ( $_check_type && 'yes' === $_check_type ) {
			/* translators: %s: */
			$activity_action = sprintf( __( '%1$s created a poll in the group %2$s', 'buddypress-polls' ), bp_core_get_userlink( $user_id ), '<a href="' . bp_get_group_permalink( $bp->groups->current_group ) . '">' . esc_attr( $bp->groups->current_group->name ) . '</a>' );
		}
		return $activity_action;
	}

	/**
	 * Function to set activity type activity_poll.
	 *
	 * @since 1.0.0
	 * @param array $activity Activity object.
	 */
	public function bpolls_update_poll_type_activity( $activity ) {
		// if (isset($_POST['bpolls_input_options']) && !empty($_POST['bpolls_input_options']) && is_array($_POST['bpolls_input_options']) ) {
		// $activity->type = 'activity_poll';
		// }.

		$_check_type = '';
		$_check_type = get_option( 'temp_poll_type' );

		if ( $_check_type && 'yes' === $_check_type ) {
			$activity->type = 'activity_poll';
			delete_option( 'temp_poll_type' );
		}
	}

	/**
	 * Action performed to save the activity meta on poll update.
	 *
	 * @param string $content The actvity content.
	 * @param int    $user_id User id.
	 * @param int    $activity_id Activity id.
	 * @param int    $g_activity_id Group Activity id.
	 * @since 1.0.0
	 */
	public function bpolls_update_poll_activity_meta( $content, $user_id, $activity_id, $g_activity_id = null ) {
		
		if ( isset( $g_activity_id ) ) {
			$activity_id = $g_activity_id;
		}

		/* Edit activity then return */		
		if ( isset( $_REQUEST['edit_activity'] ) && $_REQUEST['edit_activity'] == 'true' ) {
			return;
		}	
		global $wpdb;

		$activity_tbl = $wpdb->base_prefix . 'bp_activity';

		if ( isset( $_POST['bpolls_input_options'] ) && ! empty( $_POST['bpolls_input_options'] ) ) {
			if ( isset( $_POST['bpolls_multiselect'] ) && 'yes' === $_POST['bpolls_multiselect'] ) {
				$multiselect = 'yes';
			} else {
				$multiselect = 'no';
			}

			if ( isset( $_POST['bpolls_user_additional_option'] ) && 'yes' === $_POST['bpolls_user_additional_option'] ) {
				$user_additional_option = 'yes';
			} else {
				$user_additional_option = 'no';
			}

			if ( isset( $_POST['bpolls_user_hide_results'] ) && 'yes' === $_POST['bpolls_user_hide_results'] ) {
				$user_hide_results = 'yes';
			} else {
				$user_hide_results = 'no';
			}

			if ( isset( $_POST['bpolls-close-date'] ) && ! empty( $_POST['bpolls-close-date'] ) ) {
				$close_date = isset( $_POST['bpolls-close-date'] ) ? $_POST['bpolls-close-date'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			} else {
				$close_date = 0;
			}

			$poll_optn_arr = array();
			foreach ( (array) $_POST['bpolls_input_options'] as $key => $value ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				if ( '' !== $value ) {
					$poll_key                   = str_replace( '%', '', sanitize_title( $value ) );
					$poll_optn_arr[ $poll_key ] = $value;
				}
			}

			$bpolls_thankyou_feedback = '';
			if ( isset( $_POST['bpolls_thankyou_feedback'] ) && ! empty( $_POST['bpolls_thankyou_feedback'] ) ) {
				$bpolls_thankyou_feedback = isset( $_POST['bpolls_thankyou_feedback'] ) ? $_POST['bpolls_thankyou_feedback'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			}
			$poll_meta = array(
				'poll_option'              => $poll_optn_arr,
				'multiselect'              => $multiselect,
				'user_additional_option'   => $user_additional_option,
				'user_hide_results'        => $user_hide_results,
				'close_date'               => $close_date,
				'bpolls_thankyou_feedback' => $bpolls_thankyou_feedback,
			);
			bp_activity_update_meta( $activity_id, 'bpolls_meta', $poll_meta );

			$poll_image = get_option( 'temp_poll_image' );
			if ( $poll_image ) {
				bp_activity_update_meta( $activity_id, 'bpolls_image', $poll_image );
				delete_option( 'temp_poll_image' );
			}
		}
		delete_option( 'temp_poll_image' );
	}

	/**
	 * Filters the new poll activity content for current activity item.
	 *
	 * @since 1.0.0
	 * @param string $act Activity content.
	 * @param string $activity_obj Activity content posted by user.
	 */
	public function bpolls_update_poll_activity_content( $act = null, $activity_obj = array() ) {
		global $current_user;		
		$user_id      = get_current_user_id();
		$poll_user_id = isset( $activity_obj->user_id ) ? $activity_obj->user_id : '';
		$activity_id  = isset( $activity_obj->id ) ? $activity_obj->id : '';

		if ( isset( $act ) && null !== $act ) {
			$activity_id = $act;
		}
		$activity_poll_type = '';

		if ( ! empty( $activity_obj ) && '' !== $activity_obj->type ) {
			$activity_poll_type = $activity_obj->type;
		}

		$bpolls_settings        = get_site_option( 'bpolls_settings' );
		$poll_options_result    = ( isset( $bpolls_settings['poll_options_result'] ) ) ? true : false;
		$poll_revoting          = ( isset( $bpolls_settings['poll_revoting'] ) ) ? true : false;
		$polls_background_color = ( isset( $bpolls_settings['polls_background_color'] ) ) ? $bpolls_settings['polls_background_color'] : '#4caf50';

		$polls_bg_style = '';
		if ( $polls_background_color != '' ) {
			$polls_bg_style = ";background-color:$polls_background_color";
		}

				$polls_btn_style = '';
		if ( $polls_background_color != '' ) {
			$polls_btn_style = 'style="color: ' . $polls_background_color . '!important;border-color: ' . $polls_background_color . '!important"';
		}
		$activity_meta = bp_activity_get_meta( $activity_id, 'bpolls_meta' );
		$total_votes   = bp_activity_get_meta( $activity_id, 'bpolls_total_votes', true );
		$poll_image    = bp_activity_get_meta( $activity_id, 'bpolls_image', true );

		$submit       = false;
		$hide_results = false;

		$poll_style = '';
		if ( ! $poll_revoting ) {
			$poll_style = 'style="display:none;"';
		}
		$bpoll_user_vote = get_user_meta( $user_id, 'bpoll_user_vote', true );
		if ( $bpoll_user_vote ) {
			if ( ! array_key_exists( $activity_id, $bpoll_user_vote ) ) {
				$submit = true;
				if ( isset( $bpolls_settings['hide_results'] ) ) {
					$hide_results = true;
				}
				if ( isset( $activity_meta['user_hide_results'] ) && $activity_meta['user_hide_results'] == 'yes' ) {
					$hide_results = true;
				}
				$poll_style = '';
			}
		} else {
			$submit = true;
			if ( isset( $bpolls_settings['hide_results'] ) ) {
				$hide_results = true;
			}
			if ( isset( $activity_meta['user_hide_results'] ) && $activity_meta['user_hide_results'] == 'yes'  ) {
				$hide_results = true;
			}
			$poll_style = '';
		}

		$poll_closing = false;
		if ( isset( $activity_meta['close_date'] ) && isset( $bpolls_settings['close_date'] ) && $activity_meta['close_date'] != 0 ) {
			$current_time    = new DateTime( date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ) );
			$close_date      = $activity_meta['close_date'];
			$close_date_time = new DateTime( $close_date );
			if ( $close_date_time > $current_time ) {
				$poll_closing = true;
			}
		} else {
			$poll_closing = true;
		}

		$u_meta = array();
		if ( isset( $bpoll_user_vote[ $activity_id ] ) ) {
			$u_meta = $bpoll_user_vote[ $activity_id ];
		}
		if ( 'activity_poll' === $activity_poll_type || isset( $activity_meta['poll_option'] ) ) {
			$poll_options = ( isset( $activity_meta['poll_option'] ) ) ? $activity_meta['poll_option'] : array();

			$user_polls_option      = get_user_meta( $user_id, 'bpolls_user_options', true );
			$activity_content       = '';
			$user_additional_option = 'no';
			if ( isset( $activity_meta['user_additional_option'] ) && 'yes' === $activity_meta['user_additional_option'] ) {
				$user_additional_option = 'yes';
			}

			if ( isset( $activity_meta['multiselect'] ) && 'yes' === $activity_meta['multiselect'] ) {
				$optn_typ = 'checkbox';
			} else {
				$optn_typ = 'radio';
			}

			if ( ! empty( $poll_options ) && is_array( $poll_options ) ) {
				$activity_content .= "<div class='bpolls-options-attach-container'>";

				if ( $poll_image ) {
					$activity_content .= "<div class='bpolls-image-container'>";
					$activity_content .= "<img src='" . $poll_image . "'>";
					$activity_content .= '</div>';
				}
				$activity_content .= "<div class='bpolls-options-attach-items'><form class='bpolls-vote-submit-form' method='post' action=''>";

				foreach ( $poll_options as $key => $value ) {

					if ( isset( $activity_meta['poll_total_votes'] ) ) {
						$total_votes = $activity_meta['poll_total_votes'];
					} else {
						$total_votes = 0;
					}

					if ( isset( $activity_meta['poll_optn_votes'] ) && array_key_exists( $key, $activity_meta['poll_optn_votes'] ) ) {
						$this_optn_vote = $activity_meta['poll_optn_votes'][ $key ];
					} else {
						$this_optn_vote = 0;
					}

					if ( 0 != $total_votes ) {
						$vote_percent = round( $this_optn_vote / $total_votes * 100, 2 ) . '%';
					} elseif ( isset( $activity_meta['poll_optn_user_votes'][ $key ] ) && in_array( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) {
						$vote_percent = '100%';
					} elseif ( isset( $activity_meta['poll_optn_user_votes'][ $key ] ) && ! in_array( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) {
						$vote_percent = '0%';
					} else {
						$vote_percent = __( '(no votes yet)', 'buddypress-polls' );
					}

					$bpolls_votes_txt = '(' . $this_optn_vote . '&nbsp;' . _x( 'of', 'Poll Activity', 'buddypress-polls' ) . '&nbsp;' . $total_votes . ')';

					if ( $hide_results && ! in_array( 'administrator', (array) $current_user->roles, true ) ) {
						$vote_percent     = '';
						$bpolls_votes_txt = '';
					}

					if ( in_array( $key, $u_meta, true ) ) {
						$checked = 'checked';
					} else {
						$checked = '';
					}

					$activity_content .= "<div class='bpolls-item'>";
					$activity_content .= "<div class='bpolls-item-inner'>";

					$output                 = '';
					$activity_votes_content = '';
					$count                  = 0;

					// $activity_content .= "<span class='bpolls-votes'>" . $bpolls_votes_txt . '</span>';
					$poll_optn_user_votes = isset( $activity_meta['poll_optn_user_votes'][ $key ] ) ? $activity_meta['poll_optn_user_votes'][ $key ] : array();

					if ( ! empty( $poll_optn_user_votes ) && isset( $bpolls_settings['poll_list_voters'] ) && ! $hide_results ) {

						$count       = count( $poll_optn_user_votes );
						$liked_count = $count - $bpolls_settings['poll_limit_voters'];
						foreach ( $poll_optn_user_votes as $userkey => $user_id ) {
							// Get Users Image.
							$img_path = bp_core_fetch_avatar(
								array(
									'item_id' => $user_id,
									'type'    => 'thumb',
									'html'    => true,
								)
							);
							// How Much User Visible.
							if ( $userkey < $bpolls_settings['poll_limit_voters'] ) {

								// Get User Image Code.
								$output .= '<a data-polls-tooltip="' . bp_core_get_user_displayname( $user_id ) . '" href="' . bp_core_get_user_domain( $user_id ) . '">' . bp_core_fetch_avatar(
									array(
										'html'    => true,
										'type'    => 'thumb',
										'item_id' => $user_id,
									)
								) . '</a>';
							}
						}

						if ( isset( $output ) ) {

							if ( $liked_count > 0 ) {
								// Display Show More.
								$output .= '<a class="bp-polls-show-voters bp-polls-view-all" data-activity-id="' . $activity_id . '" data-option-id="' . $key . '" data-polls-tooltip="' . __( 'View All', 'buddypress-polls' ) . '">+' . $liked_count . '</a>';
							}
							/* translators: %s: Vote Count */
							$activity_votes_content = '<div class="bpolls-post-voted">' . $output . '</div><span class="bp-polls-voters">' . sprintf( _n( '%s Vote', '%s Votes', $count, 'buddypress-polls' ), $count ) . '</span>';

						}
					}

					$activity_content .= '<div id="activity-id-' . $activity_id . '-' . $key . '" class="bpolls-result-votes">' . $activity_votes_content . '</div>';

					$activity_content .= '<div class="bpolls-check-radio-wrap">';
					if ( ( $submit && $poll_closing && is_user_logged_in() ) || ( $poll_revoting && $poll_closing && is_user_logged_in() ) ) {
						$activity_content .= "<input id='" . $key . "' name='bpolls_vote_optn[]' value='" . $key . "' type='" . $optn_typ . "' " . $checked . ' ' . $poll_style . '>';
					}
					$activity_content .= "<label for='" . $key . "' class='bpolls-option-lbl'>" . $value . '</label>';
					$activity_content .= '</div>';

					$activity_content .= "<div class='bpolls-item-width-wrapper'>";
					// $activity_content .= "<div class='bpolls-item-width' style='width:" . $vote_percent . ";'></div>";
					$activity_content .= "<div class='bpolls-item-width' style='width:" . $vote_percent . $polls_bg_style . ";'></div>";
					$activity_content .= "<div class='bpolls-check-radio-div'></div>";
					$activity_content .= '</div>';
					if ( $poll_options_result ) {
						$activity_content .= "<span class='bpolls-percent'>" . $vote_percent . '</span>';
					}

					$activity_content .= '</div>';
					if ( isset( $user_polls_option[ 'activity-id-' . $activity_id . '-' . $key ] ) ) {
						$activity_content .= "<a href='javascript:void(0);' class='bpolls-delete-user-option' data-activity-id='" . $activity_id . "' data-option='" . $key . "' data-user-id='" . $user_id . "'><i class='wb-icons wb-icon-x'></i></a>";
					}
					$activity_content .= '</div>';
				}

				/* Add option from user end */
				if ( $user_additional_option == 'yes' && $poll_user_id != $user_id ) {
					if ( ( $submit && $poll_closing && is_user_logged_in() ) || ( $poll_revoting && $poll_closing && is_user_logged_in() ) ) {
						$activity_content .= "<div class='bpolls-add-user-item'>";
						$activity_content .= '<input type="text" class="bpoll-add-user-option" name="bpoll_user_option" value="" placeholder="' . esc_html__( 'Add poll option...', 'buddypress-polls' ) . '" data-activity-id="' . $activity_id . '" data-user-id="' . $user_id . '"/>';
						$activity_content .= '<input type="button" class="bpoll-add-option" name="bpoll_add_option" value="' . esc_html__( 'Add option', 'buddypress-polls' ) . '" data-activity-id="' . $activity_id . '" data-user-id="' . $user_id . '"/>';
						$activity_content .= '</div>';
						$activity_content .= '<p class="bpolls-add-option-error" style="display:none">' . esc_html__( 'Poll option field is empty.', 'buddypress-polls' ) . '</p>';
					}
				}

				$activity_content .= "<input type='hidden' name='bpoll_activity_id' value='" . $activity_id . "'>";
				$activity_content .= "<input type='hidden' name='bpoll_multi' value='" . $activity_meta['multiselect'] . "'>";
				$activity_content .= "<input type='hidden' name='bpoll_user_id' value='" . $user_id . "'>";

				if ( ( $submit && $poll_closing && is_user_logged_in() ) || ( $poll_revoting && $poll_closing && is_user_logged_in() ) ) {
					$activity_content .= "<a class='bpolls-vote-submit' href='javascript:void(0)' " . $polls_btn_style . '>' . __( 'Submit', 'buddypress-polls' ) . '</a>';
				}
				$activity_content .= '</form></div></div>';

				if ( isset( $act ) && $act != null ) {
					return $activity_content;
				} else {
					echo $activity_content; // phpcs:ignore WordPress.Security.EscapeOutput
				}
			}
		}
	}

	/**
	 * Ajax request to save poll vote.
	 *
	 * @since 1.0.0
	 */
	public function bpolls_save_poll_vote() {
		if ( isset( $_POST['action'] ) && 'bpolls_save_poll_vote' === $_POST['action'] ) {
			check_ajax_referer( 'bpolls_ajax_security', 'ajax_nonce' );
			$user_id         = get_current_user_id();
			$bpoll_user_vote = get_user_meta( $user_id, 'bpoll_user_vote', true );

			parse_str( $_POST['poll_data'], $poll_data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$poll_data = filter_var_array( $poll_data, FILTER_SANITIZE_STRING );

			$activity_id = $poll_data['bpoll_activity_id'];

			$activity_meta = bp_activity_get_meta( $activity_id, 'bpolls_meta' );
			$total_votes   = bp_activity_get_meta( $activity_id, 'bpolls_total_votes', true );
			if ( ! $total_votes ) {
				$total_votes = (int) 1;
			} else {
				$total_votes = (int) $total_votes + (int) 1;
			}
			if ( array_key_exists( 'poll_optn_votes', $activity_meta ) ) {
				foreach ( $activity_meta['poll_option'] as $key => $value ) {

					if ( isset( $activity_meta['poll_optn_user_votes'][ $key ] ) && in_array( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) {
						if ( ( $ukey = array_search( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) !== false ) {
							$activity_meta['poll_optn_votes'][ $key ] = $activity_meta['poll_optn_votes'][ $key ] - 1;
						}
					}

					if ( in_array( $key, $poll_data['bpolls_vote_optn'] ) ) {
						$activity_meta['poll_optn_votes'][ $key ] = $activity_meta['poll_optn_votes'][ $key ] + 1;
					}
				}
			} else {
				foreach ( $activity_meta['poll_option'] as $key => $value ) {
					if ( in_array( $key, $poll_data['bpolls_vote_optn'] ) ) {
						$ed = 1;
					} else {
						$ed = 0;
					}
					$poll_optn_votes[ $key ] = $ed;
				}
				$activity_meta['poll_optn_votes'] = $poll_optn_votes;
			}

			if ( array_key_exists( 'poll_total_votes', $activity_meta ) ) {
				if ( ! isset( $bpoll_user_vote[ $activity_id ] ) ) {
					$activity_meta['poll_total_votes'] = $activity_meta['poll_total_votes'] + 1;
				}
			} else {
				$activity_meta['poll_total_votes'] = 1;
			}

			/* Saved user id in poll option wise */
			if ( array_key_exists( 'poll_optn_user_votes', $activity_meta ) ) {
				foreach ( $activity_meta['poll_option'] as $key => $value ) {

					if ( is_array( $activity_meta['poll_optn_user_votes'][ $key ] ) && in_array( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) {
						if ( ( $ukey = array_search( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) !== false ) {
							unset( $activity_meta['poll_optn_user_votes'][ $key ][ $ukey ] );
						}
					}

					if ( in_array( $key, $poll_data['bpolls_vote_optn'] ) ) {

						$polls_existing_useid                          = isset( $activity_meta['poll_optn_user_votes'][ $key ] ) ? $activity_meta['poll_optn_user_votes'][ $key ] : array();
						$activity_meta['poll_optn_user_votes'][ $key ] = array_unique( array_merge( $polls_existing_useid, array( $user_id ) ) );
					}
				}
			} else {
				$poll_optn_user_votes = array();
				foreach ( $activity_meta['poll_option'] as $key => $value ) {
					$poll_optn_user_votes[ $key ] = array();
					if ( isset( $activity_meta['poll_optn_user_votes'][ $key ] ) && in_array( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) {
						if ( ( $key = array_search( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) !== false ) {
							unset( $activity_meta['poll_optn_user_votes'][ $key ][ $key ] );
						}
					}

					if ( in_array( $key, $poll_data['bpolls_vote_optn'] ) ) {
						$poll_optn_user_votes[ $key ] = array( $user_id );
					}
				}

				$activity_meta['poll_optn_user_votes'] = $poll_optn_user_votes;
			}

			/* saved User id in activity meta */
			$existing_useid              = isset( $activity_meta['poll_users'] ) ? $activity_meta['poll_users'] : array();
			$activity_meta['poll_users'] = array_unique( array_merge( $existing_useid, array( $user_id ) ) );

			/* Total Poll Votes count base on poll users */
			$activity_meta['poll_total_votes'] = count( $activity_meta['poll_users'] );

			bp_activity_update_meta( $activity_id, 'bpolls_meta', $activity_meta );

			bp_activity_update_meta( $activity_id, 'bpolls_total_votes', $total_votes );

			$user_vote = array();
			foreach ( $activity_meta['poll_option'] as $key => $value ) {

				if ( in_array( $key, $poll_data['bpolls_vote_optn'] ) ) {
					$user_vote[] = $key;
				}
			}

			if ( $bpoll_user_vote ) {
				// if ( ! array_key_exists( $activity_id, $bpoll_user_vote ) )
				{
					$bpoll_user_vote[ $activity_id ] = $user_vote;
					update_user_meta( $user_id, 'bpoll_user_vote', $bpoll_user_vote );
				}
			} else {
				$vote[ $activity_id ] = $user_vote;
				update_user_meta( $user_id, 'bpoll_user_vote', $vote );
			}

			$updated_votes = $this->bpolls_ajax_calculate_votes( $activity_id );

			echo wp_json_encode( $updated_votes );
			die;
		}
	}

	/**
	 * Calculate poll activity votes.
	 *
	 * @since 1.0.0
	 * @param string $activity_id activity_id.
	 */
	public function bpolls_ajax_calculate_votes( $activity_id ) {
		$user_id = get_current_user_id();

		$bpolls_settings     = get_site_option( 'bpolls_settings' );
		$poll_options_result = ( isset( $bpolls_settings['poll_options_result'] ) ) ? true : false;

		$activity_meta = bp_activity_get_meta( $activity_id, 'bpolls_meta' );

		$poll_options = isset( $activity_meta['poll_option'] ) ? $activity_meta['poll_option'] : '';

		$uptd_votes = array();
		if ( ! empty( $poll_options ) && is_array( $poll_options ) ) {
			foreach ( $poll_options as $key => $value ) {
				if ( isset( $activity_meta['poll_total_votes'] ) ) {
					$total_votes = $activity_meta['poll_total_votes'];
				} else {
					$total_votes = 0;
				}

				if ( isset( $activity_meta['poll_optn_votes'] ) && array_key_exists( $key, $activity_meta['poll_optn_votes'] ) ) {
					$this_optn_vote = $activity_meta['poll_optn_votes'][ $key ];
				} else {
					$this_optn_vote = 0;
				}

				if ( $total_votes != 0 ) {
					$vote_percent = round( $this_optn_vote / $total_votes * 100, 2 ) . '%';
				} else {
					$vote_percent = __( '(no votes yet)', 'buddypress-polls' );
				}

				if ( 0 != $total_votes ) {
					$vote_percent = round( $this_optn_vote / $total_votes * 100, 2 ) . '%';
				} elseif ( isset( $activity_meta['poll_optn_user_votes'][ $key ] ) && in_array( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) {
					$vote_percent = '100%';
				} elseif ( isset( $activity_meta['poll_optn_user_votes'][ $key ] ) && ! in_array( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) {
					$vote_percent = '0%';
				} else {
					$vote_percent = __( '(no votes yet)', 'buddypress-polls' );
				}

				$bpolls_votes_txt = '(' . $this_optn_vote . '&nbsp;' . _x( 'of', 'Poll Activity', 'buddypress-polls' ) . '&nbsp;' . $total_votes . ')';

				$output       = '';
				$vote_content = '';
				$count        = 0;

				// $activity_content .= "<span class='bpolls-votes'>" . $bpolls_votes_txt . '</span>';
				$poll_optn_user_votes = isset( $activity_meta['poll_optn_user_votes'][ $key ] ) ? $activity_meta['poll_optn_user_votes'][ $key ] : array();

				if ( ! empty( $poll_optn_user_votes ) && isset( $bpolls_settings['poll_list_voters'] ) ) {

					$count       = count( $poll_optn_user_votes );
					$liked_count = $count - $bpolls_settings['poll_limit_voters'];
					foreach ( $poll_optn_user_votes as $userkey => $user_id ) {
						// Get Users Image.
						$img_path = bp_core_fetch_avatar(
							array(
								'item_id' => $user_id,
								'type'    => 'thumb',
								'html'    => true,
							)
						);
						// How Much User Visible.
						if ( $userkey < $bpolls_settings['poll_limit_voters'] ) {

							// Get User Image Code.
							$output .= '<a data-polls-tooltip="' . bp_core_get_user_displayname( $user_id ) . '" href="' . bp_core_get_user_domain( $user_id ) . '">' . bp_core_fetch_avatar(
								array(
									'html'    => true,
									'type'    => 'thumb',
									'item_id' => $user_id,
								)
							) . '</a>';
						}
					}

					if ( isset( $output ) ) {

						if ( $liked_count > 0 ) {
							// Display Show More.
							$output .= '<a class="bp-polls-show-voters bp-polls-view-all" data-activity-id="' . $activity_id . '" data-option-id="' . $key . '" data-polls-tooltip="' . __( 'View All', 'buddypress-polls' ) . '">+' . $liked_count . '</a>';
						}
						/* translators: %s: Vote Count */
						$vote_content = '<div class="bpolls-post-voted">' . $output . '</div><span class="bp-polls-voters">' . sprintf( _n( '%s Vote', '%s Votes', $count, 'buddypress-polls' ), $count ) . '</span>';

					}
				}

				$uptd_votes[ $key ] = array(
					'vote_percent'         => $vote_percent,
					'bpolls_votes_txt'     => $bpolls_votes_txt,
					'bpolls_votes_content' => $vote_content,
				);
			}
		}
		$uptd_votes['bpolls_thankyou_feedback'] = ( isset( $activity_meta['bpolls_thankyou_feedback'] ) && $activity_meta['bpolls_thankyou_feedback'] != '' ) ? $activity_meta['bpolls_thankyou_feedback'] : '';

		return $uptd_votes;
	}

	/**
	 * Function to show poll activity entry content while embedding.
	 *
	 * @since 1.0.0
	 * @param content                 $content content.
	 * @param global_activity_content $global_activity_content global_activity_content.
	 */
	public function bpolls_bp_activity_get_embed_excerpt( $content, $global_activity_content ) {
		$activity_id = $GLOBALS['activities_template']->activity->id;
		return $content . $this->bpolls_update_poll_activity_content( $activity_id, '' );
	}

	/**
	 * Function to show poll activity entry content while embedding.
	 *
	 * @since 1.0.0
	 * @param activity_content $activity_content activity_content.
	 * @param activity_obj     $activity_obj activity_obj.
	 */
	public function bpquotes_update_pols_activity_content( $activity_content, $activity_obj ) {
		$activity_id = $activity_obj->id;
		return $activity_content . $this->bpolls_update_poll_activity_content( $activity_id, $activity_obj );
	}

	/**
	 * Function to add poll css for activity embedding.
	 *
	 * @since 1.0.0
	 */
	public function bpolls_activity_embed_add_inline_styles() {
		$css = file_get_contents( BPOLLS_PLUGIN_PATH . '/public/css/buddypress-polls-public.css' );
		$css = wp_kses( $css, array( "\'", '\"' ) );
		printf( '<style type="text/css">%s</style>', esc_attr( $css ) );
	}

	/**
	 * Bpolls_set_poll_type_true
	 *
	 * @since 1.0.0
	 */
	public function bpolls_set_poll_type_true() {
		if ( isset( $_POST['action'] ) && 'bpolls_set_poll_type_true' === $_POST['action'] ) {

			check_ajax_referer( 'bpolls_ajax_security', 'ajax_nonce' );

			$is_poll = ( isset( $_POST['is_poll'] ) ) ? wp_unslash( $_POST['is_poll'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			update_option( 'temp_poll_type', $is_poll );
		}
		wp_die();
	}

	/**
	 * Bpolls_set_poll_type_true
	 *
	 * @since 1.0.0
	 */
	public function bpolls_update_prev_polls_total_votes() {
		$args = array(
			'show_hidden' => true,
			'action'      => 'activity_poll',
			'count_total' => true,
		);

		if ( function_exists( 'bp_has_activities' ) && bp_has_activities( $args ) ) {
			global $activities_template;
			foreach ( $activities_template->activities as $key => $act_obj ) {
				$activity_meta = (array) bp_activity_get_meta( $act_obj->id, 'bpolls_meta' );
				$total_votes   = bp_activity_get_meta( $act_obj->id, 'bpolls_total_votes', true );
				if ( array_key_exists( 'poll_total_votes', $activity_meta ) && ! $total_votes ) {
					bp_activity_update_meta( $act_obj->id, 'bpolls_total_votes', (int) $activity_meta['poll_total_votes'] );
				}
			}
		}
	}

	/**
	 * Bpolls_save_image
	 *
	 * @since 1.0.0
	 */
	public function bpolls_save_image() {
		if ( isset( $_POST['action'] ) && 'bpolls_save_image' === $_POST['action'] ) {
			check_ajax_referer( 'bpolls_ajax_security', 'ajax_nonce' );
			$image_url = ( isset( $_POST['image_url'] ) ) ? wp_unslash( $_POST['image_url'] ) : ' '; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			update_option( 'temp_poll_image', $image_url );
			exit();
		}
	}


	/**
	 * Function to unset media library when user upload image using wp-media on fronted
	 *
	 * @since 3.3.0
	 * @param tabs $tabs tabs.
	 */
	public function bpolls_remove_media_library_tab( $tabs ) {

		if ( current_user_can( 'subscriber' ) || current_user_can( 'contributor' ) ) {
			unset( $tabs['library'] );

			$contributor = get_role( 'contributor' );
			$contributor->add_cap( 'upload_files' );

			$subscriber = get_role( 'subscriber' );
			$subscriber->add_cap( 'upload_files' );

		}
		return $tabs;
	}

	/**
	 * Function to unset media library title when user upload image using wp-media on fronted
	 *
	 * @since 3.3.0
	 * @param strings $strings strings.
	 */
	public function bpolls_remove_medialibrary_tab( $strings ) {
		if ( current_user_can( 'subscriber' ) || current_user_can( 'contributor' ) ) {
			unset( $strings['mediaLibraryTitle'] );
			return $strings;
		} else {
			return $strings;
		}
	}

	/**
	 * Function added the edit acivity or not with BB Plateform.
	 *
	 * @since 3.7.2
	 *
	 * @param bool   $can_edit Whether the user can edit the item.
	 * @param object $activity Current activity item object.
	 */
	public function bpolls_activity_can_edit( $can_edit, $activity ) {
			global $activities_template;

		// Try to use current activity if none was passed.
		if ( empty( $activity ) && ! empty( $activities_template->activity ) ) {
			$activity = $activities_template->activity;
		}

		// If current_comment is set, we'll use that in place of the main activity.
		if ( isset( $activity->current_comment ) ) {
			$activity = $activity->current_comment;
		}

		// Assume the user cannot edit the activity item.
		$can_edit = false;

		// Only logged in users can edit activity and Activity must be of type 'activity_update', 'activity_comment', 'activity_poll'.
		if ( is_user_logged_in() && in_array( $activity->type, array( 'activity_update', 'activity_comment', 'activity_poll' ) ) ) {

			// Users are allowed to edit their own activity.
			if ( isset( $activity->user_id ) && ( bp_loggedin_user_id() === $activity->user_id ) ) {
				$can_edit = true;
			}

			// Viewing a single item, and this user is an admin of that item.
			if ( bp_is_single_item() && bp_is_item_admin() ) {
				$can_edit = true;
			}
		}

		if ( $can_edit ) {

			// Check activity edit time expiration.
			$activity_edit_time        = (int) bp_get_activity_edit_time(); // for 10 minutes, 600.
			$bp_dd_get_time            = bp_core_current_time( true, 'timestamp' );
			$activity_edit_expire_time = strtotime( $activity->date_recorded ) + $activity_edit_time;

			// Checking if expire time still greater than current time.
			if ( - 1 !== $activity_edit_time && $activity_edit_expire_time <= $bp_dd_get_time ) {
				$can_edit = false;
			}
		}

		/**
		 * Filters whether the current user can edit an activity item.
		 *
		 * @param bool   $can_edit Whether the user can edit the item.
		 * @param object $activity Current activity item object.
		 *
		 * @since BP Polls 3.9.0
		 */
		return (bool) apply_filters( 'bppolls_activity_user_can_edit', $can_edit, $activity );
	}

	/**
	 *  Display all voters of activity polls.
	 *
	 * @return void
	 */
	public function bpolls_activity_all_voters() {
		if ( isset( $_POST['action'] ) && 'bpolls_activity_all_voters' === $_POST['action'] ) {
			check_ajax_referer( 'bpolls_ajax_security', 'ajax_nonce' );

			$activity_id          = isset( $_POST['activity_id'] ) ? sanitize_text_field( wp_unslash( $_POST['activity_id'] ) ) : '';
			$option_id            = isset( $_POST['option_id'] ) ? sanitize_text_field( wp_unslash( $_POST['option_id'] ) ) : '';
			$activity_meta        = bp_activity_get_meta( $activity_id, 'bpolls_meta' );
			$poll_optn_user_votes = isset( $activity_meta['poll_optn_user_votes'][ $option_id ] ) ? $activity_meta['poll_optn_user_votes'][ $option_id ] : array();

			ob_start();

			if ( ! empty( $poll_optn_user_votes ) ) {
				echo '<div class="bpolls-icon-dialog bpolls-user-votes-dialog is-visible">';

				echo '<div class="bpolls-icon-dialog-container">';

				echo '<div class="bpolls-modal-title"><i class="fa fa-users"></i>' . esc_html__( 'People who voted for this option', 'buddypress-polls' ) . '<i class="fa fa-times bpolls-modal-close bpolls-modal-close-icon"></i></div>';

				echo '<div class="bpolls-icon-dialog-msg">';
				echo '<div class="bpolls-users-who-list">';

				foreach ( $poll_optn_user_votes as $user_id ) {

					?>

					<div class="bpolls-list-item">
						<a href="<?php echo esc_url( bp_core_get_user_domain( $user_id ) ); ?>" class="bpolls-item-avatar">
											<?php
											echo bp_core_fetch_avatar( // phpcs:ignore WordPress.Security.EscapeOutput
												array(
													'type' => 'thumb',
													'item_id' => $user_id,
												)
											);
											?>
									</a>
						<div class="bpolls-item-data">
							<div class="bpolls-item-name"><?php echo bp_core_get_userlink( $user_id ) ; ?></div>
							<div class="bpolls-item-meta">@<?php echo esc_html( bp_core_get_username( $user_id ) ); ?></div>
						</div>
					</div>

					<?php
				}

				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';

			}

			$result_html = ob_get_contents();

			ob_end_clean();

			wp_send_json_success( $result_html );

		}
	}

	/**
	 * Embed polls activity data in rest api activity endpoint.
	 *
	 * @param  object $response get response data.
	 * @param  object $request get request data.
	 * @param  array  $activity get activity data.
	 * @return $response
	 */
	public function bpolls_activity_data_embed_rest_api( $response, $request, $activity ) {
		$bppolls_meta                                     = bp_activity_get_meta( $activity->id, 'bpolls_meta', true );
		$bppolls_vote                                     = bp_activity_get_meta( $activity->id, 'bpolls_total_votes', true );
		$response->data['bp_polls']['bpolls_meta']        = $bppolls_meta;
		$response->data['bp_polls']['bpolls_total_votes'] = $bppolls_vote;
		return $response;
	}

	/**
	 * Show the Polls Activity Data using shortcode.
	 *
	 * @param array  $atts Shortcode Attributes.
	 * @param string $content Shortcode content.
	 * @since BP Polls 4.0.0
	 */
	public function bppolls_rest_api_shortcode( $atts, $content = null ) {
		global $wpdb;
		$atts        = shortcode_atts(
			array(
				'activity_id' => null,
			),
			$atts
		);
		$activity_id = $atts['activity_id'];
		$activity_id = is_numeric( $activity_id ) ? (int) $activity_id : $activity_id;

		/* activity id is not integer then return */
		if ( ! is_int( $activity_id ) ) {
			$activity_id = 0;
			return $content;
		}
		$activity_content = '';
		ob_start();
		$activity_content .= '<div id="buddypress">';
		if ( function_exists( 'bp_is_active' ) ) {
			wp_enqueue_style( $this->plugin_name );
			wp_enqueue_style( $this->plugin_name . '-time' );
			if ( ! wp_style_is( 'wb-font-awesome', 'enqueued' ) ) {
				wp_enqueue_style( 'wb-font-awesome' );
			}
			if ( ! wp_style_is( 'wb-icons', 'enqueued' ) ) {
				wp_enqueue_style( 'wb-icons' );
			}
			wp_enqueue_script( $this->plugin_name . '-timejs' );
			wp_enqueue_script( $this->plugin_name . '-timefulljs' );
			wp_enqueue_script( $this->plugin_name );

			global $current_user;
			$user_id                = get_current_user_id();
			$bpolls_settings        = get_site_option( 'bpolls_settings' );
			$poll_options_result    = ( isset( $bpolls_settings['poll_options_result'] ) ) ? true : false;
			$poll_revoting          = ( isset( $bpolls_settings['poll_revoting'] ) ) ? true : false;
			$polls_background_color = ( isset( $bpolls_settings['polls_background_color'] ) ) ? $bpolls_settings['polls_background_color'] : '#4caf50';
			$polls_bg_style         = '';
			if ( $polls_background_color != '' ) {
				$polls_bg_style = ";background-color:$polls_background_color";
			}

			$polls_btn_style = '';
			if ( $polls_background_color != '' ) {
				$polls_btn_style = 'style="color: ' . $polls_background_color . '!important;border-color: ' . $polls_background_color . '!important"';
			}

			$submit       = false;
			$hide_results = false;
			$poll_style   = '';
			if ( ! $poll_revoting ) {
				$poll_style = 'style="display:none;"';
			}
			$bpoll_user_vote = get_user_meta( $user_id, 'bpoll_user_vote', true );
			if ( $bpoll_user_vote ) {
				if ( ! array_key_exists( $activity_id, $bpoll_user_vote ) ) {
					$submit = true;
					if ( isset( $bpolls_settings['hide_results'] ) ) {
						$hide_results = true;
					}
					$poll_style = '';
				}
			} else {
				$submit = true;
				if ( isset( $bpolls_settings['hide_results'] ) ) {
					$hide_results = true;
				}
				$poll_style = '';
			}
			$activity_data = bp_activity_get_specific(
				array(
					'activity_ids' => $activity_id,
					'show_hidden'  => true,
					'spam'         => 'all',
				)
			);

			$polls_act_content = stripslashes_deep( $activity_data['activities'][0]->content );
			$activity_type     = $activity_data['activities'][0]->type;

			$activity_meta = bp_activity_get_meta( $activity_id, 'bpolls_meta' );
			$total_votes   = bp_activity_get_meta( $activity_id, 'bpolls_total_votes', true );
			$poll_image    = bp_activity_get_meta( $activity_id, 'bpolls_image', true );
			$poll_closing  = false;

			if ( isset( $activity_meta['close_date'] ) && isset( $bpolls_settings['close_date'] ) && $activity_meta['close_date'] != 0 ) {
				$current_time    = new DateTime( date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ) );
				$close_date      = $activity_meta['close_date'];
				$close_date_time = new DateTime( $close_date );
				if ( $close_date_time > $current_time ) {
					$poll_closing = true;
				}
			} else {
				$poll_closing = true;
			}
					$u_meta = array();
			if ( isset( $bpoll_user_vote[ $activity_id ] ) ) {
				$u_meta = $bpoll_user_vote[ $activity_id ];
			}

			if ( isset( $activity_meta['poll_option'] ) && $activity_type == 'activity_poll' ) {
				$poll_options = ( isset( $activity_meta['poll_option'] ) ) ? $activity_meta['poll_option'] : array();

				if ( isset( $activity_meta['multiselect'] ) && 'yes' === $activity_meta['multiselect'] ) {
					$optn_typ = 'checkbox';
				} else {
					$optn_typ = 'radio';
				}

				if ( ! empty( $poll_options ) && is_array( $poll_options ) ) {
					$activity_content .= "<div class='bpolls-options-attach-shortcode-wrapper'>";
					$activity_content .= "<div class='bpolls-options-attach-container'>";
					$activity_content .= '<p>';
					$activity_content .= $polls_act_content;
					$activity_content .= '</p>';
					if ( $poll_image ) {
						$activity_content .= "<div class='bpolls-image-container'>";
						$activity_content .= "<img src='" . $poll_image . "'>";
						$activity_content .= '</div>';
					}
					$activity_content .= "<div class='bpolls-options-attach-items'><form class='bpolls-vote-submit-form' method='post' action=''>";
					foreach ( $poll_options as $key => $value ) {

						if ( isset( $activity_meta['poll_total_votes'] ) ) {
							$total_votes = $activity_meta['poll_total_votes'];
						} else {
							$total_votes = 0;
						}

						if ( isset( $activity_meta['poll_optn_votes'] ) && array_key_exists( $key, $activity_meta['poll_optn_votes'] ) ) {
							$this_optn_vote = $activity_meta['poll_optn_votes'][ $key ];
						} else {
							$this_optn_vote = 0;
						}

						if ( 0 != $total_votes ) {
							$vote_percent = round( $this_optn_vote / $total_votes * 100, 2 ) . '%';
						} elseif ( isset( $activity_meta['poll_optn_user_votes'][ $key ] ) && in_array( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) {
							$vote_percent = '100%';
						} elseif ( isset( $activity_meta['poll_optn_user_votes'][ $key ] ) && ! in_array( $user_id, $activity_meta['poll_optn_user_votes'][ $key ] ) ) {
							$vote_percent = '0%';
						} else {
							$vote_percent = __( '(no votes yet)', 'buddypress-polls' );
						}

						$bpolls_votes_txt = '(' . $this_optn_vote . '&nbsp;' . _x( 'of', 'Poll Activity', 'buddypress-polls' ) . '&nbsp;' . $total_votes . ')';

						if ( $hide_results && ! in_array( 'administrator', (array) $current_user->roles, true ) ) {
							$vote_percent     = '';
							$bpolls_votes_txt = '';
						}

						if ( in_array( $key, $u_meta, true ) ) {
							$checked = 'checked';
						} else {
							$checked = '';
						}

						$activity_content .= "<div class='bpolls-item'>";

						$output                 = '';
						$activity_votes_content = '';
						$count                  = 0;

						// $activity_content .= "<span class='bpolls-votes'>" . $bpolls_votes_txt . '</span>';
						$poll_optn_user_votes = isset( $activity_meta['poll_optn_user_votes'][ $key ] ) ? $activity_meta['poll_optn_user_votes'][ $key ] : array();

						if ( ! empty( $poll_optn_user_votes ) && isset( $bpolls_settings['poll_list_voters'] ) && ! $hide_results ) {

							$count       = count( $poll_optn_user_votes );
							$liked_count = $count - $bpolls_settings['poll_limit_voters'];
							foreach ( $poll_optn_user_votes as $userkey => $user_id ) {
								// Get Users Image.
								$img_path = bp_core_fetch_avatar(
									array(
										'item_id' => $user_id,
										'type'    => 'thumb',
										'html'    => true,
									)
								);
								// How Much User Visible.
								if ( $userkey < $bpolls_settings['poll_limit_voters'] ) {

									// Get User Image Code.
									$output .= '<a data-polls-tooltip="' . bp_core_get_user_displayname( $user_id ) . '" href="' . bp_core_get_user_domain( $user_id ) . '">' . bp_core_fetch_avatar(
										array(
											'html'    => true,
											'type'    => 'thumb',
											'item_id' => $user_id,
										)
									) . '</a>';
								}
							}

							if ( isset( $output ) ) {

								if ( $liked_count > 0 ) {
									// Display Show More.
									$output .= '<a class="bp-polls-show-voters bp-polls-view-all" data-activity-id="' . $activity_id . '" data-option-id="' . $key . '" data-polls-tooltip="' . __( 'View All', 'buddypress-polls' ) . '">+' . $liked_count . '</a>';
								}
								/* translators: %s: Vote Count */
								$activity_votes_content = '<div class="bpolls-post-voted">' . $output . '</div><span class="bp-polls-voters">' . sprintf( _n( '%s Vote', '%s Votes', $count, 'buddypress-polls' ), $count ) . '</span>';

							}
						}

						$activity_content .= '<div id="activity-id-' . $activity_id . '-' . $key . '" class="bpolls-result-votes">' . $activity_votes_content . '</div>';

						$activity_content .= '<div class="bpolls-check-radio-wrap">';
						if ( ( $submit && $poll_closing && is_user_logged_in() ) || ( $poll_revoting && $poll_closing && is_user_logged_in() ) ) {
							$activity_content .= "<input id='" . $key . "' name='bpolls_vote_optn[]' value='" . $key . "' type='" . $optn_typ . "' " . $checked . ' ' . $poll_style . '>';
						}
						$activity_content .= "<label for='" . $key . "' class='bpolls-option-lbl'>" . $value . '</label>';
						$activity_content .= '</div>';

						$activity_content .= "<div class='bpolls-item-width-wrapper'>";
						// $activity_content .= "<div class='bpolls-item-width' style='width:" . $vote_percent . ";'></div>";
						$activity_content .= "<div class='bpolls-item-width' style='width:" . $vote_percent . $polls_bg_style . ";'></div>";
						$activity_content .= "<div class='bpolls-check-radio-div'></div>";
						$activity_content .= '</div>';
						if ( $poll_options_result ) {
							$activity_content .= "<span class='bpolls-percent'>" . $vote_percent . '</span>';
						}
						$activity_content .= '</div>';
					}
					$activity_content .= "<input type='hidden' name='bpoll_activity_id' value='" . $activity_id . "'>";
					$activity_content .= "<input type='hidden' name='bpoll_multi' value='" . $activity_meta['multiselect'] . "'>";
					$activity_content .= "<input type='hidden' name='bpoll_user_id' value='" . $user_id . "'>";

					if ( ( $submit && $poll_closing && is_user_logged_in() ) || ( $poll_revoting && $poll_closing && is_user_logged_in() ) ) {
						$activity_content .= "<a class='bpolls-vote-submit' href='javascript:void(0)' " . $polls_btn_style . '>' . __( 'Submit', 'buddypress-polls' ) . '</a>';
					}
					$activity_content .= '</form></div></div></div>';

				}
			} else {
				$activity_content .= esc_html( 'This is not a poll activity', 'buddypress-polls' );
			}
		}
		$activity_content .= '</div>';
		$activity_content .= ob_get_clean();
		return $activity_content;
	}

	/**
	 * Fires when preparing to serve a REST API request.
	 *
	 * @return void
	 *
	 * @since BP Polls 4.0.0
	 */
	public function bppolls_register_user_meta() {
		register_rest_field(
			'user',
			'bp_polls_user_data',
			array(
				'get_callback' => array( $this, 'bp_polls_user_meta_callback' ),
				'schema'       => null,
			)
		);

	}

	/**
	 * Get callback fuction of rest API.
	 *
	 * @param  int    $user User ID.
	 * @param  string $field_name Field Name.
	 * @param  data   $request API Request.
	 *
	 * @since BP Polls 4.0.0
	 */
	public function bp_polls_user_meta_callback( $user, $field_name, $request ) {
		return get_user_meta( $user['id'], 'bpoll_user_vote', true );
	}


	/**
	 * Add Activotity poll option when the user add from fronted
	 *
	 * @since BP Polls 4.0.1
	 */
	public function bpolls_activity_add_user_option() {
		check_ajax_referer( 'bpolls_ajax_security', 'ajax_nonce' );
		$user_id     = get_current_user_id();
		$activity_id = isset( $_POST['activity_id'] ) ? sanitize_text_field( wp_unslash( $_POST['activity_id'] ) ) : '';
		$user_option = isset( $_POST['user_option'] ) ? sanitize_text_field( wp_unslash( $_POST['user_option'] ) ) : '';

		$activity_meta = bp_activity_get_meta( $activity_id, 'bpolls_meta' );

		$poll_key                                  = str_replace( '%', '', sanitize_title( $user_option ) );
		$activity_meta['poll_option'][ $poll_key ] = $user_option;
		/* Update Activity Poll Option */
		bp_activity_update_meta( $activity_id, 'bpolls_meta', $activity_meta );

		$user_polls_option = get_user_meta( $user_id, 'bpolls_user_options', true );
		if ( empty( $user_polls_option ) ) {
			$user_polls_option = array();
		}
		$user_polls_option[ 'activity-id-' . $activity_id . '-' . $poll_key ] = $user_option;
		/* Update  Activity Poll Option in user meta*/
		update_user_meta( $user_id, 'bpolls_user_options', $user_polls_option );

		$user_vote                       = array();
		$poll_data['bpolls_vote_optn'][] = $poll_key;
		foreach ( $activity_meta['poll_option'] as $key => $value ) {

			if ( in_array( $key, $poll_data['bpolls_vote_optn'] ) ) {
				$user_vote[] = $key;
			}
		}

		if ( $bpoll_user_vote ) {
			$bpoll_user_vote[ $activity_id ] = $user_vote;
			update_user_meta( $user_id, 'bpoll_user_vote', $bpoll_user_vote );
		} else {
			$vote[ $activity_id ] = $user_vote;
			update_user_meta( $user_id, 'bpoll_user_vote', $vote );
		}

		$bpolls_settings        = get_site_option( 'bpolls_settings' );
		$poll_options_result    = ( isset( $bpolls_settings['poll_options_result'] ) ) ? true : false;
		$poll_revoting          = ( isset( $bpolls_settings['poll_revoting'] ) ) ? true : false;
		$polls_background_color = ( isset( $bpolls_settings['polls_background_color'] ) ) ? $bpolls_settings['polls_background_color'] : '#4caf50';
		$polls_bg_style         = '';
		if ( $polls_background_color != '' ) {
			$polls_bg_style = ";background-color:$polls_background_color";
		}

		if ( isset( $activity_meta['multiselect'] ) && 'yes' === $activity_meta['multiselect'] ) {
			$optn_typ = 'checkbox';
		} else {
			$optn_typ = 'radio';
		}
		ob_start();
		?>
		<div class="bpolls-item">
			<div class='bpolls-item-inner'>
				<div id="activity-id-<?php echo $activity_id; ?>-<?php echo $poll_key; ?>" class="bpolls-result-votes"></div>
				<div class="bpolls-check-radio-wrap">
					<input id="<?php echo $poll_key; ?>" name="bpolls_vote_optn[]" value="<?php echo $poll_key; ?>" type="<?php echo $optn_typ; ?>" checked>
					<label for="option-2" class="bpolls-option-lbl"><?php echo $user_option; ?></label>
				</div>
				<div class="bpolls-item-width-wrapper">
					<div class="bpolls-item-width"></div>
					<div class="bpolls-check-radio-div"></div>
				</div>
				<span class="bpolls-percent"></span>
			</div>
			<a href="javascript:void(0);" class="bpolls-delete-user-option" data-activity-id="<?php echo $activity_id; ?>" data-option="<?php echo $poll_key; ?>" data-user-id="<?php echo $user_id; ?>"><i class="wb-icons wb-icon-x"></i></a>
		</div>
		<?php
		$add_poll_option = ob_get_clean();
		echo json_encode(
			array(
				'success'         => true,
				'add_poll_option' => $add_poll_option,
			)
		);
		wp_die();
	}

	/**
	 * Delete Activotity poll option when the user delete from fronted
	 *
	 * @since BP Polls 4.0.1
	 */
	public function bpolls_activity_delete_user_option() {
		check_ajax_referer( 'bpolls_ajax_security', 'ajax_nonce' );
		$user_id     = get_current_user_id();
		$activity_id = isset( $_POST['activity_id'] ) ? sanitize_text_field( wp_unslash( $_POST['activity_id'] ) ) : '';
		$user_option = isset( $_POST['user_option'] ) ? sanitize_text_field( wp_unslash( $_POST['user_option'] ) ) : '';

		$activity_meta = bp_activity_get_meta( $activity_id, 'bpolls_meta' );
		$poll_key      = str_replace( '%', '', sanitize_title( $user_option ) );
		/* Delete Activity Poll Option */
		unset( $activity_meta['poll_option'][ $poll_key ] );

		/*Remove Unused "poll_optn_votes" Option */
		if ( isset( $activity_meta['poll_optn_votes'][ $poll_key ] ) ) {
			unset( $activity_meta['poll_optn_votes'][ $poll_key ] );
		}
		/*Remove Unused "poll_optn_votes" Option */
		if ( isset( $activity_meta['poll_optn_user_votes'][ $poll_key ] ) ) {
			unset( $activity_meta['poll_optn_user_votes'][ $poll_key ] );
		}
		$user_vote = array();
		foreach ( $activity_meta['poll_optn_user_votes'] as $user_key => $user_value ) {
			foreach ( $user_value as $user ) {
				$user_vote[] = $user;
			}
		}

		if ( ! empty( $user_vote ) ) {
			foreach ( $activity_meta['poll_users'] as $u_key => $user_value ) {
				if ( ! in_array( $user_value, $user_vote ) ) {
					unset( $activity_meta['poll_users'][ $u_key ] );
				}
			}
		}
		/* Count Total User votes*/
		$activity_meta['poll_total_votes'] = count( $activity_meta['poll_users'] );
		bp_activity_update_meta( $activity_id, 'bpolls_meta', $activity_meta );

		$user_polls_option = get_user_meta( $user_id, 'bpolls_user_options', true );
		if ( empty( $user_polls_option ) ) {
			$user_polls_option = array();
		}
		unset( $user_polls_option[ 'activity-id-' . $activity_id . '-' . $poll_key ] );
		/* Update  Activity Poll Option in user meta*/
		update_user_meta( $user_id, 'bpolls_user_options', $user_polls_option );

		$updated_votes = $this->bpolls_ajax_calculate_votes( $activity_id );

		echo wp_json_encode( $updated_votes );
		wp_die();
	}

	public function bpolls_wp_footer() {
		if ( $this->bpolls_is_user_allowed_polls() && ( bp_is_activity_component() || bp_is_group_activity() ) ) {
			$bpolls_settings   = get_site_option( 'bpolls_settings' );
			$polls_option_lmit = ( isset( $bpolls_settings['options_limit'] ) ) ? $bpolls_settings['options_limit'] : 5;
			?>
			<div class="bpolls-icon-dialog">
				<div class="bpolls-icon-dialog-container">
					<div class="bpolls-icon-dialog-header">
						<i class="fa fa-exclamation-triangle"></i>
					</div>
					<div class="bpolls-icon-dialog-msg">
						<div class="bpolls-icon-dialog-desc">
							<div class="bpsts-icon-dialog-title">
								<strong><?php esc_html_e( 'Oops!', 'buddypress-polls' ); ?></strong>
							</div>
							<div class="bpolls-icon-dialog-content">
								<?php echo sprintf( esc_html__( 'You are not allowed to enter more than ', 'buddypress-polls' ) . esc_attr( $polls_option_lmit ) . esc_html__( ' options.', 'buddypress-polls' ) ); ?>
							</div>
						</div>
					</div>
					<ul class="bpolls-icon-dialog-buttons">
						<li>
							<a class="bpolls-icon-dialog-cancel">
								<?php esc_html_e( 'Got it!', 'buddypress-polls' ); ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<?php
		}
	}

}
