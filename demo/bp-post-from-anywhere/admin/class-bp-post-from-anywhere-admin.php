<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Bp_Post_From_Anywhere
 * @subpackage Bp_Post_From_Anywhere/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bp_Post_From_Anywhere
 * @subpackage Bp_Post_From_Anywhere/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Bp_Post_From_Anywhere_Admin {

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
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bp-post-from-anywhere-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bp-post-from-anywhere-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Added Shortcode Content.
	 *
	 * @param  array  $atts Attributes.
	 * @param  string $content Content.
	 */
	public function bppfa_postform_shortcode( $atts, $content = null ) {

		ob_start();

		echo '<div id="bppfa-buddypress">';
		if ( function_exists( 'bp_is_active' ) ) {

			if ( is_user_logged_in() ) {
				include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/templates/bp-post-from-activity.php';
			}
		}
		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Add activity into buddypress options tab
	 *
	 * @since    1.0.0
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function bppfa_add_activity_settings() {
		if ( is_plugin_active( 'bp-post-from-anywhere-master/bp-post-from-anywhere.php' ) && bp_is_active( 'activity' ) ) {

			add_settings_field(
				'bppfa_options',
				__( 'Enable What’s new', 'bp-post-from-anywhere' ),
				array( $this, 'bppfa_add_activity_settings_fields' ),
				'buddypress',
				'bp_activity'
			);
			register_setting( 'buddypress', 'bppfa_options' );
		}
	}

	/**
	 * Add activity settings fields
	 *
	 * @since    1.0.0
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function bppfa_add_activity_settings_fields() {
		$bppfa_options = get_option( 'bppfa_options' );
		if ( empty( $bppfa_options ) ) {
			update_option( 'bppfa_options', 'bppfa_self' );
		}
		?>
		<input id="bp-plugin-option-name" name="bppfa_options" type="radio" value="bppfa_self" <?php checked( $bppfa_options, 'bppfa_self' ); ?> checked />
		<label for="bp-plugin-option-name">
			<?php esc_html_e( 'Self activity', 'bp-post-from-anywhere' ); ?>
		</label>

		<input id="bp-plugin-option-name" name="bppfa_options" type="radio" value="bppfa_friends" <?php checked( $bppfa_options, 'bppfa_friends' ); ?> />
		<label for="bp-plugin-option-name">
			<?php esc_html_e( 'Friends activity', 'bp-post-from-anywhere' ); ?>
		</label>

		<input id="bp-plugin-option-name" name="bppfa_options" type="radio" value="bppfa_member" <?php checked( $bppfa_options, 'bppfa_member' ); ?> />
		<label for="bp-plugin-option-name">
			<?php esc_html_e( 'Members activity', 'bp-post-from-anywhere' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'Self', 'bp-post-from-anywhere' ); ?></p>
		<?php
	}
}
