<?php
/**
 * Exit if accessed directly.
 *
 * @package bp-post-from-anyhwere
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * BuddyPress - Activity Post Form
 *
 * @package BuddyPress
 * @subpackage bp-activity
 */
?>

<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="bppfa-whats-new-form" name="whats-new-form">

	<?php
	/**
	 * Fires before the activity post form.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_activity_post_form' );
	?>

	<div id="bppfa-whats-new-avatar">
		<a href="<?php echo esc_html( bp_loggedin_user_domain() ); ?>">
			<?php bp_loggedin_user_avatar( 'width=' . bp_core_avatar_thumb_width() . '&height=' . bp_core_avatar_thumb_height() ); ?>
		</a>
	</div>

	<p class="activity-greeting">
	<?php
	if ( bp_is_group() ) {
		/* translators: %1\$s: Display Group Name, %2\$s?: Display First Name */
		printf( esc_html__( "What's new in %1\$s, %2\$s?", 'bp-post-from-anywhere' ), esc_html( bp_get_group_name() ), esc_html( bp_get_user_firstname( bp_get_loggedin_user_fullname() ) ) );
	} else {
		/* translators: %s?: Display First Name */
		printf( esc_html__( "What's new, %s?", 'bp-post-from-anywhere' ), esc_html( bp_get_user_firstname( bp_get_loggedin_user_fullname() ) ) );
	}
	?>
		</p>

	<div id="bppfa-whats-new-content">
		<div id="whats-new-textarea">
			<label for="whats-new" class="bp-screen-reader-text">
			<?php
				/* translators: accessibility text */
				esc_html_e( 'Post what\'s new', 'bp-post-from-anywhere' );
			?>
				</label>
				<textarea class="bp-suggestions" name="whats-new" id="bppfa-whats-new" cols="50" rows="10" <?php if ( bp_is_group() ) : ?> data-suggestions-group-id="<?php echo esc_attr( (int) bp_get_current_group_id() ); ?>" <?php endif; ?> ><?php if ( isset( $_GET['r'] ) ) : ?><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['r'] ) ) ); endif; ?></textarea>
		</div>

		<div id="bppfa-whats-new-options">
			<div id="bppfa-whats-new-submit">
				<input type="submit" name="aw-whats-new-submit" id="bppfa-aw-whats-new-submit" value="<?php esc_attr_e( 'Post Update', 'buddypress' ); ?>" />
				<div class="bppfa-spinner"></div>
			</div>

			<?php if ( bp_is_active( 'groups' ) && ! bp_is_my_profile() && ! bp_is_group() ) : ?>

				<div id="bppfa-whats-new-post-in-box">
					<label for="bppfa-whats-new-post-in" class="bp-screen-reader-text">
					<?php
						/* translators: accessibility text */
						esc_html_e( 'Post in', 'bp-post-from-anywhere' );
					?>
						</label>
					<select id="bppfa-whats-new-post-in" name="whats-new-post-in">
						<option selected="selected" data-value="user" value="0"><?php esc_html_e( 'My Profile', 'bp-post-from-anywhere' ); ?></option>

						<?php
						if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) :
							while ( bp_groups() ) :
								bp_the_group();
								?>

								<option data-value="group" value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

								<?php
							endwhile;
						endif;
						?>

					</select>
				</div>
				<input type="hidden" id="bppfa-whats-new-post-object" name="whats-new-post-object" value="user" />

			<?php else : ?>
				<input type="hidden" id="bppfa-whats-new-post-object" name="whats-new-post-object" value="user" />				
			<?php endif; ?>

			<?php
			/**
			 * Fires at the end of the activity post form markup.
			 *
			 * @since 1.2.0
			 */

			if ( apply_filters( 'bppfa_extended_form_option', true ) ) {
				do_action( 'bp_activity_post_form_options' );
			}
			?>

		</div><!-- #whats-new-options -->
	</div><!-- #whats-new-content -->
	<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
</form><!-- #whats-new-form -->
