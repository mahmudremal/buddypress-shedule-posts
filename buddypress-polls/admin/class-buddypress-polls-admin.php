<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Polls
 * @subpackage Buddypress_Polls/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Buddypress_Polls
 * @subpackage Buddypress_Polls/admin
 * @author     wbcomdesigns <admin@wbcomdesigns.com>
 */
if ( ! class_exists( 'Buddypress_Polls_Admin' ) ) {

	/** Buddypress_Polls_Admin class */
	class Buddypress_Polls_Admin {

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
		 * @param      string $plugin_name       The name of this plugin.
		 * @param      string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 * @param hook $hook hook.
		 */
		public function enqueue_styles( $hook ) {
			$activity_page = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : 'bp-activity';
			if ( isset( $activity_page ) && 'bp-activity' === $activity_page ) {
				wp_enqueue_style( $this->plugin_name, BPOLLS_PLUGIN_URL . 'public/css/buddypress-polls-public.css', array(), time(), 'all' );
			}

			if ( 'wb-plugins_page_buddypress-polls' !== $hook ) {
				return;
			}

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
			$admin_page = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : 'buddypress-polls';
			if ( isset( $admin_page ) && 'buddypress-polls' === $admin_page ) {

				wp_enqueue_style( 'wp-color-picker' );

				if ( ! wp_style_is( 'polls-selectize-css', 'enqueued' ) ) {
					wp_enqueue_style( 'polls-selectize-css', plugin_dir_url( __FILE__ ) . 'css/selectize.css', array(), $this->version, 'all' );
				}

				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-polls-admin.css', array(), $this->version, 'all' );
			}

		}

		/**
		 * Hide all notices from the setting page.
		 *
		 * @return void
		 */
		public function wbcom_hide_all_admin_notices_from_setting_page() {
			$wbcom_pages_array  = array( 'wbcomplugins', 'wbcom-plugins-page', 'wbcom-support-page', 'buddypress-polls' );
			$wbcom_setting_page = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : '';

			if ( in_array( $wbcom_setting_page, $wbcom_pages_array, true ) ) {
				remove_all_actions( 'admin_notices' );
				remove_all_actions( 'all_admin_notices' );
			}
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 * @param hook $hook hook.
		 */
		public function enqueue_scripts( $hook ) {
			if ( 'wb-plugins_page_buddypress-polls' !== $hook ) {
				return;
			}

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
			$admin_page = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : 'buddypress-polls';
			if ( isset( $admin_page ) && 'buddypress-polls' === $admin_page ) {
				if ( ! wp_script_is( 'polls-selectize-js', 'enqueued' ) ) {
					wp_enqueue_script( 'polls-selectize-js', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ), $this->version, false );
				}

				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-polls-admin.js', array( 'jquery' ), $this->version, false );
			}

		}

		/**
		 * Register admin menu for plugin.
		 *
		 * @since    1.0.0
		 */
		public function bpolls_add_menu_buddypress_polls() {

			if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {

				add_menu_page( esc_html__( 'WB Plugins', 'buddypress-polls' ), esc_html__( 'WB Plugins', 'buddypress-polls' ), 'manage_options', 'wbcomplugins', array( $this, 'bpolls_buddypress_polls_settings_page' ), 'dashicons-lightbulb', 59 );
				add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'buddypress-polls' ), esc_html__( 'General', 'buddypress-polls' ), 'manage_options', 'wbcomplugins' );
			}
			add_submenu_page( 'wbcomplugins', esc_html__( 'Buddypress Polls Settings Page', 'buddypress-polls' ), esc_html__( 'BuddyPress Polls', 'buddypress-polls' ), 'manage_options', 'buddypress-polls', array( $this, 'bpolls_buddypress_polls_settings_page' ) );

		}

		/**
		 * Callable function for admin menu setting page.
		 *
		 * @since    1.0.0
		 */
		public function bpolls_buddypress_polls_settings_page() {
			$current = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'welcome';
			?>

			<div class="wrap">
				<div class="wbcom-bb-plugins-offer-wrapper">
					<div id="wb_admin_logo">
						<a href="https://wbcomdesigns.com/downloads/buddypress-community-bundle/" target="_blank">
							<img src="<?php echo esc_url( BPOLLS_PLUGIN_URL ) . 'admin/wbcom/assets/imgs/wbcom-offer-notice.png'; ?>">
						</a>
					</div>
				</div>
				<div class="wbcom-wrap buddyPress-polls-header">					
				<div class="blpro-header">
					<div class="wbcom_admin_header-wrapper">
			            <div id="wb_admin_plugin_name">
							<?php esc_html_e( 'BuddyPress Polls', 'buddypress-polls' ); ?>
							<span><?php printf( __( 'Version %s', 'buddypress-polls' ), BPOLLS_PLUGIN_VERSION ); ?></span>
						</div>
			            <?php echo do_shortcode('[wbcom_admin_setting_header]'); ?>
			        </div>
				</div>
				<div class="wbcom-admin-settings-page">
			<?php
			$bpolls_tabs = array(
				'welcome' => __( 'Welcome', 'buddypress-polls' ),
				'general' => __( 'General', 'buddypress-polls' ),
				'support' => __( 'Support', 'buddypress-polls' ),
			);

			$tab_html = '<div class="wbcom-tabs-section"><div class="nav-tab-wrapper"><div class="wb-responsive-menu"><span>' . esc_html( 'Menu' ) . '</span><input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn"><label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label></div><ul>';
			foreach ( $bpolls_tabs as $bpolls_tab => $bpolls_name ) {
				$class     = ( $bpolls_tab == $current ) ? 'nav-tab-active' : '';
				$tab_html .= '<li class="' . $bpolls_name . '"><a class="nav-tab ' . $class . '" href="admin.php?page=buddypress-polls&tab=' . $bpolls_tab . '">' . $bpolls_name . '</a></li>';
			}
			$tab_html .= '</div></ul></div>';
			echo wp_kses_post( $tab_html );
			include 'inc/bpolls-tabs-options.php';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}

		/**
		 * Function to register admin settings.
		 *
		 * @since    1.0.0
		 */
		public function bpolls_admin_register_settings() {
			if ( isset( $_POST['bpolls_settings'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				unset( $_POST['bpolls_settings']['hidden'] ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				update_site_option( 'bpolls_settings', wp_unslash( $_POST['bpolls_settings'] ) ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				wp_safe_redirect( $_POST['_wp_http_referer'] ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				exit();
			}
		}

		/**
		 * Bpolls_add_dashboard_widgets
		 *
		 * @since    1.0.0
		 */
		public function bpolls_add_dashboard_widgets() {
			wp_add_dashboard_widget(
				'bpolls_stats_dashboard_widget', // Widget slug.
				__( 'Site Polls Data', 'buddypress-polls' ), // Title.
				array( $this, 'bpolls_stats_dashboard_widget_function' ) // Display function.
			);

			wp_add_dashboard_widget(
				'bpolls_graph_dashboard_widget', // Widget slug.
				__( 'Poll Graph', 'buddypress-polls' ), // Title.
				array( $this, 'bpolls_graph_dashboard_widget_function' ) // Display function.
			);
		}

		/**
		 * Function to output the contents of polls stats widgets.
		 */
		public function bpolls_stats_dashboard_widget_function() {
			$args          = array(
				'show_hidden' => true,
				'action'      => 'activity_poll',
				'count_total' => true,
			);
			$polls_created = 0;
			if ( bp_has_activities( $args ) ) {
				global $activities_template;
				$polls_created = $activities_template->total_activity_count;
			}
			global $wpdb;

			$results = $wpdb->get_row( "SELECT * from {$wpdb->prefix}bp_activity_meta where meta_key = 'bpolls_total_votes' group by activity_id having meta_value=max(meta_value) order by meta_value desc" );

			$max_votes_act_link = '#';
			$title              = '';
			if ( isset( $results->activity_id ) ) {
				$max_votes          = $results->meta_value;
				$max_votes_act_link = bp_activity_get_permalink( $results->activity_id );
				$activity_obj       = bp_activity_get(
					array(
						'in'     => $results->activity_id,
						'max'    => 1,
						'action' => 'activity_poll',
						'type'   => 'activity_poll',
					)
				);
				$title              = $activity_content = $activity_obj['activities'][0]->content;
				$length             = strlen( $activity_content );
				if ( $length > 60 ) {
					$title = bp_create_excerpt(
						$activity_content,
						'50',
						array(
							'ending'            => '...',
							'exact'             => false,
							'html'              => true,
							'filter_shortcodes' => '',
							'strip_tags'        => false,
							'remove_links'      => false,
						)
					);
				}
			}

			$recent_poll = $wpdb->get_row( "SELECT * from {$wpdb->prefix}bp_activity where type = 'activity_poll' group by id having date_recorded=max(date_recorded) order by date_recorded desc" );

			$recent_poll_link = '#';
			if ( isset( $recent_poll->id ) ) {
				$recent_poll_link = bp_activity_get_permalink( $recent_poll->id );
				$recent_title     = $r_activity_content = $recent_poll->content;
				$length           = strlen( $r_activity_content );
				if ( $length > 60 ) {
					$recent_title = bp_create_excerpt(
						$r_activity_content,
						'50',
						array(
							'ending'            => '...',
							'exact'             => false,
							'html'              => true,
							'filter_shortcodes' => '',
							'strip_tags'        => false,
							'remove_links'      => false,
						)
					);
				}
			}
			if ( $polls_created ) {
				?>
				<div class="bpolls_stats_wrapper">
					<table class="form-table">
						<tr>
							<td><?php esc_html_e( 'Polls Created', 'buddypress-polls' ); ?></td>
							<td><?php echo esc_html( $polls_created ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Highest Voted Poll', 'buddypress-polls' ); ?></td>
							<td><a href="<?php echo esc_url( $max_votes_act_link ); ?>"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Recent Poll', 'buddypress-polls' ); ?></td>
							<td><a href="<?php echo esc_url( $recent_poll_link ); ?>"><?php echo esc_html( $recent_title ); ?><a></td>
						</tr>
					</table>
				</div>
				<?php
			} else {
				?>
				<div class="bpolls-empty-messgae"><?php esc_html_e( 'No polls created.', 'buddypress-polls' ); ?></div>
				<?php
			}
		}

		/**
		 * Bpolls_graph_dashboard_widget_function
		 */
		public function bpolls_graph_dashboard_widget_function() {

			global $wpdb;

			$results = $wpdb->get_row( "SELECT * from {$wpdb->prefix}bp_activity where type = 'activity_poll' group by id having date_recorded=max(date_recorded) order by date_recorded desc" );

			$poll_wdgt       = new BP_Poll_Activity_Graph_Widget();
			$poll_wdgt_stngs = $poll_wdgt->get_settings();
			$instance        = array(
				'title'            => __( 'Poll Graph', 'buddypress-polls' ),
				'max_activity'     => 50,
				'activity_default' => ( isset( $results->id ) ) ? $results->id : '',
			);
			the_widget( 'BP_Poll_Activity_Graph_Widget', $instance );
		}

		/**
		 * Bpolls_activity_polls_data_export
		 */
		public function bpolls_activity_polls_data_export() {

			$contributor = get_role( 'contributor' );
			$subscriber  = get_role( 'subscriber' );

			if ( ! empty( $contributor ) ) {
				$contributor->add_cap( 'upload_files' );
			}

			if ( ! empty( $subscriber ) ) {
				$subscriber->add_cap( 'upload_files' );
			}

			if ( isset( $_REQUEST['export_csv'] ) && 1 == $_REQUEST['export_csv'] && isset( $_REQUEST['buddypress_poll'] ) && 1 == $_REQUEST['buddypress_poll'] && isset( $_REQUEST['activity_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$activity_id   = isset( $_REQUEST['activity_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['activity_id'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
				$activity_meta = bp_activity_get_meta( $activity_id, 'bpolls_meta' );

				$file         = 'buddypress-activity-poll-info.csv';
				$uploads_path = ABSPATH . 'wp-content/uploads/';
				$fp           = fopen( $uploads_path . $file, 'a' ) or die( "Error Couldn't open $file for writing!" ); // phpcs:ignore WordPress.Security.EscapeOutput

				$csv_header = array( 'User ID', 'UserName' );
				foreach ( $activity_meta['poll_option'] as $key => $value ) {
					$csv_header[ $key ] = $value;
				}
				fputs( $fp, $bom = ( chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) ) );
				fputcsv( $fp, $csv_header );
				$activity_meta = bp_activity_get_meta( $activity_id, 'bpolls_meta' );
				$users         = $activity_meta['poll_users'];
				$args          = array(
					'include' => $users,
				);

				$users       = new WP_User_Query( $args );
				$users_found = $users->get_results();
				foreach ( $users_found as $user ) {
					$results['users'][] = $user->ID;
					$user_id            = $user->ID;
					$user_display_name  = $user->user_login;

					$user_polls_data         = get_user_meta( $user_id, 'bpoll_user_vote', true );
					$user_activity_poll_data = isset( $user_polls_data[ $activity_id ] ) ? $user_polls_data[ $activity_id ] : array();

					$fields = array( $user_id, $user_display_name );

					foreach ( $activity_meta['poll_option'] as $key => $value ) {
						if ( in_array( $key, $user_activity_poll_data, true ) ) {
							$fields[] = 'true';
						} else {
							$fields[] = '-';
						}
					}
					$fields = array_map( 'utf8_decode', $fields );
					fputcsv( $fp, $fields );

				}

				fclose( $fp );

				ignore_user_abort( true );
				set_time_limit( 0 ); // disable the time limit for this script.

				// change the path to fit your websites document structure.
				$dl_file = preg_replace( '([^\w\s\d\-_~,;:\[\]\(\].]|[\.]{2,})', '', $file ); // simple file name validation.
				$dl_file = filter_var( $dl_file, FILTER_SANITIZE_URL ); // Remove (more) invalid characters.

				$uploads_path = ABSPATH . 'wp-content/uploads/'; // change the path to fit your websites document structure.
				$full_path    = $uploads_path . $dl_file;

				if ( $fd = fopen( $full_path, 'r' ) ) {
					$path_parts = pathinfo( $full_path );

					header( 'Content-type: application/csv' );
					header( 'Content-Disposition: attachment; filename="' . $path_parts['basename'] . '"' ); // use.
					header( 'Cache-control: private' ); // use this to open files directly.
					header( 'Content-Transfer-Encoding: binary' );
					while ( ! feof( $fd ) ) {
						$buffer = fread( $fd, 2048 );
						echo $buffer; // phpcs:ignore WordPress.Security.EscapeOutput
					}
				}
				fclose( $fd );
				unlink( $uploads_path . $file );
				exit;
			}
		}
	}


}
