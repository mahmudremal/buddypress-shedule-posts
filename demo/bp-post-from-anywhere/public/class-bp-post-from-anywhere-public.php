<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Bp_Post_From_Anywhere
 * @subpackage Bp_Post_From_Anywhere/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bp_Post_From_Anywhere
 * @subpackage Bp_Post_From_Anywhere/public
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Bp_Post_From_Anywhere_Public {

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
		 * defined in Bp_Post_From_Anywhere_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bp_Post_From_Anywhere_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'bbpfa-ntification-style', plugin_dir_url( __FILE__ ) . 'css/jquery.notify.css', $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bp-post-from-anywhere-public.css', array(), $this->version, 'all' );
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
		 * defined in Bp_Post_From_Anywhere_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bp_Post_From_Anywhere_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'bbpfa-ntification-script', plugin_dir_url( __FILE__ ) . 'js/jquery.notify.min.js' ); //phpcs:ignore
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bp-post-from-anywhere-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'bppfa_ajax_obj',
			array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'bppfa_url' => BP_POST_FROM_ANYWHERE_PLUGIN_URL,
			)
		);
	}

	/**
	 * Display bp activity form.
	 *
	 * @since    1.0.0
	 * @author   wbcomdesigns
	 * @access   public
	 */
	public function bppfa_activity_form() {
		$bppfa_options = get_option( 'bppfa_options' );
		if ( is_user_logged_in() && get_current_user_id() != bp_displayed_user_id() && bp_is_activity_component() && 'bppfa_self' != $bppfa_options && 'bppfa_member' == $bppfa_options ) { //phpcs:ignore
			bp_get_template_part( 'activity/post-form' );
		}

		global $bp;
		$is_friend = friends_check_friendship( $bp->loggedin_user->id, $bp->displayed_user->id );

		if ( $is_friend && 'bppfa_member' != $bppfa_options && 'bppfa_self' != $bppfa_options && 'bppfa_friends' == $bppfa_options ) { //phpcs:ignore
			bp_get_template_part( 'activity/post-form' );
		}
	}
}
