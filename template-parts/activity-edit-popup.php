<?php
/**
 * Activity Edit Popup template.
 *
 * @package FutureWordPress BSP
 */
global $wp;
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
            <div class="modal-pane">
              <div class="title h6"><?php esc_html_e( 'Edit Activity post', 'domain' ); ?></div>

              <!-- <div class="content">
                <div id="budd ypress">
                  <div>
                    <div class="activity-comment activity-update-form" id="bp-nouveau-activity-form">
                      <?php // locate_template( array( 'buddypress/activity/post-form.php' ), true ); ?>
                    </div>
                  </div>
                </div>
              </div> -->

              <div class="content">
                <form class="fwp-bsf-activity-redit-post-form" method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                  <input type="hidden" name="action" value="fwp-bsp-activity-re-edit">
                  <?php wp_nonce_field( 'fwp-bsp-activity-re-edit', 'activity-re-edit', true, true ); ?>
                  <!-- <input class="simple-input" type="hidden" name="redirect_to" value="<?php echo esc_url( site_url( $wp->request ) ); ?>" /> -->
                  <input id="fwp-bsp-activity-id" name="activity[id]" class="form-control simple-input" type="hidden" value="" />
                  <ul class="reign-sign-form-messages"></ul>
                  <div class="row">
                    <div class="col">
                      <div class="form-group label-floating">
                        <label class="control-label"><?php esc_html_e( 'Activity Content', 'domain' ); ?></label>
                        <textarea id="fwp-bsp-activity-content" name="activity[content]" class="form-control simple-input" cols="30" rows="10"></textarea>
                      </div>
                      <div class="form-group label-floating password-eye-wrap">
                        <label class="control-label"><?php esc_html_e( 'Schedule', 'domain' ); ?></label>
                        <input id="fwp-bsp-activity-schedule" class="form-control simple-input" name="activity[schedule]" type="datetime-local" />
                      </div>
                      <button type="submit" class="btn full-width registration-login-submit">
                        <span><?php esc_html_e( 'Save', 'domain' ); ?></span>
                        <span class="icon-loader"></span>
                      </button>
                      <button type="reset" class="btn full-width bg-warning">
                        <span><?php esc_html_e( 'Reset', 'domain' ); ?></span>
                        <span class="icon-loader"></span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>