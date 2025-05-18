<?php
/**
 * Integrations: LearnDash
 *
 * @package     AffiliateWP
 * @subpackage  Integrations
 * @copyright   Copyright (c) 2022, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.9.7
 */

/**
 * Implements an integration for LearnDash.
 *
 * @since 2.9.7
 *
 * @see Affiliate_WP_Base
 */
class Affiliate_WP_LearnDash extends Affiliate_WP_Base {

	/**
	 * Current order.
	 *
	 * @access private
	 * @since  2.9.7
	 */
	private $order;

	/**
	 * The context for referrals. This refers to the integration that is being used.
	 *
	 * @access  public
	 * @since   2.9.7
	 */
	public $context = 'learndash';

	/**
	 * Get things started
	 *
	 * @access  public
	 * @since   2.9.7
	*/
	public function init() {

		// LearnDash v4.2.0+ is required.
		if ( ! defined( 'LEARNDASH_VERSION' ) || version_compare( 'LEARNDASH_VERSION', '4.2.0', '>=' ) ) {
			return;
		}

		// Pretty links.
		add_filter( 'init', array( $this, 'affwp_support_pretty_links' ), 10 );

		// Reference link.
		add_filter( 'affwp_referral_reference_column', array( $this, 'reference_link' ), 10, 2 );

		// Stripe referral tracking.
		add_filter( 'learndash_stripe_session_args', array( $this, 'maybe_track_referral' ) );
		add_action( 'learndash_transaction_created', array( $this, 'process_referral' ), 10 );

		// Check for LearnDash Stripe Integration Addon.
		if ( ! class_exists( 'LearnDash_Stripe' ) ) {
			return;
		}

		// For Stripe Legacy Checkout via the LearnDash Stripe Integration Addon.
		add_action( 'added_post_meta', array( $this, 'insert_referral' ), 10, 4 );

	}

	/**
	 * Gets the total sales for this integration.
	 *
	 * @since 2.9.7
	 *
	 * @param string|array $date  {
	 *    Optional. Date string or start/end range to retrieve orders for. Default empty.
	 *
	 *     @type string        $start Start date to retrieve orders for.
	 *     @type string        $end   End date to retrieve orders for.
	 * }
	 * @return float The total sales based on the date range provided.
	 */
	public function get_total_sales( $date = '' ) {
		return count( get_posts( array(
			'post_type'   => 'sfwd-transactions',
			'date_query'  => $this->prepare_date_range( $date ),
			'numberposts' => -1,
			'meta_query'  => array(
				array(
					'key'     => 'stripe_price',
					'compare' => 'EXISTS',
				),
			),
		) ) );
	}

	/**
	 * Gets the total order count for this integration.
	 *
	 * @since 2.9.7
	 *
	 * @param string|array $date  {
	 *     Optional. Date string or start/end range to retrieve orders for. Default empty.
	 *
	 *     @type string $start Start date to retrieve orders for.
	 *     @type string $end   End date to retrieve orders for.
	 * }
	 * @return int Order total count.
	 */
	public function get_total_order_count( $date = '' ) {
		return count( get_posts( array(
			'post_type'   => 'sfwd-transactions',
			'date_query'  => $this->prepare_date_range( $date ),
			'numberposts' => -1,
		) ) );
	}

