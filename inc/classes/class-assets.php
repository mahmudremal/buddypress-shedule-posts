<?php
/**
 * Enqueue theme assets
 *
 * @package FutureWordPress BSP
 */

namespace FUTUREWORDPRESS_PROJECT\Inc;

use FUTUREWORDPRESS_PROJECT\Inc\Traits\Singleton;

class Assets {
	use Singleton;

	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
	}
	public function register_styles() {
		// Register styles.
		wp_register_style( 'bootstrap-css', FUTUREWORDPRESS_PROJECT_BUILD_LIB_URI . '/css/bootstrap.min.css', [], false, 'all' );
		wp_register_style( 'slick-css', FUTUREWORDPRESS_PROJECT_BUILD_LIB_URI . '/css/slick.css', [], false, 'all' );
		wp_register_style( 'slick-theme-css', FUTUREWORDPRESS_PROJECT_BUILD_LIB_URI . '/css/slick-theme.css', ['slick-css'], false, 'all' );
		wp_register_style( 'fwp-bsp-frontend', FUTUREWORDPRESS_PROJECT_BUILD_CSS_URI . '/frontend.css', ['bootstrap-css'], $this->filemtime( FUTUREWORDPRESS_PROJECT_BUILD_CSS_DIR_PATH . '/frontend.css' ), 'all' );
		wp_register_style( 'frontend-base', FUTUREWORDPRESS_PROJECT_BUILD_LIB_URI . '/css/frontend-base.css', ['bootstrap-css'], $this->filemtime( FUTUREWORDPRESS_PROJECT_BUILD_LIB_PATH . '/css/frontend-base.css' ), 'all' );
		wp_register_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css', [], false, 'all' );
		wp_register_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css', [], false, 'all' );

		// Enqueue Styles.
		// wp_enqueue_style( 'bootstrap-css' );
		// wp_enqueue_style( 'slick-css' );
		// wp_enqueue_style( 'slick-theme-css' );
		wp_enqueue_style( 'fwp-bsp-frontend' );
		// wp_enqueue_style( 'font-awesome' );
		// wp_enqueue_style( 'frontend-base' );

	}
	public function register_scripts() {
		// Register scripts.
		wp_register_script( 'slick-js', FUTUREWORDPRESS_PROJECT_BUILD_LIB_URI . '/js/slick.min.js', ['jquery'], false, true );
		wp_register_script( 'fwp-bsp-frontend', FUTUREWORDPRESS_PROJECT_BUILD_JS_URI . '/frontend.js', ['jquery', 'slick-js'], $this->filemtime( FUTUREWORDPRESS_PROJECT_BUILD_JS_DIR_PATH . '/frontend.js' ), true );
		wp_register_script( 'single-js', FUTUREWORDPRESS_PROJECT_BUILD_JS_URI . '/single.js', ['jquery', 'slick-js'], $this->filemtime( FUTUREWORDPRESS_PROJECT_BUILD_JS_DIR_PATH . '/single.js' ), true );
		wp_register_script( 'author-js', FUTUREWORDPRESS_PROJECT_BUILD_JS_URI . '/author.js', ['jquery'], $this->filemtime( FUTUREWORDPRESS_PROJECT_BUILD_JS_DIR_PATH . '/author.js' ), true );
		wp_register_script( 'bootstrap-js', FUTUREWORDPRESS_PROJECT_BUILD_LIB_URI . '/js/bootstrap.min.js', ['jquery'], false, true );
		wp_register_script( 'tailwindcss', 'https://cdn.tailwindcss.com', [], false, true );
		wp_register_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js', [], false, true );
		wp_register_script( 'ckeditor', 'https://cdn.ckeditor.com/ckeditor5/35.2.1/classic/ckeditor.js', [], false, true );
		// wp_register_script( 'data-table', 'https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js', [ 'jquery' ], false, true );

		// Enqueue Scripts.
		wp_enqueue_script( 'fwp-bsp-frontend' );
		// wp_enqueue_script( 'bootstrap-js' );
		// wp_enqueue_script( 'slick-js' );

		// If single post page
		// if ( is_single() ) {
		// 	wp_enqueue_script( 'single-js' );
		// }

		// If author archive page
		// if ( is_author() ) {
		// 	wp_enqueue_script( 'author-js' );
		// }

		// get_fwp_option( 'candidate_cv_delete_confirm_txt', 'Are you sure you want to delete Your CV? This can\'t be undo.' ),
		wp_localize_script( 'fwp-bsp-frontend', 'siteConfig', [
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'fwp_bsp_ajax_post_nonce' ),
      'iSheduled' => is_FwpActive( 'fwp_bsp_enabled' ),
      'defaulTime' => get_fwp_option( 'fwp_bsp_defaultime', '12:00:00 AM' ),
      'hideSubmit' => is_FwpActive( 'fwp_bsp_hidepostnow' ),
		] );
	}
	public function register_admin_styles() {
    // if( ! is_admin() ) {return;}

		wp_enqueue_style( 'buddypress-shedule-post-admin', FUTUREWORDPRESS_PROJECT_BUILD_CSS_URI . '/backend.css', [], $this->filemtime( FUTUREWORDPRESS_PROJECT_BUILD_CSS_DIR_PATH . '/backend.css' ), 'all' );
	}
  protected function filemtime( $file ) {
    return ( file_exists( $file ) && ! is_dir( $file ) ) ? filemtime( $file ) : rand( 0, 9999999 );
  }

}
