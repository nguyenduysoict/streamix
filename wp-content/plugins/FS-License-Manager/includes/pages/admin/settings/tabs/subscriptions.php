<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
<form method="post" action="options.php">
	<?php
	settings_fields( 'fslm_subscriptions_option_group' );
	do_settings_sections( 'fslm_subscriptions_option_group' );
	?>

	<h3><?php echo __( 'Subscriptions Settings', 'fslm' ); ?>:</h3>


	<?php if ( class_exists( 'WC_Subscriptions' ) ) { ?>

		<div class="input-box">
			<div class="label">
				<span><?php echo __( "Don't generate a new license key for renewals", 'fslm' ); ?></span>
			</div>
			<div class="input">
				<input type="checkbox" name="fslm_skip_renewals"
					<?php echo esc_attr( get_option( 'fslm_skip_renewals', 'on' ) ) == 'on' ? 'checked' : ''; ?>>
			</div>
		</div>

		<?php do_action( 'wclm_settings_page_subscriptions_tab' ); ?>

	<?php } ?>

	<?php submit_button(); ?>

</form>
