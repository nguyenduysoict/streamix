<?php
/**
 * Upgrades API
 *
 * @package     AffiliateWP
 * @subpackage  Utilities
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 *
 * @see includes/utils/traits/trait-db.php `upgrade_table()` for alternative usage for
 *      upgrading database tables vs. using legacy methods for doing so here.
 */

affwp_require_util_traits( 'db', 'data' );

/**
 * Core class for handling upgrade operations.
 *
 * @since 1.0.0
 */
class Affiliate_WP_Upgrades {

	use \AffiliateWP\Utils\DB;
	use \AffiliateWP\Utils\Data;

	/**
	 * Whether debug mode is enabled.
	 *
	 * @since 1.8.6
	 * @access private
	 * @var bool
	 */
	private $debug;

	/**
	 * Affiliate_WP_Logging instance.
	 *
	 * @since 1.8.6
	 * @access private
	 * @var Affiliate_WP_Logging
	 */
	private $logs;

	/**
	 * Signals whether the upgrade was successful.
	 *
	 * @access public
	 * @var    bool
	 */
	private $upgraded = false;

	/**
	 * AffiliateWP version.
	 *
	 * @access private
	 * @since  2.0
	 * @var    string
	 */
	private $version;

	/**
	 * Utilities class instance.
	 *
	 * @access private
	 * @since  2.0
	 * @var    \Affiliate_WP_Utilities
	 */
	private $utils;

	/**
	 * Upgrade routine registry.
	 *
	 * @access private
	 * @since  2.0.5
	 * @var    \AffWP\Utils\Upgrades\Registry
	 */
	private $registry;

	/**
	 * Sets up the Upgrades class instance.
	 *
	 * @access public
	 *
	 * @param \Affiliate_WP_Utilities $utils Utilities class instance.
	 */
	public function __construct( $utils ) {

		$this->utils    = $utils;
		$this->version  = get_option( 'affwp_version' );
		$this->registry = new \AffWP\Utils\Upgrades\Registry;

		add_action( 'init', array( $this, 'init' ), -9999 );

		$settings = new Affiliate_WP_Settings;
		$this->debug = (bool) $settings->get( 'debug_mode', false );

		$this->register_core_upgrades();
	}

	/**
	 * Initializes upgrade routines for the current version of AffiliateWP.
	 *
	 * @access public
	 */
	public function init() {

		if ( empty( $this->version ) ) {
			$this->version = '1.0.6'; // last version that didn't have the version option set
		}

		if ( version_compare( $this->version, '1.1', '<' ) ) {
			$this->v11_upgrades();
		}

		if ( version_compare( $this->version, '1.2.1', '<' ) ) {
			$this->v121_upgrades();
		}

		if ( version_compare( $this->version, '1.3', '<' ) ) {
			$this->v13_upgrades();
		}

		if ( version_compare( $this->version, '1.6', '<' ) ) {
			$this->v16_upgrades();
		}

		if ( version_compare( $this->version, '1.7', '<' ) ) {
			$this->v17_upgrades();
		}

		if ( version_compare( $this->version, '1.7.3', '<' ) ) {
			$this->v173_upgrades();
		}

		if ( version_compare( $this->version, '1.7.11', '<' ) ) {
			$this->v1711_upgrades();
		}

		if ( version_compare( $this->version, '1.7.14', '<' ) ) {
			$this->v1714_upgrades();
		}

		if ( version_compare( $this->version, '1.9', '<' ) ) {
			$this->v19_upgrade();
		}

		if ( version_compare( $this->version, '1.9.5', '<' ) ) {
			$this->v195_upgrade();
		}

		if ( true === version_compare( AFFILIATEWP_VERSION, '2.0', '<' ) ) {
			$this->v20_upgrade();
		}

		if ( version_compare( $this->version, '2.0.2', '<' ) ) {
			$this->v202_upgrade();
		}

		if ( version_compare( $this->version, '2.0.10', '<' ) ) {
			$this->v210_upgrade();
		}

		if ( version_compare( $this->version, '2.1', '<' ) ) {
			$this->v21_upgrade();
		}

		if ( version_compare( $this->version, '2.1.3.1', '<' ) ) {
			$this->v2131_upgrade();
		}

		if ( version_compare( $this->version, '2.2', '<' ) ) {
			$this->v22_upgrade();
		}

		if ( version_compare( $this->version, '2.2.2', '<' ) ) {
			$this->v222_upgrade();
		}

		if ( version_compare( $this->version, '2.2.8', '<' ) ) {
			$this->v228_upgrade();
		}

		if ( version_compare( $this->version, '2.2.9', '<' ) ) {
			$this->v229_upgrade();
		}

		if ( version_compare( $this->version, '2.3', '<' ) ) {
			$this->v23_upgrade();
		}

		if ( version_compare( $this->version, '2.4', '<' ) ) {
			$this->v24_upgrade();
		}

		if ( version_compare( $this->version, '2.4.2', '<' ) ) {
			$this->v242_upgrade();
		}

		if ( version_compare( $this->version, '2.5', '<' ) ) {
			$this->v25_upgrade();
		}

		if ( version_compare( $this->version, '2.6', '<' ) ) {
			$this->v26_upgrade();
		}

		if ( version_compare( $this->version, '2.7', '<' ) ) {
			$this->v27_upgrade();
		}

		if ( version_compare( $this->version, '2.7.4', '<' ) ) {
			$this->v274_upgrade();
		}

		if ( version_compare( $this->version, '2.8', '<' ) ) {
			$this->v28_upgrade();
		}

		if ( version_compare( $this->version, '2.9', '<' ) ) {
			$this->v29_upgrade();
		}

		if ( version_compare( $this->version, '2.9.5', '<' ) ) {
			$this->v295_upgrade();
		}

		if ( version_compare( $this->version, '2.9.6', '<' ) ) {
			$this->v296_upgrade();
		}

		if ( version_compare( $this->version, '2.9.6.1', '<' ) ) {
			$this->v2961_upgrade();
		}

		if ( version_compare( $this->version, '2.11.0', '<' ) ) {
			$this->v2110_upgrade();
		}

		if ( version_compare( $this->version, '2.14.0', '<' ) ) {
			$this->v2140_upgrade();
		}

		if ( version_compare( $this->version, '2.15.0', '<' ) ) {
			$this->v2150_upgrade();
		}

		if ( version_compare( $this->version, '2.16.0', '<' ) ) {
			$this->v2160_upgrade();
		}

		if ( version_compare( $this->version, '2.16.3', '<' ) ) {
			$this->v2163_upgrade();
		}

		if ( version_compare( $this->version, '2.17.0', '<' ) ) {
			$this->v2170_upgrade();
		}

		if ( version_compare( $this->version, '2.18.0', '<' ) ) {
			$this->v2180_upgrade();
		}

		// Inconsistency between current and saved version.
		if ( version_compare( $this->version, AFFILIATEWP_VERSION, '<>' ) ) {
			$this->upgraded = true;
		}

		// If upgrades have occurred.
		if ( $this->upgraded ) {

			update_option( 'affwp_version_upgraded_from', $this->version );
			update_option( 'affwp_version', AFFILIATEWP_VERSION );
		}
	}

