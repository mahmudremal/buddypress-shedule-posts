<?php
/**
 * Loadmore Single Posts
 *
 * @package FutureWordPress BSP
 */

namespace FUTUREWORDPRESS_PROJECT\Inc;

use FUTUREWORDPRESS_PROJECT\Inc\Traits\Singleton;
// use \WP_Query;

class Requests {

	use Singleton;

	protected function __construct() {
		$this->setup_hooks();
	}

	protected function setup_hooks() {
	}
  public function sendMail() {
    if ( ! isset( $_POST[ 'fwp-contact-company-form' ] ) || ! isset( $_POST['fwp-contact-company-form-action'] ) || ! wp_verify_nonce( $_POST['fwp-contact-company-form-action'], 'fwp-contact-company-form-action' ) ) {
      wp_die( __( 'Sorry, your nonce did not verify.', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ), __( 'Authetication error', FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ) );
    } else {
      // wp_die( print_r( $_POST ) );
      $request = $_POST[ 'fwp-contact-company-form' ];
      $request = wp_parse_args( $request, [
        'id' => 0, 'to' => '', 'name' => '', 'email' => '', 'subject' => '', 'message' => ''
      ] );
      // can be verify by "id" as company ID Author ID
      $to = $request[ 'to' ];
      $subject = $request[ 'subject' ];
      $body = $request[ 'message' ];
      $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
      $headers[] = 'Reply-To: ' . $request[ 'name' ] . ' <' . $request[ 'email' ] . '>';

      wp_mail( $to, $subject, $body, $headers );
      // $msg = [ 'status' => 'success', 'message' => __( get_fwp_option( 'msg_profile_edit_success_txt', 'Changes saved' ), FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ) ];
      // set_transient( 'status_successed_message-' . get_current_user_id(), $msg, 300 );
      wp_safe_redirect( wp_get_referer() );
    }
  }
}