	/**
	 * Determines if a transaction was done via LearnDash Stripe addon and creates a referral if needed.
	 *
	 * @since 2.9.7
	 */
	public function insert_referral( $meta_id, $post_id, $meta_key, $meta_value ) {
		// Checking for User ID which is typically one of the last updated.
		if ( 'user_id' !== $meta_key ) {
			return;
		}

		// Bail if not a LearnDash Transaction.
		if ( 'sfwd-transactions' !== get_post_type( $post_id ) ) {
			return;
		}

		// Get order details.
		$order = $this->get_order( $post_id );

		// Bail if there is no order.
		if ( empty( $order ) ) {
			$this->log( 'Insert referral not completed because no order found.' );
			return;
		}

		// Bail if this isn't a Stripe transaction. Only Stripe transactions are supported at this time.
		if ( empty( $order->payment_processor ) || $order->payment_processor !== 'stripe' ) {
			$this->log( 'Draft referral not created. Only Stripe transactions are supported at this time.' );
			return;
		}

		// Bail if Stripe metadata is present, indicates Stripe Connect which uses process_referral.
		if ( ! empty( $order->stripe_metadata ) ) {
			return;
		}

		// Get affiliate ID.
		$affiliate_id = $this->affiliate_id;

		if ( 0 === $affiliate_id ) {
			$this->log( 'Draft referral not created because affiliate ID was not set.' );
			return;
		}

		// Assign email.
		$this->email = $order->stripe_email;

		// Create draft referral.
		$referral_id = $this->insert_draft_referral(
			$affiliate_id,
			array(
				'reference' => $order->id,
			)
		);

		if ( ! $referral_id ) {
			$this->log( 'Draft referral creation failed.' );
			return;
		}

		// Customers cannot refer themselves.
		if ( $this->is_affiliate_email( $this->email, $affiliate_id ) ) {
			$this->log( 'Referral not created because affiliate\'s own account was used.' );
			$this->mark_referral_failed( $referral_id );
			return;
		}

		// One time charge.
		if ( ! empty( $order->stripe_price_type ) && 'paynow' === $order->stripe_price_type ) {
			$this->log( 'Processing referral for Stripe one-time charge.' );
		}

		// Subsription charge.
		if ( ! empty( $order->stripe_price_type ) && 'subscribe' === $order->stripe_price_type ) {
			$this->log( 'Processing referral for Stripe subscription.' );
		}

		// Find out if test mode is enabled.
		$stripe_options = get_option('learndash_stripe_settings');

		// Hydrates the previously created referral.
		$this->hydrate_referral(
			$referral_id,
			array(
				'status'      => 'pending',
				'amount'      => $this->calculate_referral_amount( $order->stripe_price, $post_id, 0, $affiliate_id ),
				'description' => $order->description,
				'order_total' => $this->get_order_total( $order->id ),
				'products'    => $this->get_products( $order->id ),
				'custom'      => array(
					'affiliate_id' => $affiliate_id,
					'visit_id'     => $visit_id,
					'testmode'     => isset( $stripe_options['test_mode'] )
						? (bool) $stripe_options['test_mode']
						: false,
				),
			)
		);

		$this->log( sprintf( 'LearnDash referral #%d updated to pending successfully.', $referral_id ) );

		// Complete referral setting it to 'Unpaid'.
		$this->complete_referral( $order->id );
	}

	/**
	 * Adds affiliate metadata to Stripe session arguments.
	 *
	 * @since 2.9.7
	 *
	 * @param array $session_args Stripe session arguments.
	 *
	 * @return array (Maybe) modified array of object metadata.
	 */
	public function maybe_track_referral( $session_args ) {
		if ( ! $this->was_referred() ) {
			return $session_args;
		}

		$session_args['metadata']['affwp_visit_id']     = affiliate_wp()->tracking->get_visit_id();
		$session_args['metadata']['affwp_affiliate_id'] = $this->affiliate_id;

		return $session_args;
	}