	/**
	 * Registers core upgrade routines.
	 *
	 * @access private
	 * @since  2.0.5
	 *
	 * @see \Affiliate_WP_Upgrades::add_routine()
	 */
	private function register_core_upgrades() {
		$this->add_routine( 'upgrade_v20_recount_unpaid_earnings', array(
			'version' => '2.0',
			'compare' => '<',
			'batch_process' => array(
				'id'    => 'recount-affiliate-stats-upgrade',
				'class' => 'AffWP\Utils\Batch_Process\Upgrade_Recount_Stats',
				'file'  => AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/upgrades/class-batch-upgrade-recount-affiliate-stats.php'
			)
		) );

		$this->add_routine( 'upgrade_v22_create_customer_records', array(
			'version' => '2.2',
			'compare' => '<',
			'batch_process' => array(
				'id'    => 'create-customers-upgrade',
				'class' => 'AffWP\Utils\Batch_Process\Upgrade_Create_Customers',
				'file'  => AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/upgrades/class-batch-upgrade-create-customers.php'
			)
		) );

		$this->add_routine( 'upgrade_v245_create_customer_affiliate_relationship_records', array(
			'version' => '2.4.5',
			'compare' => '<',
			'batch_process' => array(
				'id'    => 'create-customer-affiliate-relationship-upgrade',
				'class' => 'AffWP\Utils\Batch_Process\Upgrade_Create_Customer_Affiliate_Relationship',
				'file'  => AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/upgrades/class-batch-upgrade-create-customer-affiliate-relationship.php'
			)
		) );

		$this->add_routine( 'upgrade_v26_create_dynamic_coupons', array(
			'version' => '2.6',
			'compare' => '<',
			'batch_process' => array(
				'id'    => 'create-dynamic-coupons-upgrade',
				'class' => 'AffWP\Utils\Batch_Process\Upgrade_Create_Dynamic_Coupons',
				'file'  => AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/upgrades/class-batch-upgrade-create-dynamic-coupons.php',
			),
		) );

		$this->add_routine( 'upgrade_v261_utf8mb4_compat', array(
			'version' => '2.6.1',
			'compare' => '<',
			'batch_process' => array(
				'id'    => 'upgrade-db-utf8mb4',
				'class' => 'AffWP\Utils\Batch_Process\Upgrade_Database_ut8mb4_Compat',
				'file'  => AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/upgrades/class-batch-upgrade-db-utf8mb4.php',
			),
		) );

		$this->add_routine( 'upgrade_v27_calculate_campaigns', array(
			'version' => '2.7',
			'compare' => '<',
		) );

		$this->add_routine( 'upgrade_v274_calculate_campaigns', array(
			'version' => '2.7.4',
			'compare' => '<',
		) );

		$this->add_routine( 'migrate_affiliate_user_meta', array(
			'version'       => '2.8',
			'compare'       => '<',
			'batch_process' => array(
				'id'    => 'migrate-affiliate-user-meta',
				'class' => 'AffWP\Utils\Batch_Process\Batch_Migrate_Affiliate_User_Meta',
				'file'  => AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/class-batch-migrate-affwp-user-meta.php',
			),
		) );

		$this->add_routine( 'upgrade_v281_convert_failed_referrals', array(
			'version'       => '2.8.1',
			'compare'       => '<',
			'batch_process' => array(
				'id'    => 'upgrade-convert-failed-referrals',
				'class' => 'AffWP\Utils\Batch_Process\Batch_Upgrade_Convert_Failed_Referrals',
				'file'  => AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/upgrades/class-batch-upgrade-convert-failed-referrals.php',
			),
		) );

		$this->add_routine( 'upgrade_v2140_set_creative_type', array(
			'version'       => '2.14.0',
			'compare'       => '<',
			'batch_process' => array(
				'id'    => 'set-creative-type',
				'class' => 'AffWP\Utils\Batch_Process\Batch_Set_Creative_Type',
				'file'  => AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/class-batch-set-creative-type.php',
			),
		) );

		$this->add_routine( 'upgrade_v2160_update_creative_names', array(
			'version'       => '2.16.0',
			'compare'       => '<',
			'batch_process' => array(
				'id'    => 'update-creative-names',
				'class' => 'AffWP\Utils\Batch_Process\Batch_Update_Creative_Names',
				'file'  => AFFILIATEWP_PLUGIN_DIR . 'includes/admin/tools/class-batch-update-creative-names.php',
			),
		) );
	}

