<?php
/**
 * The plugin bootstrap file.
 *
 * @link              http://wbcomdesigns.com
 * @since             1.0.0
 * @package           Bp_Post_From_Anywhere
 * @wordpress-plugin
 * Plugin Name:       Wbcom Designs - BuddyPress Post from Anywhere
 * Plugin URI:        https://wbcomdesigns.com/downloads
 * Description:       BuddyPress Post from Anywhere allows you to post activities for BuddyPress or BuddyBoss Platform using shortcodes from any page or any CPT post using shortcode [bppfa_postform].
 * Version:           1.5.0
 * Author:            Wbcom Designs
 * Author URI:        https://wbcomdesigns.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bp-post-from-anywhere
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'BP_POST_FROM_ANYWHERE_PLUGIN_URL' ) ) {
	define( 'BP_POST_FROM_ANYWHERE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Checking plugin's required plugins.
 *
 * @since    1.0.0
 * @author   wbcomdesigns
 * @access   public
 */
function bppfa_plugin_init() {
	// If BuddyPress is NOT active.
	if ( current_user_can( 'activate_plugins' ) && ! class_exists( 'BuddyPress' ) ) {
		add_action( 'admin_init', 'bppfa_plugin_deactivate' );
		add_action( 'admin_notices', 'bppfa_plugin_admin_notice' );

		/**
		 * Deactivate the BuddyPress Post From Anywhere Plugin.
		 *
		 * @since    1.0.0
		 * @author   wbcomdesigns
		 * @access   public
		 */
		function bppfa_plugin_deactivate() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}

		/**
		 * Throw an Alert to tell the Admin why it didn't activate.
		 *
		 * @since    1.0.0
		 * @author   wbcomdesigns
		 * @access   public
		 */
		function bppfa_plugin_admin_notice() {
			$bpsh_plugin       = __( 'BuddyPress Post From Anywhere', 'bp-post-from-anywhere' );
			$buddypress_plugin = __( 'BuddyPress', 'bp-post-from-anywhere' );
			echo '<div class="error"><p>'
			/* translators: %1$s: BuddyPress Post From Anywhere, %2$s:BuddyPress */
			. sprintf( esc_html__( '%1$s requires %2$s to function correctly. Please activate %2$s before activating %1$s. For now, the plugin has been deactivated.', 'bp-post-from-anywhere' ), '<strong>' . esc_html( $bpsh_plugin ) . '</strong>', '<strong>' . esc_html( $buddypress_plugin ) . '</strong>' )
			. '</p></div>';
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	} else {
		if ( ! defined( 'BPPFA_PLUGIN_BASENAME' ) ) {
			define( 'BPPFA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		}
		/**
		 * The core plugin class that is used to define internationalization,
		 * admin-specific hooks, and public-facing site hooks.
		 */
		require plugin_dir_path( __FILE__ ) . 'includes/class-bp-post-from-anywhere.php';
		run_bp_post_from_anywhere();
	}
}

add_action( 'plugins_loaded', 'bppfa_plugin_init' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bp_post_from_anywhere() {
	$plugin = new Bp_Post_From_Anywhere();
	$plugin->run();
}