	/**
	 * Determines if a transaction contains affiliate metadata and creates a referral if needed.
	 *
	 * @since 2.9.7
	 *
	 * @todo Make sure this only works for Stripe. Add Paypal support later.
	 *
	 * @param int $transaction_id Transaction ID.
	 */
	public function process_referral( $transaction_id ) {
		// Get order details.
		$order = $this->get_order( $transaction_id );

		// Bail if there is no order.
		if ( empty( $order ) ) {
			$this->log( 'Process referral not completed because no order found.' );
			return;
		}

		// Bail if this isn't a Stripe transaction. Only Stripe transactions are supported at this time.
		if ( empty( $order->payment_processor ) || $order->payment_processor !== 'stripe' ) {
			return;
		}

		$affiliate_id = isset( $order->stripe_metadata->affwp_affiliate_id ) ? $order->stripe_metadata->affwp_affiliate_id : 0;

		if ( 0 === $affiliate_id ) {
			$this->log( 'Draft referral not created because affiliate ID was not set.' );
			return;
		}

		$visit_id = isset( $order->stripe_metadata->affwp_visit_id )
			? intval( $order->stripe_metadata->affwp_visit_id )
			: false;

		// Assign email.
		$this->email = isset( $order->customer_email ) ? $order->customer_email : '';

		// Create draft referral.
		$referral_id = $this->insert_draft_referral(
			$affiliate_id,
			array(
				'reference' => $transaction_id,
				'visit_id'  => $visit_id
			)
		);

		if ( ! $referral_id ) {
			$this->log( 'Draft referral creation failed.' );
			return;
		}

		if ( $this->is_affiliate_email( $this->email, $affiliate_id ) ) {
			$this->log( 'Referral not created because affiliate\'s own account was used.' );
			$this->mark_referral_failed( $referral_id );
			return;
		}


		// One time charge.
		if ( ! empty( $order->stripe_price_type ) && 'paynow' === $order->stripe_price_type ) {
			$this->log( 'Processing referral for Stripe one-time charge.' );
			$payment_intent = $order->stripe_payment_intent;
		}

		// Subsription charge.
		if ( ! empty( $order->stripe_price_type ) && 'subscribe' === $order->stripe_price_type ) {
			$this->log( 'Processing referral for Stripe subscription.' );
			$payment_intent = $order->subscription;
		}

		if ( empty( $order->description ) ) {
			$order->description = get_the_title( $order->id );
		}

		// Find out if test mode is enabled.
		$stripe_options = get_option('learndash_stripe_connection_settings');

		// Hydrates the previously created referral.
		$this->hydrate_referral(
			$referral_id,
			array(
				'status'      => 'pending',
				'amount'      => $this->calculate_referral_amount( $order->stripe_price, $transaction_id, 0, $affiliate_id ),
				'description' => $order->description,
				'visit_id'    => $visit_id,
				'order_total' => $this->get_order_total( $order->id ),
				'products'    => $this->get_products( $order->id ),
				'custom'      => array(
					'affiliate_id' => $affiliate_id,
					'visit_id'     => $visit_id,
					'testmode'     => isset( $stripe_options['test_mode'] )
						? (bool) $stripe_options['test_mode']
						: false,
					'payment_data' => $payment_intent
				),
			)
		);

		$this->log( sprintf( 'LearnDash referral #%d updated to pending successfully.', $referral_id ) );

		// Complete referral setting it to 'Unpaid'.
		$this->complete_referral( $order->id );
	}

	/**
	 * Sets up the reference link in the Referrals table
	 *
	 * @since   2.9.7
	 *
	 * @todo Use payment data and testmode to link to appropriate stripe payment/subscription page.
	 *       Note: only Stripe Connect has payment intent for creating the direct URL rather than generic /payments.
	*/
	public function reference_link( $reference, $referral ) {

		if ( empty( $referral->context ) || 'learndash' != $referral->context ) {
			return $reference;
		}

		$url = sprintf( 'edit.php?post_type=sfwd-transactions&s=%s', get_the_title( $reference ) );

		return sprintf( '<a href="%1$s">%2$s</a>', admin_url( $url ), $reference );
	}

