<?php
/**
 * Activity Edit Popup template.
 *
 * @package FutureWordPress BSP
 */
global $wp;$standardForm = ( isset( $standardForm ) && $standardForm ) ? true : false;
$standardForm = false;
?>
<div class="reign-module reign-window-popup reign-close-popup" id="fwp-sheduled-activity-edit-form" tabindex="-1" role="dialog" data-id="fwp-sheduled-activity-edit-form">
  <div class="modal-dialog window-popup fwp-sheduled-activity-edit-form" role="document">
    <div class="modal-content">
      <div class="close icon-close reign-close-popup" id="reign-close-popup" data-id="fwp-sheduled-activity-edit-form">
        <i class="far fa-times" id="reign-close-popup" data-id="fwp-sheduled-activity-edit-form"></i>
      </div>
      <div class="modal-body no-padding">
        <div class="registration-login-form mb-0 formContainer selected-forms-both">
          
          <div class="modal-content">
            <div class="modal-pane activity-update-form">
              <div class="title h6"><?php esc_html_e( 'Edit Activity Schedule', 'fwp-bsp' ); ?></div>
              <div class="content is_active" data-tab="schedule">
                <?php
                if( $standardForm ) {
                  // locate_template( array( 'buddypress/activity/post-form.php' ), true );
                  // echo do_shortcode( '[bppfa_postform]' );
                } else { ?>
                  <form class="fwp-bsf-activity-redit-post-form" method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <input type="hidden" name="action" value="fwp-bsp-activity-re-edit">
                    <?php wp_nonce_field( 'fwp-bsp-activity-re-edit', 'activity-re-edit', true, true ); ?>
                    <!-- <input class="simple-input" type="hidden" name="redirect_to" value="<?php echo esc_url( site_url( $wp->request ) ); ?>" /> -->
                    <input id="fwp-bsp-activity-id" name="activity[id]" class="form-control simple-input" type="hidden" value="" />
                    <input id="fwp-bsp-activity-timezone" name="activity[timezone]" class="form-control simple-input" type="hidden" value="" />
                    <ul class="reign-sign-form-messages"></ul>
                    <div class="row">
                      <div class="col">
                        <!-- <div class="form-group label-floating">
                          <label class="control-label"><?php esc_html_e( 'Activity Content', 'fwp-bsp' ); ?></label>
                          <textarea data-id="whats-new" id="fwp-bsp-activity-content" name="activity[content]" class="form-control simple-input" cols="30" rows="10"></textarea>
                        </div> -->
                        <div class="form-group label-floating password-eye-wrap">
                          <label class="control-label"><?php esc_html_e( 'Schedule', 'fwp-bsp' ); ?></label>
                          <input id="fwp-bsp-activity-schedule" class="form-control simple-input" name="activity[schedule]" type="datetime-local" />
                        </div>
                        <button type="submit" class="btn full-width registration-login-submit">
                          <span><?php esc_html_e( 'Save', 'fwp-bsp' ); ?></span>
                          <span class="icon-loader"></span>
                        </button>
                        <!-- <button type="reset" class="btn full-width bg-warning">
                          <span><?php esc_html_e( 'Reset', 'fwp-bsp' ); ?></span>
                          <span class="icon-loader"></span>
                        </button> -->
                      </div>
                    </div>
                  </form>
                <?php } ?>
              </div>
              <!-- <div class="content" data-tab="create">
                <iframe src="<?php echo esc_url( trailingslashit( bp_loggedin_user_domain() . '/activity/' ) . '?create-activity=true&is_iframe=true' ); ?>" frameborder="0"></iframe>
              </div>
              <div class="content" data-tab="edit">
                <iframe src="<?php echo esc_url( site_url( '/wp-content/uploads/2022/11/logo_twippie_5.png' ) ); ?>" frameborder="0"></iframe>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>