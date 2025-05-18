<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
<form method="post" action="options.php">

	<?php
	settings_fields( 'fslm_email_template_option_group' );

	$heading = get_option( 'fslm_mail_heading', __( 'License Keys for Order #[order_id]', 'fslm' ) );
	$subject = get_option( 'fslm_mail_subject', __( '[site_name] | License Keys for Order #[order_id]', 'fslm' ) );
	$message = get_option( 'fslm_mail_message',
		__( '<p>Dear [customer-first-name] [customer-last-name]</p>
            <p>Thank you for your order, those are your license keys for the order #[order_id]</p>
            <p>you can see all your past orders and license keys <a title="My Account" href="[myaccount_url]">here</a>.</p>',
			'fslm' ) );

	?>

	<h3><?php echo __( 'Email Settings', 'fslm' ); ?>:</h3>
	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Defer Sending WooCommerce Emails', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input type="checkbox"
			       name="wclm_defer_sending_woocommerce_emails"
				<?php echo esc_attr( get_option( 'wclm_defer_sending_woocommerce_emails', 'on' ) ) == 'on' ? 'checked' : ''; ?>>
		</div>

		<blockquote class="text-danger error">
			<?php esc_html_e( 'Required if you are using High-performance order storage.', 'fslm' ); ?>
		</blockquote>
	</div>

	<h3><?php echo __( 'Email Template', 'fslm' ); ?>:</h3>

	<p><?php echo __( 'Shortcodes:', 'fslm' ) ?></p>

	<table class="wp-list-table widefat fixed striped posts">
		<thead>
		<tr>
			<td><b><?php echo __( 'Shortcods:', 'fslm' ) ?></b></td>
			<td><?php echo __( 'Function:', 'fslm' ) ?></td>
			<td></td>
			<td><b><?php echo __( 'Shortcods:', 'fslm' ) ?></b></td>
			<td><?php echo __( 'Function:', 'fslm' ) ?></td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td><b>[order_id]<b/></td>
			<td><?php echo __( 'Order ID', 'fslm' ) ?></td>
			<td></td>
			<td><b>[customer-shipping-last-name]<b/></td>
			<td><?php echo __( 'Shipping Last Name', 'fslm' ) ?></td>
		</tr>

		<tr>
			<td><b>[customer-first-name]<b/></td>
			<td><?php echo __( 'Billing First Name', 'fslm' ) ?></td>
			<td></td>
			<td><b>[site_name]<b/></td>
			<td><?php echo __( 'Site Name', 'fslm' ) ?></td>
		</tr>

		<tr>
			<td><b>[customer-last-name]<b/></td>
			<td><?php echo __( 'Billing Last Name', 'fslm' ) ?></td>
			<td></td>
			<td><b>[url]<b/></td>
			<td><?php echo __( 'Site URL', 'fslm' ) ?></td>
		</tr>

		<tr>
			<td><b>[customer-shipping-last-name]<b/></td>
			<td><?php echo __( 'Shipping First Name', 'fslm' ) ?></td>
			<td></td>
			<td><b>[myaccount_url]<b/></td>
			<td><?php echo __( 'My Account page URL', 'fslm' ) ?></td>
		</tr>


		</tbody>
	</table>

	<br>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Add WooCommerce Default Email Header &amp; Footer', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input type="checkbox"
			       name="fslm_add_wc_header_and_footer"
				<?php echo esc_attr( get_option( 'fslm_add_wc_header_and_footer', 'on' ) ) == 'on' ? 'checked' : ''; ?>>
		</div>
	</div>

	<div class="input-box">
		<div class="label">
            <span><?php echo __( 'Add License Keys to Default WooCommerce Email Too(Default WooCommerce email without the custom template)',
		            'fslm' ); ?></span>
		</div>
		<div class="input">
			<input type="checkbox" name="fslm_add_lk_wc_de"
				<?php echo esc_attr( get_option( 'fslm_add_lk_wc_de', 'on' ) ) == 'on' ? 'checked' : ''; ?>>
			<div class="helper">?
				<div class="tip">
					<?php echo __( 'In addition to the email with the custom template the license keys will be added to WooCommerce\'s default order email too',
						'fslm' ); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="input-box">
		<div class="label">
            <span><?php echo __( 'Send a second email that contain the license keys only and uses the template',
		            'fslm' ); ?></span>
		</div>
		<div class="input">
			<input type="checkbox" name="fslm_add_lk_se"
				<?php echo esc_attr( get_option( 'fslm_add_lk_se', 'off' ) ) == 'on' ? 'checked' : ''; ?>>
		</div>
	</div>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Heading', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input class="input-field" name="fslm_mail_heading" id="fslm_mail_heading" type="text"
			       value="<?php echo $heading ?>">
		</div>
	</div>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Subject', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input class="input-field" type="text" name="fslm_mail_subject" id="fslm_mail_heading"
			       value="<?php echo $subject; ?>">
		</div>
	</div>

	<div>
		<div class="label">
			<span class="mb15"><?php echo __( 'Message', 'fslm' ); ?></span>
		</div>
		<div class="input xl">
            <textarea class="fslm_mail_message" name="fslm_mail_message" id="fslm_mail_message"
                      type="email"><?php echo $message ?></textarea>
		</div>
	</div>

	<br>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'License Keys Page URL', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input class="input-field" name="fslm_license_keys_page_url" id="fslm_license_keys_page_url" type="text"
			       value="<?php echo get_option( 'fslm_license_keys_page_url', get_permalink( get_option( 'fslm_page_id' ) ) ) ?>">
		</div>
		<blockquote>
			<p class="description">
				<?php echo __( 'The full URL of the page containing the shortcode [license_keys].', 'fslm' ); ?><br>
				<?php echo __( 'Example:', 'fslm' ); ?><br>
				<?php echo __( 'https://domain.com/license-keys', 'fslm' ); ?><br>
			</p>
		</blockquote>
	</div>

	<?php submit_button(); ?>
</form>