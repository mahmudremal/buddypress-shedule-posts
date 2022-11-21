<?php
/**
 * This file is used for rendering and saving plugin general settings.
 *
 * @package    Buddypress_Polls
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
	$bpolls_settings = get_site_option( 'bpolls_settings' );
} else {
	$bpolls_settings = get_site_option( 'bpolls_settings' );
}
if ( ! isset( $bpolls_settings['limit_poll_activity'] ) ) {
	$bpolls_settings['limit_poll_activity'] = 'no';
}

global $wp_roles;
?>
<div class="wbcom-tab-content">
<div class="wbcom-wrapper-admin">
<div class="wbcom-admin-title-section"><h3><?php esc_html_e( 'Polls General Settings', 'buddypress-polls' ); ?></h3>
</div>
<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
<form method="post" action="admin.php?action=update_network_options">
	<div class="wbcom-admin-option-wrap-bp-poll">
	<input name='bpolls_settings[hidden]' type='hidden' value=""/>
	<?php
	settings_fields( 'buddypress_polls_general' );
	do_settings_sections( 'buddypress_polls_general' );
	?>
	<div class="form-table polls-general-options">
		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="blogname"><?php esc_html_e( 'Multi select polls', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled/Disabled: users can vote for multiple options in each poll.', 'buddypress-polls' ); ?>
				</p>
			</div>
			<div class="wbcom-settings-section-options">
				<label class="wb-switch">
					<input name='bpolls_settings[multiselect]' type='checkbox' value='yes' <?php ( isset( $bpolls_settings['multiselect'] ) ) ? checked( $bpolls_settings['multiselect'], 'yes' ) : ''; ?>/>
					<div class="wb-slider wb-round"></div>
				</label>
			</div>
		</div>
		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="blogname"><?php esc_html_e( 'Allow members to add poll options.', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: It will allow other members to all poll options to a poll which are created by others like Facebook.', 'buddypress-polls' ); ?>
				</p>
			</div>
			<div class="wbcom-settings-section-options">
				<label class="wb-switch">
					<input name='bpolls_settings[user_additional_option]' type='checkbox' value='yes' <?php ( isset( $bpolls_settings['user_additional_option'] ) ) ? checked( $bpolls_settings['user_additional_option'], 'yes' ) : ''; ?>/>
					<div class="wb-slider wb-round"></div>
				</label>												
			</div>		
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="blogname"><?php esc_html_e( 'Hide results', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: results are hidden from users who have not voted yet.', 'buddypress-polls' ); ?> <?php esc_html_e( '/ Disabled: users can see poll results before voting.', 'buddypress-polls' ); ?>
				</p>
			</div>
			<div class="wbcom-settings-section-options">
			<label class="wb-switch">
				<input name='bpolls_settings[hide_results]' type='checkbox' value='yes' <?php ( isset( $bpolls_settings['hide_results'] ) ) ? checked( $bpolls_settings['hide_results'], 'yes' ) : ''; ?>/>
				<div class="wb-slider wb-round"></div>
			</label>			
			</div>
			</div>

			<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="blogname"><?php esc_html_e( 'Poll closing date & time', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: users can set poll closing date and time.', 'buddypress-polls' ); ?> <?php esc_html_e( '/ Disabled: polls will always remain open for voting', 'buddypress-polls' ); ?>
			</p>
			</div>
			<div class="wbcom-settings-section-options">
			<label class="wb-switch">
				<input name='bpolls_settings[close_date]' type='checkbox' value='yes' <?php ( isset( $bpolls_settings['close_date'] ) ) ? checked( $bpolls_settings['close_date'], 'yes' ) : ''; ?>/>
				<div class="wb-slider wb-round"></div>
			</label>			
			</div>		
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="blogname"><?php esc_html_e( 'Enable image attachment', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: users can set a image attachment with the poll.', 'buddypress-polls' ); ?>
			</p>
			</div>
			<div class="wbcom-settings-section-options">
			<label class="wb-switch">
				<input name='bpolls_settings[enable_image]' type='checkbox' value='yes' <?php ( isset( $bpolls_settings['enable_image'] ) ) ? checked( $bpolls_settings['enable_image'], 'yes' ) : ''; ?>/>
				<div class="wb-slider wb-round"></div>
			</label>			
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="options_limit"><?php esc_html_e( 'Poll Options Limit', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Set the limit of the poll options.', 'buddypress-polls' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
			<input id="options_limit" name='bpolls_settings[options_limit]' type='number' value='<?php echo ( isset( $bpolls_settings['options_limit'] ) ) ? esc_attr( $bpolls_settings['options_limit'] ) : '5'; ?>'/>			
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="poll_options_result"><?php esc_html_e( 'Enable Poll Result', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: Allow users to view poll results.', 'buddypress-polls' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
			<label class="wb-switch">
				<input id="poll_options_result" name='bpolls_settings[poll_options_result]' type='checkbox' value='yes' <?php ( isset( $bpolls_settings['poll_options_result'] ) ) ? checked( $bpolls_settings['poll_options_result'], 'yes' ) : ''; ?>/>
				<div class="wb-slider wb-round"></div>
			</label>			
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="poll_revoting"><?php esc_html_e( 'Enable Revoting', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: Allow users to change their votes after voting.', 'buddypress-polls' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
			<label class="wb-switch">
				<input id="poll_revoting" name='bpolls_settings[poll_revoting]' type='checkbox' value='yes' <?php ( isset( $bpolls_settings['poll_revoting'] ) ) ? checked( $bpolls_settings['poll_revoting'], 'yes' ) : ''; ?>/>
				<div class="wb-slider wb-round"></div>
			</label>			
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="poll_list_voters"><?php esc_html_e( 'Show Voters List', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: Allow users to view list of voters.', 'buddypress-polls' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
			<label class="wb-switch">
				<input id="poll_list_voters" name='bpolls_settings[poll_list_voters]' type='checkbox' value='yes' <?php ( isset( $bpolls_settings['poll_list_voters'] ) ) ? checked( $bpolls_settings['poll_list_voters'], 'yes' ) : ''; ?>/>
				<div class="wb-slider wb-round"></div>
			</label>			
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="poll_limit_voters"><?php esc_html_e( 'Max Voters Number', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Max voters to show.', 'buddypress-polls' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
			<input id="poll_limit_voters" name='bpolls_settings[poll_limit_voters]' type='number' value='<?php echo ( isset( $bpolls_settings['poll_limit_voters'] ) ) ? esc_attr( $bpolls_settings['poll_limit_voters'] ) : '3'; ?>'/>
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="blogname"><?php esc_html_e( 'Color Scheme', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Allow users to set color scheme for progress bar and submit button.', 'buddypress-polls' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
			<label for>
				<input id="polls_background_color" name='bpolls_settings[polls_background_color]' type='text' value='<?php echo isset( $bpolls_settings['polls_background_color'] ) ? esc_attr( $bpolls_settings['polls_background_color'] ) : '#4caf50'; ?>' />
			</label>			
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="blogname"><?php esc_html_e( 'Enable after poll message', 'buddypress-polls' ); ?></label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: users can add thank you message with the poll.', 'buddypress-polls' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
			<label class="wb-switch">
				<input name='bpolls_settings[enable_thank_you_message]' type='checkbox' value='yes' <?php ( isset( $bpolls_settings['enable_thank_you_message'] ) ) ? checked( $bpolls_settings['enable_thank_you_message'], 'yes' ) : ''; ?>/>
				<div class="wb-slider wb-round"></div>
			</label>			
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label><?php esc_html_e( 'Limit Poll Activities', 'buddypress-polls' ); ?></label>
				<p class="description">
					<?php esc_html_e( 'Limit by user role or member type to publish poll type activities', 'buddypress-polls' ); ?>
				</p>
			</div>
			<div class="wbcom-settings-section-options">
				<label>
				<input name='bpolls_settings[limit_poll_activity]' type='radio' value='no' <?php ( isset( $bpolls_settings['limit_poll_activity'] ) ) ? checked( $bpolls_settings['limit_poll_activity'], 'no' ) : ''; ?>/>&nbsp; <?php esc_html_e( 'No Limit', 'buddypress-polls' ); ?>
				</label>
				<label>
				<input name='bpolls_settings[limit_poll_activity]' type='radio' value='user_role' <?php ( isset( $bpolls_settings['limit_poll_activity'] ) ) ? checked( $bpolls_settings['limit_poll_activity'], 'user_role' ) : ''; ?>/>&nbsp; <?php esc_html_e( 'Limit by User Role', 'buddypress-polls' ); ?>
				</label>
				<label>
				<input name='bpolls_settings[limit_poll_activity]' type='radio' value='member_type' <?php ( isset( $bpolls_settings['limit_poll_activity'] ) ) ? checked( $bpolls_settings['limit_poll_activity'], 'member_type' ) : ''; ?>/>&nbsp; <?php esc_html_e( 'Limit by Member Type', 'buddypress-polls' ); ?>
				</label>				
			</div>
		</div>

		<div class="wbcom-settings-section-wrap" id="bpolls_user_role" 
		<?php
		if ( isset( $bpolls_settings['limit_poll_activity'] ) && 'user_role' !== $bpolls_settings['limit_poll_activity'] ) :
			?>
			style="display:none" <?php endif; ?>>
			<div class="wbcom-settings-section-options-heading">
				<label><?php esc_html_e( 'Select User Roles', 'buddypress-polls' ); ?></label>
				<p class="description">
					<?php esc_html_e( 'Select user role which are allowed to publish poll type activities.', 'buddypress-polls' ); ?>
				</p>
			</div>
			<div class="wbcom-settings-section-options">
				<select class="multi-selectize" name="bpolls_settings[poll_user_role][]" multiple>
					<?php
					$roles = $wp_roles->get_names();
					foreach ( $roles as $role => $rname ) {
						$selected = ( ! empty( $bpolls_settings['poll_user_role'] ) && in_array( $role, $bpolls_settings['poll_user_role'], true ) ) ? 'selected' : '';
						?>
					<option value="<?php echo esc_attr( $role ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $rname ); ?></option>
					<?php } ?>
				</select>				
			</div>		
		</div>
		<?php
		$types = bp_get_member_types( array(), 'objects' );
		if ( $types ) {
			?>
		<div class="wbcom-settings-section-wrap" id="bpolls_member_type" 
			<?php
			if ( isset( $bpolls_settings['limit_poll_activity'] ) && 'member_type' !== $bpolls_settings['limit_poll_activity'] ) :
				?>
			style="display:none" <?php endif; ?>>
			<div class="wbcom-settings-section-options-heading">
				<label><?php esc_html_e( 'Select Member Type', 'buddypress-polls' ); ?></label>
				<p class="description">
					<?php esc_html_e( 'Select member type which are allowed to publish poll type activities.', 'buddypress-polls' ); ?>
				</p>
			</div>
			<div class="wbcom-settings-section-options">
					<select class="multi-selectize" name="bpolls_settings[poll_member_type][]" multiple>

					<?php
					foreach ( $types as $typ ) {
						$selected = ( ! empty( $bpolls_settings['poll_member_type'] ) && in_array( $typ->name, $bpolls_settings['poll_member_type'], true ) ) ? 'selected' : '';
						?>
						<option value="<?php echo esc_attr( $typ->name ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $typ->labels['singular_name'] ); ?></option>
					<?php } ?>
					</select>
				</div>
			</div>		
			<?php } ?>
	</div>
</div>
	<?php submit_button(); ?>
</form>
</div>
</div>
</div>