	/**
	 * Registers a new upgrade routine.
	 *
	 * @access public
	 * @since  2.0.5
	 *
	 * @param string $upgrade_id Upgrade ID.
	 * @param array  $args {
	 *     Arguments for registering a new upgrade routine.
	 *
	 *     @type string $version       Version the upgrade routine should be run against.
	 *     @type string $compare       Comparison operator to use when determining if the routine
	 *                                 should be executed.
	 *     @type array  $batch_process {
	 *         Optional. Arguments for registering a batch process.
	 *
	 *         @type string $id    Batch process ID.
	 *         @type string $class Batch processor class to use.
	 *         @type string $file  File containing the batch processor class.
	 *     }
	 * }
	 * @return bool True if the upgrade routine was added, otherwise false.
	 */
	public function add_routine( $upgrade_id, $args ) {
		// Register the batch process if one has been defined.
		if ( ! empty( $args['batch_process'] ) ) {

			$utils = $this->utils;
			$batch = $args['batch_process'];

			// Log an error if it's too late to register the batch process.
			if ( did_action( 'affwp_batch_process_init' ) ) {

				$utils->log( sprintf( 'The %s batch process was registered too late. Registrations must occur while/before <code>affwp_batch_process_init</code> fires.',
					esc_html( $args['batch_process']['id'] )
				) );

				return false;

			} else {

				add_action( 'affwp_batch_process_init', function() use ( $utils, $batch ) {
					$utils->batch->register_process( $batch['id'], array(
						'class' => $batch['class'],
						'file'  => $batch['file'],
					) );
				} );

			}

			unset( $args['batch_process'] );
		}

		// Add the routine to the registry.
		return $this->registry->add_upgrade( $upgrade_id, $args );
	}

	/**
	 * Retrieves an upgrade routine from the registry.
	 *
	 * @access public
	 * @since  2.0.5
	 *
	 * @param string $upgrade_id Upgrade ID.
	 * @return array|false Upgrade entry from the registry, otherwise false.
	 */
	public function get_routine( $upgrade_id ) {
		return $this->registry->get( $upgrade_id );
	}

	/**
	 * Writes a log message.
	 *
	 * @access private
	 * @since 1.8.6
	 *
	 * @param string $message Optional. Message to log.
	 */
	private function log( $message = '' ) {
		$this->utils->log( $message );
	}

	/**
	 * Perform database upgrades for version 1.1
	 *
	 * @access  private
	 * @since   1.1
	*/
	private function v11_upgrades() {

		@affiliate_wp()->affiliates->create_table();

		$this->upgraded = true;

	}

	/**
	 * Perform database upgrades for version 1.2.1
	 *
	 * @access  private
	 * @since   1.2.1
	*/
	private function v121_upgrades() {

		@affiliate_wp()->creatives->create_table();

		$this->upgraded = true;

	}

	/**
	 * Perform database upgrades for version 1.3
	 *
	 * @access  private
	 * @since   1.3
	 */
	private function v13_upgrades() {

		@affiliate_wp()->creatives->create_table();

		// Clear rewrite rules
		flush_rewrite_rules();

		$this->upgraded = true;

	}

	/**
	 * Perform database upgrades for version 1.6
	 *
	 * @access  private
	 * @since   1.6
	 */
	private function v16_upgrades() {

		@affiliate_wp()->affiliate_meta->create_table();
		@affiliate_wp()->referrals->create_table();

		$this->upgraded = true;

	}

	/**
	 * Perform database upgrades for version 1.7
	 *
	 * @access  private
	 * @since   1.7
	 */
	private function v17_upgrades() {

		@affiliate_wp()->referrals->create_table();
		@affiliate_wp()->visits->create_table();
		@affiliate_wp()->campaigns->create_view();

		$this->v17_upgrade_referral_rates();

		$this->v17_upgrade_gforms();

		$this->v17_upgrade_nforms();

		$this->upgraded = true;

	}

	/**
	 * Perform database upgrades for version 1.7.3
	 *
	 * @access  private
	 * @since   1.7.3
	 */
	private function v173_upgrades() {

		$this->v17_upgrade_referral_rates();

		$this->upgraded = true;

	}

