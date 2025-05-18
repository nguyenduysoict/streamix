<?php
$affiliate_id = affwp_get_affiliate_id();
?>
<div id="affwp-affiliate-dashboard-url-generator" class="affwp-tab-content">

	<h4><?php _e( 'Affiliate URLs', 'affiliate-wp' ); ?></h4>

	<?php
	/**
	 * Fires at the top of the Affiliate URLs dashboard tab.
	 *
	 * @since 2.0.5
	 *
	 * @param int $affiliate_id Affiliate ID of the currently logged-in affiliate.
	 */
	do_action( 'affwp_affiliate_dashboard_urls_top', $affiliate_id );
	?>

	<p>
		<?php if ( 'id' == affwp_get_referral_format() ) : ?>
			<?php
			/* translators: Affiliate ID */
			printf( __( 'Your affiliate ID is: <strong>%s</strong>', 'affiliate-wp' ), $affiliate_id );
			?>
		<?php elseif ( 'username' == affwp_get_referral_format() ) : ?>
			<?php
			/* translators: Affiliate username */
			printf( __( 'Your affiliate username is: <strong>%s</strong>', 'affiliate-wp' ), affwp_get_affiliate_username() );
			?>
		<?php endif; ?>
		<br>
		<?php
		/* translators: Affiliate referral URL */
		printf( __( 'Your referral URL is: <strong>%s</strong>', 'affiliate-wp' ), esc_url( urldecode( affwp_get_affiliate_referral_url() ) ) );
		?>
	</p>

	<?php
	/**
	 * Fires just before the Custom Link Generator.
	 *
	 * @since 2.0.5
	 *
	 * @param int $affiliate_id Affiliate ID of the currently logged-in affiliate.
	 */
	do_action( 'affwp_affiliate_dashboard_urls_before_generator', $affiliate_id );

	/**
	 * Render the Custom Links Generator.
	 *
	 * @since 2.14.0
	 *
	 * @param int $affiliate_id Affiliate ID of the currently logged-in affiliate.
	 */
	do_action( 'affwp_render_custom_link_generator', $affiliate_id );

	/**
	 * Fires at the bottom of the Affiliate URLs dashboard tab.
	 *
	 * @since 2.0.5
	 *
	 * @param int $affiliate_id Affiliate ID of the currently logged-in affiliate.
	 */
	do_action( 'affwp_affiliate_dashboard_urls_bottom', $affiliate_id );
	?>

</div>
