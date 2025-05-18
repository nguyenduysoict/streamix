<?php

class WCLM_DeferSendingWooCommerceEmails {
	private static $instance;
	private $default_defer_time;
	// An associative array to match $email_id with email class, to allow for the deferring of different emails.
	private $email_id_to_defer;


	// Returns an instance of this class.
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new WCLM_DeferSendingWooCommerceEmails();
		}

		return self::$instance;
	}


	// Initialize the plugin variables.
	public function __construct() {
		$this->default_defer_time = 10; // Defer for 600 seconds (10 minutes).
		// Can add other email IDs and their defer times.
		// You can uncomment the add_action() with 'order_status_changed' function to find out the before and after status.
		// This is also the list of emails that will be deferred.
		$this->email_id_to_defer = array(
			'woocommerce_order_status_completed'             => $this->default_defer_time,
			'woocommerce_order_status_processing'            => $this->default_defer_time,
			'woocommerce_order_status_pending_to_processing' => $this->default_defer_time,
			'woocommerce_order_status_pending_to_completed'  => $this->default_defer_time,
		);

		$this->init();
	}


	// Set up WordPress specfic actions.
	public function init() {
		// Set all WooCommerce emails to be deferred.
		add_filter( 'woocommerce_defer_transactional_emails', '__return_true' );

		// Allow most emails to be sent as normal but prevent emails listed in $this->email_id_to_defer. Schedule them for a time in the future.
		add_filter( 'woocommerce_allow_send_queued_transactional_email', array(
			$this,
			'whether_send_queued_wc_email'
		), 10, 3 );

		// This is the scheduled function that will send the email.
		add_action( 'send_deferred_woocommerce_email', array( $this, 'send_deferred_woocommerce_email' ), 10, 2 );

		// DEBUG: Add the order modification time and current time to prove that the email was intentionally delayed.
		//add_action( 'woocommerce_email_order_details', array( $this, 'add_defer_length_info_to_order_email' ), 5, 4 );

		// Uncomment this to log the before and after statuses so you know what ones
		// to add to the $this->email_id_to_defer array.
		//add_action( 'woocommerce_order_status_changed', array( $this, 'order_status_changed' ), 10, 4 );
	}

	// Log the before and after status to discover which one to add to $this->email_id_to_defer array.
	public function order_status_changed( $id, $from, $to, $order ) {
		error_log( 'Order ID: ' . $id );
		error_log( 'Status from: ' . $from );
		error_log( 'Status to: ' . $to );
	}


	private function get_email_defer_time( $filter ) {
		if ( array_key_exists( $filter, $this->email_id_to_defer ) ) {
			return $this->email_id_to_defer[ $filter ];
		}

		return $this->default_defer_time;
	}


	public function whether_send_queued_wc_email( $true, $filter, $args ) {
		//error_log( 'woocommerce_allow_send_queued_transactional_email $filter: ' . var_export( $filter, true ) );
		//error_log( 'woocommerce_allow_send_queued_transactional_email order_number: ' . var_export( $args[ 0 ], true ) );

		if ( array_key_exists( $filter, $this->email_id_to_defer ) ) {
			// TODO: Consider verifying that $args[0] is a valid order number.
			//$order = wc_get_order( $args[ 0 ] );
			//$action_args = array( 'filter' => $filter, 'args' => $args );

			// Call Action Scheduler instead of WP Cron. Args will be filter and $args.
			$event_id = as_schedule_single_action( time() + $this->get_email_defer_time( $filter ), 'send_deferred_woocommerce_email', array(
				$filter,
				$args
			) );

			//$order_num = $args[ 0 ];
			//error_log( sprintf( 'woocommerce_allow_send_queued_transactional_email: Defer a %s email for order: %s for %d seconds (event ID: %d).', $filter, $order_num, $this->get_email_defer_time( $filter ), $event_id ) );

			return false;
		}

		//error_log( 'woocommerce_allow_send_queued_transactional_email: Ok to send email.' );

		return $true;
	}


	// Send the deferred email for order $order_id.
	public function send_deferred_woocommerce_email( $filter, $args = array() ) {
		//error_log( 'send_deferred_woocommerce_email for order: ' . $order_id );

		// Use same code as WC_Emails::send_queued_transactional_email() in woocommerce/includes/class-wc-emails.php
		WC_Emails::instance();
		// Ensure gateways are loaded in case they need to insert data into the emails.
		WC()->payment_gateways();
		WC()->shipping();

		do_action_ref_array( $filter . '_notification', $args );
	}


	// This is an experimental function to add the date/time the order was modified and
	// the date/time the email was sent into email - to demonstrate that the deferring code worked.
	public function add_defer_length_info_to_order_email( $order, $sent_to_admin, $plain_text, $email ) {
		if ( $plain_text ) {
			printf( '%sThe order was modified at %s and email sent at %s.', "\n", $order->get_date_modified(), current_time( 'mysql' ) );
		} else {
			printf( '<p>The order was modified at <strong>%s</strong> and email sent at <strong>%s</strong>.</p>', $order->get_date_modified(), current_time( 'mysql' ) );
		}
	}
}