	/**
	 * Perform database upgrades for referral rates in version 1.7
	 *
	 * @access  private
	 * @since   1.7
	 */
	private function v17_upgrade_referral_rates() {

		global $wpdb;

		$prefix  = ( defined( 'AFFILIATE_WP_NETWORK_WIDE' ) && AFFILIATE_WP_NETWORK_WIDE ) ? null : $wpdb->prefix;
		$results = $wpdb->get_results( "SELECT affiliate_id, rate FROM {$prefix}affiliate_wp_affiliates WHERE rate_type = 'percentage' AND rate > 0 AND rate <= 1;" );

		if ( $results ) {
			foreach ( $results as $result ) {
				$wpdb->update(
					"{$prefix}affiliate_wp_affiliates",
					array( 'rate' => floatval( $result->rate ) * 100 ),
					array( 'affiliate_id' => $result->affiliate_id ),
					array( '%d' ),
					array( '%d' )
				);
			}
		}

		$settings  = get_option( 'affwp_settings' );
		$rate_type = ! empty( $settings['referral_rate_type'] ) ? $settings['referral_rate_type'] : null;
		$rate      = isset( $settings['referral_rate'] ) ? $settings['referral_rate'] : 20;

		if ( 'percentage' !== $rate_type ) {
			return;
		}

		if ( $rate > 0 && $rate <= 1 ) {
			$settings['referral_rate'] = floatval( $rate ) * 100;
		} elseif ( '' === $rate || '0' === $rate || '0.00' === $rate ) {
			$settings['referral_rate'] = 0;
		} else {
			$settings['referral_rate'] = floatval( $rate );
		}

		// Update settings.
		affiliate_wp()->settings->set( $settings, $save = true );
	}

	/**
	 * Perform database upgrades for Gravity Forms in version 1.7
	 *
	 * @access  private
	 * @since   1.7
	 */
	private function v17_upgrade_gforms() {

		$settings = get_option( 'affwp_settings' );

		if ( empty( $settings['integrations'] ) || ! array_key_exists( 'gravityforms', $settings['integrations'] ) ) {
			return;
		}

		global $wpdb;

		$tables = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}rg_form%';" );

		if ( ! $tables ) {
			return;
		}