	/**
	 * Retrieves order details for an order by ID.
	 *
	 * @since 2.9.7
	 *
	 * @param int $transaction_id LearnDash Transaction ID.
	 * @return mixed Object of order-related data, or false if no order is found.
	 */
	private function get_order( $transaction_id ) {
		// Add order details if not found.
		if ( $this->order ) {
			return $this->order;
		}

		$post = get_post( $transaction_id );

		if( ! $post ) {
			return false;
		}

		$order = new stdClass();

		$order->id = absint( $transaction_id );

		// WP Post.
		$order->post = $post;

		$payment_data = get_post_meta( $transaction_id );

		// Stripe Connect Metadata.
		$order->stripe_metadata = ! empty( $payment_data['stripe_metadata'] ) && isset( $payment_data['stripe_metadata'][0] ) ? maybe_unserialize( $payment_data['stripe_metadata'][0] ) : null;

		// Payment-related.
		$order->stripe_price_type     = ! empty( $payment_data['stripe_price_type'] ) && isset( $payment_data['stripe_price_type'][0] ) ? $payment_data['stripe_price_type'][0] : null;
		$order->stripe_payment_intent = ! empty( $payment_data['stripe_payment_intent'] ) && isset( $payment_data['stripe_payment_intent'][0] ) ? $payment_data['stripe_payment_intent'][0] : null;
		$order->subscription          = ! empty( $payment_data['subscription'] ) && isset( $payment_data['subscription'][0] ) ? $payment_data['subscription'][0] : null;
		$order->stripe_price          = ! empty( $payment_data['stripe_price'] ) && isset( $payment_data['stripe_price'][0] ) ?  $payment_data['stripe_price'][0] : null;
		$order->stripe_currency       = ! empty( $payment_data['stripe_currency'] ) && isset( $payment_data['stripe_currency'][0] ) ? $payment_data['stripe_currency'][0] : null;

		// Payment processor.
		$order->payment_processor = ! empty( $payment_data['ld_payment_processor'] ) && isset( $payment_data['ld_payment_processor'][0] ) ? $payment_data['ld_payment_processor'][0] : null;

		if ( empty( $order->payment_processor ) ) {
			// If Stripe price type is set, set processor as Stripe.
			// Note: cannot rely on ld_payment_processor because its added later in the addon.
			if ( ! empty( $order->stripe_price_type ) ) {
				$order->payment_processor = 'stripe';
			}
		}

		// User-related.
		$order->stripe_email   = ! empty( $payment_data['stripe_email'] ) && isset( $payment_data['stripe_email'][0] ) ? $payment_data['stripe_email'][0] : null;
		$order->customer_email = ! empty( $payment_data['customer_email'] ) && isset( $payment_data['customer_email'][0] ) ? $payment_data['customer_email'][0] : null;

		// Product-related.
		$order->stripe_name = ! empty( $payment_data['stripe_name'] ) && isset( $payment_data['stripe_name'][0] ) ? $payment_data['stripe_name'][0] : null;
		$order->description = $order->stripe_name;

		// "cache"
		return $this->order = $order;

	}

	/**
	 * Retrieves the product details array for the referral
	 *
	 * @since   2.9.7
	 * @return  array
	 */
	public function get_products( $payment_id = 0 ) {
		return learndash_transaction_get_payment_meta( $payment_id );
	}

	/**
	 * Retrieves the order total from the order.
	 *
	 * @since  2.9.7
	 * @since  2.12.0 Check if the function exists before calling it.
	 *
	 * @param int $order The order ID.
	 * @return float The order total for the current integration.
	 */
	public function get_order_total( $order = 0 ) {

		if ( ! function_exists( 'learndash_transaction_get_final_price' ) ) {
			return 0;
		}

		return learndash_transaction_get_final_price( $order );
	}

	/**
	 * Sets up verbose rewrites for the course base in conjunction with pretty affiliate URLs.
	 *
	 * @since  2.9.7
	 */
	public function affwp_support_pretty_links() {
		$course_pt = learndash_get_post_type_slug( 'course' );

		if ( null === $course_pt ) {
			return;
		}

		// Use the LD course slug.
		$slug = ! empty( get_post_type_object( $course_pt )->rewrite['slug'] )
			? get_post_type_object( $course_pt )->rewrite['slug']
			: 'courses';

		// Referral variable.
		$ref = affiliate_wp()->tracking->get_referral_var();


		// For query with sfwd-courses post type.
		add_rewrite_rule( sprintf( '%1$s/%2$s(/(.*))?/?$',
			$slug,
			$ref
		), sprintf( 'index.php?post_type=sfwd-courses&%1$s=$matches[2]',
			$ref
		), 'top' );
	}

	/**
	 * Runs the check necessary to confirm this plugin is active.
	 *
	 * @since 2.9.7
	 *
	 * @return bool True if the plugin is active, false otherwise.
	 */
	function plugin_is_active() {
		return class_exists( 'SFWD_LMS' );
	}
}

new Affiliate_WP_LearnDash;
