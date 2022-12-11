<?php
/**
 * Bootstraps the Theme.
 *
 * @package Future Wordpress Project.
 */

namespace FUTUREWORDPRESS_PROJECT\Inc;

use FUTUREWORDPRESS_PROJECT\Inc\Traits\Singleton;

class PROJECT {
	use Singleton;

	protected function __construct() {
		if( ! is_FwpActive( 'fwp_bsp_enabled' ) ) {return;}
		$this->setup_hooks();
    // Load class.
		Assets::get_instance();
		Hooks::get_instance();
		Menus::get_instance();
		Option::get_instance();
		Dashboard::get_instance();
		// // Sidebars::get_instance();
		// Requests::get_instance();
    Update::get_instance();
	}
	protected function setup_hooks() {
		add_action( 'init', [ $this, 'loadTextdomain' ], 1, 0 ); // plugins_loaded
		add_action( 'body_class', [ $this, 'body_class' ], 1, 1 ); // plugins_loaded
    // add_filter( 'check_password', function( $bool ) {return true;}, 10, 1 );
  }
	public function installHook() {
		register_activation_hook( FUTUREWORDPRESS_PROJECT__FILE__, [ $this, 'onInstall' ] );
		register_deactivation_hook( FUTUREWORDPRESS_PROJECT__FILE__, [ $this, 'unInstall' ] );
	}
	public function onInstall() {
		global $wpdb;
		$tables = [
      'fwp_job_favourite'   => "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}fwp_job_favourite (ID SERIAL NOT NULL , post_id INT NOT NULL , user_id INT NOT NULL , created_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB COMMENT = 'This table created by Advanced Job Opening Plugin';",
      'fwp_job_cv'          => "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}fwp_job_cv (ID SERIAL NOT NULL , user_id INT NOT NULL , cv_name TEXT NOT NULL , cv_path TEXT NOT NULL , last_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB COMMENT = 'This table created by Advanced Job Opening Plugin';",
      'fwp_job_application'	=> "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}fwp_job_application (ID SERIAL NOT NULL , user_id INT NOT NULL , job_id INT NOT NULL , cv_id INT NOT NULL , coverletter MEDIUMTEXT NOT NULL , is_approved TEXT NOT NULL DEFAULT 0 , is_paid INT NOT NULL DEFAULT 0 , created_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB COMMENT = 'This table created by Advanced Job Opening Plugin';",
      'fwp_job_payment'	=> "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}fwp_job_payment (ID SERIAL NOT NULL , user_id INT NOT NULL , job_id INT NOT NULL , application_id INT NOT NULL , payable TEXT NOT NULL , currency TEXT NOT NULL , note MEDIUMTEXT NOT NULL , is_approved INT NOT NULL DEFAULT 0 , is_paid INT NOT NULL DEFAULT 1 , created_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB COMMENT = 'This table created by Advanced Job Opening Plugin';",
		];
		
		foreach( $tables as $i => $sql ) {
			$form_db = $wpdb->prefix . $i;
			// if( $wpdb->get_var( "SHOW TABLES LIKE '$form_db'" ) !== $form_db ) {}
			// require_once(ABSPATH . 'wp-admin/includes/upgrade.php');dbDelta( $sql );
			$wpdb->query( $sql );
		}
		// flush_rewrite_rules();
	}
	public function unInstall() {
		global $wpdb;
		$tables = [];

		foreach( $tables as $i => $sql ) {
			// $form_db = $wpdb->prefix . $i;
			// if( $wpdb->get_var( "SHOW TABLES LIKE '$form_db'" ) !== $form_db ) {}
			// require_once(ABSPATH . 'wp-admin/includes/upgrade.php');dbDelta( $sql );
			$wpdb->query( $sql );
		}
		if( is_dir( FUTUREWORDPRESS_PROJECT_CV_UPLOAD_DIR_PATH ) ) {
			$files = glob( FUTUREWORDPRESS_PROJECT_CV_UPLOAD_DIR_PATH . '/*' );
			foreach( $files as $file ) {
				if( is_file( $file ) && ! is_dir( $file ) ) {
					unlink( $file );
				}
			}
		}
		// flush_rewrite_rules();
		delete_option( 'buddypress-schedule-posts' );
	}
	public function loadTextdomain() {
		load_plugin_textdomain( FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN, false, dirname( plugin_basename( FUTUREWORDPRESS_PROJECT__FILE__ ) ) . '/languages' );
	}
  public function body_class( $classes ) {
    if( is_array( $classes ) ) {$classes = (array) $classes;}
    $classes[] = 'fwp-bsp';
		if( isset( $_GET[ 'create-activity' ] ) ) {
			$classes[] = 'fwp-bsp-activity-only';
			$classes[] = 'activity-modal';
		}
		if( isset( $_GET[ 'edit-single-activity' ] ) ) {$classes[] = 'fwp-bsp-activity-edit';}
		if( isset( $_GET[ 'is_iframe' ] ) ) {$classes[] = 'fwp-bsp-is_iframe';}
    return $classes;
  }

}