		$forms = $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}rg_form;" );

		if ( ! $forms ) {
			return;
		}

		foreach ( $forms as $form ) {

			$meta = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT display_meta FROM {$wpdb->prefix}rg_form_meta WHERE form_id = %d;",
					$form->id
				)
			);

			$meta = json_decode( $meta );

			if ( isset( $meta->gform_allow_referrals ) ) {
				continue;
			}

			$meta->gform_allow_referrals = 1;

			$meta = json_encode( $meta );

			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$wpdb->prefix}rg_form_meta SET display_meta = %s WHERE form_id = %d;",
					$meta,
					$form->id
				)
			);

		}

	}

	/**
	 * Perform database upgrades for Ninja Forms in version 1.7
	 *
	 * @access  private
	 * @since   1.7
	 */
	private function v17_upgrade_nforms() {

		$settings = get_option( 'affwp_settings' );

		if ( empty( $settings['integrations'] ) || ! array_key_exists( 'ninja-forms', $settings['integrations'] ) ) {
			return;
		}

		global $wpdb;

		$tables = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}nf_object%';" );

		if ( ! $tables ) {
			return;
		}

		$forms = $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}nf_objects WHERE type = 'form';" );

		if ( ! $forms ) {
			return;
		}

		// There could be forms that already have this meta saved in the DB, we will ignore those
		$_forms = $wpdb->get_results( "SELECT object_id FROM {$wpdb->prefix}nf_objectmeta WHERE meta_key = 'affwp_allow_referrals';" );

		$forms  = wp_list_pluck( $forms, 'id' );
		$_forms = wp_list_pluck( $_forms, 'object_id' );
		$forms  = array_diff( $forms, $_forms );

		if ( ! $forms ) {
			return;
		}

		foreach ( $forms as $form_id ) {

			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO {$wpdb->prefix}nf_objectmeta (object_id,meta_key,meta_value) VALUES (%d,'affwp_allow_referrals','1');",
					$form_id
				)
			);

		}

	}

	/**
	 * Perform database upgrades for version 1.7.11
	 *
	 * @access  private
	 * @since   1.7.11
	 */
	private function v1711_upgrades() {

		$settings = affiliate_wp()->settings->get_all();

		// Ensures settings are not lost if the duplicate email/subject fields were used before they were removed
		if( ! empty( $settings['rejected_email'] ) && empty( $settings['rejection_email'] ) ) {
			$settings['rejection_email'] = $settings['rejected_email'];
			unset( $settings['rejected_email'] );
		}

		if( ! empty( $settings['rejected_subject'] ) && empty( $settings['rejection_subject'] ) ) {
			$settings['rejection_subject'] = $settings['rejected_subject'];
			unset( $settings['rejected_subject'] );
		}

		// Update settings.
		affiliate_wp()->settings->set( $settings, $save = true );

		$this->upgraded = true;

	}

	/**
	 * Perform database upgrades for version 1.7.14
	 *
	 * @access  private
	 * @since   1.7.14
	 */
	private function v1714_upgrades() {

		@affiliate_wp()->visits->create_table();

		$this->upgraded = true;

	}

	/**
	 * Performs database upgrades for version 1.9.
	 *
	 * @since 1.9
	 * @access private
	 */
	private function v19_upgrade() {
		@affiliate_wp()->referrals->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The Referrals table upgrade for 1.9 has completed.' );

		@affiliate_wp()->affiliates->payouts->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The Payouts table creation process for 1.9 has completed.' );

		@affiliate_wp()->REST->consumers->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The API consumers table creation process for 1.9 has completed' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 1.9.5.
	 *
	 * @since 1.9.5
	 * @access private
	 */
	private function v195_upgrade() {
		@affiliate_wp()->affiliates->payouts->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The Payouts table upgrade for 1.9.5 has completed.' );

		wp_cache_set( 'last_changed', microtime(), 'payouts' );
		@affiliate_wp()->utils->log( 'Upgrade: The Payouts cache has been invalidated following the 1.9.5 upgrade routine.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.0.
	 *
	 * @since 2.0
	 * @access private
	 */
	private function v20_upgrade() {
		// New primitive and meta capabilities.
		@affiliate_wp()->capabilities->add_caps();
		@affiliate_wp()->utils->log( 'Upgrade: Core capabilities have been upgraded.' );


		// Update settings
		@affiliate_wp()->settings->set( array(
			'required_registration_fields' => array(
				'your_name'   => __( 'Your Name', 'affiliate-wp' ),
				'website_url' => __( 'Website URL', 'affiliate-wp' )
			)
		), $save = true );
		@affiliate_wp()->utils->log( 'Upgrade: The default required registration field settings have been configured.' );

		// Affiliate schema update.
		@affiliate_wp()->affiliates->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The unpaid_earnings column has been added to the affiliates table.' );

		wp_cache_set( 'last_changed', microtime(), 'affiliates' );
		@affiliate_wp()->utils->log( 'Upgrade: The Affiliates cache has been invalidated following the 2.0 upgrade.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.0.2.
	 *
	 * @since 2.0.2
	 * @access private
	 */
	private function v202_upgrade() {
		// New 'context' column for visits.
		@affiliate_wp()->visits->create_table();
		$this->log( 'Upgrade: The context column has been added to the Visits table.' );

		wp_cache_set( 'last_changed', microtime(), 'visits' );
		$this->log( 'Upgrade: The Visits cache has been invalidated following the 2.0.2 upgrade.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.0.10.
	 *
	 * @since 2.0.10
	 * @access private
	 */
	private function v210_upgrade() {
		update_option( 'affwp_flush_rewrites', '1' );
		@affiliate_wp()->utils->log( 'Upgrade: AffiliateWP rewrite rules have been flushed following the 2.0.10 upgrade.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.1.
	 *
	 * @access private
	 * @since  2.1
	 */
	private function v21_upgrade() {
		// Schedule a rewrites flush.
		flush_rewrite_rules();
		$this->log( 'Upgrade: Rewrite rules flushed following the 2.1 upgrade.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.1.3.1.
	 *
	 * @access private
	 * @since  2.1.3.1
	 */
	private function v2131_upgrade() {
		// Refresh capabilities missed in 2.1 update (export_visit_data).
		@affiliate_wp()->capabilities->add_caps();
		@affiliate_wp()->utils->log( 'Upgrade: Core capabilities have been upgraded for 2.1.3.1.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.2.
	 *
	 * @access private
	 * @since  2.2
	 */
	private function v22_upgrade() {

		global $wpdb;

		// Add type column to referrals database.
		@affiliate_wp()->referrals->create_table();
		$table = affiliate_wp()->referrals->table_name;
		$wpdb->query( "UPDATE $table SET type = 'sale' where type IS NULL;" );
		@affiliate_wp()->utils->log( 'Upgrade: Referrals table has been upgraded for 2.2.' );

		// New 'customer_id' column for referrals.
		@affiliate_wp()->referrals->create_table();
		@affiliate_wp()->capabilities->add_caps();
		@affiliate_wp()->customers->create_table();
		affiliate_wp()->utils->log( 'Upgrade: The customers table has been created.' );
		@affiliate_wp()->customer_meta->create_table();
		affiliate_wp()->utils->log( 'Upgrade: The customer meta table has been created.' );

		// Update email settings
		$registration_notifications   = 'registration_notifications';
		$admin_referral_notifications = 'admin_referral_notifications';
		$disable_all_emails           = 'disable_all_emails';

		/**
		 * Enable all email notifications by default.
		 * Fresh installations of AffiliateWP and upgrades should enable all notifications.
		 */
		$email_notifications = affiliate_wp()->settings->email_notifications( true );

		/**
		 * If "Disable All Emails" checkbox option was previously enabled,
		 * clear out the email notification array, essentially disabling all notifications.
		 */
		if ( affiliate_wp()->settings->get( $disable_all_emails ) ) {
			$email_notifications = array();
		}

		// Enable the new admin affiliate registration email if it was previously enabled.
		if ( affiliate_wp()->settings->get( $registration_notifications ) ) {
			$email_notifications['admin_affiliate_registration_email'] = __( 'Notify site admin when a new affiliate has registered', 'affiliate-wp' );
		} else {
			// Uncheck the new admin affiliate registration email if it was previously unchecked.
			unset( $email_notifications['admin_affiliate_registration_email'] );
		}

		// Enable the new admin referral notification email if it was previously enabled.
		if ( affiliate_wp()->settings->get( $admin_referral_notifications ) ) {
			$email_notifications['admin_new_referral_email'] = __( 'Notify site admin when new referrals are earned', 'affiliate-wp' );
		} else {
			// Uncheck the new admin referral notification email if it was previously unchecked.
			unset( $email_notifications['admin_new_referral_email'] );
		}

		// Make the required changes to the Email Notifications.
		@affiliate_wp()->settings->set( array(
			'email_notifications' => $email_notifications
		), $save = true );

		// Get all settings.
		$settings = affiliate_wp()->settings->get_all();

		// Remove old "Disable All Emails" setting.
		if ( isset( $settings[$disable_all_emails] ) ) {
			unset( $settings[$disable_all_emails] );
		}

		// Remove old "Notify Admin" setting.
		if ( isset( $settings[$registration_notifications] ) ) {
			unset( $settings[$registration_notifications] );
		}

		// Remove old "Notify Admin of Referrals" setting.
		if ( isset( $settings[$admin_referral_notifications] ) ) {
			unset( $settings[$admin_referral_notifications] );
		}

		// Update affwp_settings option.
		update_option( 'affwp_settings', $settings );

		$this->upgraded = true;

	}

	/**
	 * Performs database upgrades for version 2.2.2.
	 *
	 * @since 2.2.2
	 */
	private function v222_upgrade() {
		foreach ( $this->get_sites_for_upgrade() as $site_id ) {

			if( is_multisite() ) {
				switch_to_blog( $site_id );
			}

			affiliate_wp()->affiliates->create_table();
			@affiliate_wp()->utils->log( sprintf( 'Upgrade: The rest_id column has been added to the Affiliates table for site #%1$s.', $site_id ) );

			affiliate_wp()->referrals->create_table();
			@affiliate_wp()->utils->log( sprintf( 'Upgrade: The rest_id column has been added to the Referrals table for site #%1$s.', $site_id ) );

			affiliate_wp()->REST->consumers->create_table();
			@affiliate_wp()->utils->log( sprintf( 'Upgrade: The status and date columns have been added to the REST Consumers table for site #%1$s.', $site_id ) );

			affiliate_wp()->visits->create_table();
			@affiliate_wp()->utils->log( sprintf( 'Upgrade: The rest_id column has been added to the Visits table for site #%1$s.', $site_id ) );

			// Populate the date and status columns for existing consumers.
			$consumers = affiliate_wp()->REST->consumers->get_consumers( array(
				'number' => -1
			) );

			if ( ! empty( $consumers ) ) {
				$date = get_post_field( 'post_date', affwp_get_affiliate_area_page_id() );

				if ( empty( $date ) ) {
					$date = gmdate( 'Y-m-d H:i:s' );
				} else {
					$date = gmdate( 'Y-m-d H:i:s', strtotime( $date ) );
				}

				foreach ( $consumers as $consumer ) {

					affiliate_wp()->REST->consumers->update( $consumer->ID, array(
						'date'   => $date,
						'status' => 'active'
					) );
				}
			}

			if( is_multisite() ) {
				restore_current_blog();
			}
		}

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.2.8.
	 *
	 * @since 2.2.8
	 */
	private function v228_upgrade() {
		affiliate_wp()->referrals->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The length of the campaign column in the Referrals table has been changed to 50 characters.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.2.9.
	 *
	 * @since 2.2.9
	 */
	private function v229_upgrade() {
		affiliate_wp()->referrals->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The parent_id column has been added to the Referrals table.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.3.
	 *
	 * @since 2.3
	 */
	private function v23_upgrade() {
		// Adds the flat rate basis column.
		affiliate_wp()->affiliates->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: the flat_rate_basis column has been added to the Affiliates table.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.4.
	 *
	 * @since 2.4
	 */
	private function v24_upgrade() {
		// New 'service_account, service_id, service_invoice_link and description' columns for payouts.
		affiliate_wp()->affiliates->payouts->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The service_account, service_id, service_invoice_link and description columns have been added to the Payouts table.' );

		wp_cache_set( 'last_changed', microtime(), 'payouts' );
		@affiliate_wp()->utils->log( 'Upgrade: The Payouts cache has been invalidated following the 2.4 upgrade.' );

		// Adds the referral meta table.
		affiliate_wp()->referral_meta->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The referral meta table has been created.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.4.2.
	 *
	 * @since 2.4.2
	 */
	private function v242_upgrade() {
		// Flush rewrites for the benefit of the EDD integration.
		flush_rewrite_rules();
		@affiliate_wp()->utils->log( 'Upgrade: Rewrite rules flushed.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.5.
	 *
	 * @since 2.5
	 */
	private function v25_upgrade() {
		affiliate_wp()->referrals->sales->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The sales table has been created.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for version 2.6.
	 *
	 * @since 2.6
	 */
	private function v26_upgrade() {
		affiliate_wp()->affiliates->coupons->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The coupons table has been created.' );

		// Enable the affiliate coupons setting (will not cause unexpected behavior).
		@affiliate_wp()->settings->set( array(
			'affiliate_coupons'  => true,
		), $save = true );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for 2.7
	 *
	 * @since 2.7
	 */
	private function v27_upgrade() {
		global $wpdb;

		$dropped = $wpdb->query( "DROP VIEW IF EXISTS {$wpdb->prefix}affiliate_wp_campaigns" );

		if ( true === $dropped ) {
			@affiliate_wp()->utils->log( 'Upgrade: The campaigns view has been dropped.' );
		} else {
			@affiliate_wp()->utils->log( 'Upgrade: The campaigns view was not dropped.', $wpdb->last_error );
		}

		@affiliate_wp()->campaigns->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The campaigns table has been created.' );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for 2.7.4
	 *
	 * @since 2.7.4
	 */
	private function v274_upgrade() {
		$upload_dir = wp_upload_dir( null, false );
		$base_dir   = isset( $upload_dir['basedir'] ) ? $upload_dir['basedir'] : ABSPATH;

		$old_file = trailingslashit( $base_dir ) . 'affwp-debug.log';

		if ( file_exists( $old_file ) && is_writeable( $old_file ) && is_writeable( $base_dir ) ) {
			$hash     = affwp_get_hash( $upload_dir, defined( 'AUTH_SALT' ) ? AUTH_SALT : '' );
			$new_file = trailingslashit( $base_dir ) . sprintf( 'affwp-debug-log__%s.log', $hash );
			@rename( $old_file, $new_file );
		}

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for 2.8.
	 *
	 * @since 2.8
	 */
	private function v28_upgrade() {
		global $wpdb;

		$table_name = affiliate_wp()->affiliates->coupons->table_name;

		// Update the length of the coupon_code column to 191 characters.
		affiliate_wp()->affiliates->coupons->create_table();

		affiliate_wp()->utils->log( 'Upgrade: The coupons table has been updated to support lengthier coupon codes and types.' );

		// Set default coupon format and hyphen delimeter.
		$coupons_settings = array(
		    'coupon_format'           => '{coupon_code}',
		    'coupon_hyphen_delimiter' => 1,
		);

		affiliate_wp()->settings->set( $coupons_settings, $save = true );

		$this->upgraded = true;
	}

	/**
	 * Performs database upgrades for 2.9.
	 *
	 * @since 2.9
	 */
	private function v29_upgrade() {
		global $wpdb;

		$table_name = affiliate_wp()->affiliates->coupons->table_name;

		// Add the 'locked' column.
		affiliate_wp()->affiliates->coupons->create_table();

		affiliate_wp()->utils->log( 'Upgrade: The locked column has been added to the coupons table.' );

		// Update type field of existing coupons.
		$old_type = '';
		$new_type = 'dynamic';

		$wpdb->query(
				$wpdb->prepare(
					"UPDATE $table_name SET type = %s where type = %s;",
					$new_type,
					$old_type
				)
			);

		affiliate_wp()->utils->log( 'Upgrade: All dynamic coupons now have a "dynamic" type in the coupons table.' );

		wp_cache_set( 'last_changed', microtime(), 'coupons' );

		$this->upgraded = true;
	}

	/**
	 * Perform database upgrades for version 2.9.5
	 *
	 * @access  private
	 * @since   2.9.5
	*/
	private function v295_upgrade() {
		affiliate_wp()->notifications->create_table();
		affiliate_wp()->utils->log( 'Upgrade: The in-plugin notifications table has been created.' );
		$this->upgraded = true;
	}

	/**
	 * Perform database upgrades for version 2.9.6
	 *
	 * @access  private
	 * @since   2.9.6
	 */
	private function v296_upgrade() {

		affiliate_wp()->referrals->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The flag column has been added to the referrals table.' );

		affiliate_wp()->visits->create_table();
		@affiliate_wp()->utils->log( 'Upgrade: The flag column has been added to the visits table.' );

		$this->upgraded = true;
	}

	/**
	 * Perform database upgrades for version 2.9.6.1
	 *
	 * @access  private
	 * @since   2.9.6.1
	 */
	private function v2961_upgrade() {

		$this->fix_296_action_scheduler_issue();

		$this->upgraded = true;
	}

	/**
	 * Fix scheduler issues in 2.9.6
	 *
	 * Ensure that for the Action Scheduler actions
	 * affwp_daily_scheduled_events, and affwp_monthly_email_summaries
	 * that we make sure there are only one of each of these.
	 *
	 * In 2.9.6 we had an issue where many of these were created, when we only need one
	 * pending action for each of these.
	 *
	 * @since  2.9.6.1
	 * @access private
	 *
	 * @return void Early bail if there's just one scheduled (no duplicates).
	 */
	private function fix_296_action_scheduler_issue() {

		if ( ! function_exists( 'as_get_scheduled_actions' ) ) {
			return; // We can't fix it.
		}

		foreach ( array(
			'affwp_monthly_email_summaries',
			'affwp_daily_scheduled_events',
		) as $action ) {

			if ( count(
				// Get all schedule actions (there may be many) for $action.
				as_get_scheduled_actions(
					array(
						'hook'     => $action,
						'group'    => 'affiliatewp',
						'status'   => ActionScheduler_Store::STATUS_PENDING,
						'per_page' => -1,
					)
				)
			) <= 1 ) {

				// We only have one scheduled hook for $action, that's correct.
				continue;
			}

			// Remove them all, there should only be one.
			as_unschedule_all_actions( $action, array(), 'affiliatewp' );

			// Tell the scheduler not to schedule an email summary for now.
			if ( 'affwp_monthly_email_summaries' === $action ) {
				update_option( 'affwp_email_summary_now', 'no' );
			}
		}
	}

	/**
	 * Perform database upgrades for version 2.11.0
	 *
	 * @access  private
	 * @since   2.11.0
	 */
	private function v2110_upgrade() {

		affiliate_wp()->creatives->create_table();

		@affiliate_wp()->utils->log( 'Upgrade: The attachment_id column has been added to the creatives table.' );

		$this->upgraded = true;
	}

	/**
	 * Perform database upgrades for version 2.14.0.
	 *
	 * @access  private
	 * @since   2.14.0
	 */
	private function v2140_upgrade() {

		affiliate_wp()->creatives->create_table();

		@affiliate_wp()->utils->log( 'Upgrade: The type and date_updated columns has been added to the creatives table.' );

		affiliate_wp()->custom_links->create_table();

		@affiliate_wp()->utils->log( 'Upgrade: The custom_links table was created.' );

		$this->upgraded = true;
	}

	/**
	 * Perform database upgrades for version 2.15.0.
	 *
	 * @access  private
	 * @since   2.15.0
	 */
	private function v2150_upgrade() {

		affiliate_wp()->creatives->create_table();

		@affiliate_wp()->utils->log( 'Upgrade: The start_date and end_date columns have been added to the creatives table.' );

		$this->upgraded = true;
	}

	/**
	 * Perform database upgrades for version 2.16.0.
	 *
	 * @access  private
	 * @since   2.16.0
	 */
	private function v2160_upgrade() {

		affiliate_wp()->creatives->create_table();

		@affiliate_wp()->utils->log( 'Upgrade: The notes column has been added to the creatives table.' );

		// Ensure this will never be overridden.
		if ( ! in_array( get_option( 'affwp_creative_name_privacy', '' ), array( 'pending', 'private', 'public' ), true ) ) {

			update_option( 'affwp_creative_name_upgrade_date', gmdate( 'Y-m-d H:i:s' ) );

			$creatives = affiliate_wp()->creatives->count();

			update_option(
				'affwp_creative_name_privacy',
				empty( $creatives )
					? 'public'
					: 'pending'
			);

		}

		$this->upgraded = true;
	}

	/**
	 * Perform database upgrades for version 2.16.3.
	 *
	 * @access  private
	 * @since   2.16.3
	 */
	private function v2163_upgrade() {
		require_once AFFILIATEWP_PLUGIN_DIR . 'includes/admin/class-usage.php';

		$usage_tracking = new Affiliate_WP_Usage_Tracking();

		// Track first registered affiliate.
		$usage_tracking->track_first_affiliate();

		// Track first referral.
		$usage_tracking->track_first_referral();

		// Track first payout.
		$usage_tracking->track_first_payout();

		// Track first creative.
		$usage_tracking->track_first_creative( 0, array() );

		/**
		 * Installs before v2.10.0 won't have the affwp_first_installed option row.
		 * If it doesn't exist, create it based on the post date of the current Affiliate Area page.
		 */
		if ( ! get_option( 'affwp_first_installed' ) ) {
			add_option( 'affwp_first_installed', strtotime( get_post_field( 'post_date', affwp_get_affiliate_area_page_id() ) ), '', 'no' );
		}

		// Remove older affwp_last_checkin option row.
		if ( get_option( 'affwp_last_checkin' ) ) {
			delete_option( 'affwp_last_checkin' );
		}

		$this->upgraded = true;
	}

	/**
	 * Perform database upgrades for version 2.17.0.
	 *
	 * @since 2.17.0
	 */
	private function v2170_upgrade() {

		affiliate_wp()->creative_meta->create_table();

		@affiliate_wp()->utils->log( 'Upgrade: The creativemeta table was created.' );

		$this->upgraded = true;
	}

	/**
	 * Perform database upgrades for version 2.18.0.
	 *
	 * @since 2.18.0
	 */
	private function v2180_upgrade() {
		// Get all settings.
		$settings = affiliate_wp()->settings->get_all();

		// User has Auto Register New Users option enabled.
		if ( ! empty( $settings['auto_register'] ) ) {

			// Enable our new setting under "Additional Registration Modes".
			$settings['additional_registration_modes'] = 'auto_register_new_users';

			// Unset the old setting.
			unset( $settings['auto_register'] );

			// Update the affwp_settings option.
			update_option( 'affwp_settings', $settings );
		}

		$this->upgraded = true;
	}

	/**
	 * Retrieves the site IDs array.
	 *
	 * Most commonly used for db schema changes in networks (but also works for single site).
	 *
	 * @return array Site IDs in the network (single or multisite).
	 */
	private function get_sites_for_upgrade() {
		if ( is_multisite() ) {

			if ( true === version_compare( $GLOBALS['wp_version'], '4.6', '<' ) ) {

				$sites = wp_list_pluck( 'blog_id', wp_get_sites() );

			} else {

				$sites = get_sites( array( 'fields' => 'ids' ) );

			}

		} else {

			$sites = array( get_current_blog_id() );

		}

		$plugin = AFFILIATEWP_PLUGIN_DIR_NAME . '/affiliate-wp.php';

		// Only return sites AffWP is active on.
		foreach ( $sites as $index => $site_id ) {


			if( is_multisite() ) {

				switch_to_blog( $site_id );

			}

			if ( ! in_array( $plugin, get_option( 'active_plugins', array() ) ) ) {
				unset( $sites[ $index ] );
			}

			if( is_multisite() ) {

				restore_current_blog();

			}

		}
		return $sites;
	}

}
