<?php

/**
 * Class FSLM_Settings
 */
class FSLM_Settings {

	/**
	 * FSLM_Settings constructor.
	 */
	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'load_license_manager_admin_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_plugin_menu_and_pages' ) );
		if ( FS_WC_licenses_Manager::is_active() ) {
			add_action( 'admin_init', array( $this, 'page_init' ) );
		}

	}

	/**
	 * Plugin menu
	 */
	public function add_plugin_menu_and_pages() {

		$user_capability = fslm_vendors_permission() ? 'manage_product' : 'manage_woocommerce';

		if ( FS_WC_licenses_Manager::is_active() ) {

			add_menu_page(
				__( 'License Manager', 'fslm' ),
				__( 'License Manager', 'fslm' ),
				$user_capability,
				'license-manager',
				array(
					$this,
					'admin_page_callback'
				),
				'dashicons-lock',
				55
			);

			add_submenu_page(
				'license-manager',
				__( 'License Key Generator', 'fslm' ),
				__( 'License Key Generator', 'fslm' ),
				$user_capability,
				'license-manager-license-key-generator',
				array(
					$this,
					'license_key_generator_page_callback'
				)
			);

			add_submenu_page(
				'license-manager',
				__( 'Add License Key', 'fslm' ),
				__( 'Add License Key', 'fslm' ),
				$user_capability,
				'license-manager-add-license-key',
				array(
					$this,
					'add_license_key_page_callback'
				)
			);

			add_submenu_page(
				'license-manager',
				__( 'Add Generator Rule', 'fslm' ),
				__( 'Add Generator Rule', 'fslm' ),
				$user_capability,
				'license-manager-license-generator',
				array(
					$this,
					'add_license_key_generator_rule_page_callback'
				)
			);

			add_submenu_page(
				'license-manager'
				, __( 'Settings', 'fslm' ),
				__( 'Settings', 'fslm' ),
				$user_capability,
				'license-manager-settings',
				array(
					$this,
					'settings_page_callback'
				)
			);

			add_submenu_page(
				'license-manager',
				__( 'Import', 'fslm' ),
				__( 'Import', 'fslm' ),
				$user_capability,
				'license-manager-import',
				array(
					$this,
					'import_page_callback'
				)
			);

			add_submenu_page(
				'license-manager',
				__( 'Export', 'fslm' ),
				__( 'Export', 'fslm' ),
				$user_capability,
				'license-manager-export',
				array(
					$this,
					'export_page_callback'
				)
			);

			add_submenu_page(
				'license-manager',
				__( 'License And Support', 'fslm' ),
				__( 'License And Support', 'fslm' ),
				$user_capability,
				'license-manager-support',
				array(
					$this,
					'support_page_callback'
				)
			);

			add_submenu_page(
				'license-manager',
				esc_html__( 'Extensions', 'fslm' ),
				esc_html__( 'Extensions', 'fslm' ),
				$user_capability,
				'license-manager-extensions',
				array(
					$this,
					'extensions_page_callback'
				)
			);

			add_submenu_page(
				'wclm-pages',
				__( 'Welcome', 'fslm' ),
				__( 'Welcome', 'fslm' ),
				$user_capability,
				'license-manager-welcome',
				array(
					$this,
					'welcome_page'
				)
			);

			do_action( 'wclm_after_admin_menu_added' );

		} else {

			add_menu_page(
				__( 'License Manager', 'fslm' ),
				__( 'License Manager', 'fslm' ),
				$user_capability,
				'license-manager-welcome',
				array(
					$this,
					'welcome_page'
				),
				'dashicons-lock',
				55
			);

		}

	}


	/**
	 * Plugin menu page callback
	 */
	public function welcome_page() {
		require( FSLM_PLUGIN_BASE . "includes/welcome.php" );
	}

	/**
	 * Plugin menu page callback
	 */
	public function admin_page_callback() {
		require( FSLM_PLUGIN_BASE . "includes/license_manager.php" );
	}

	/**
	 * Plugin menu page callback
	 */
	public function add_license_key_page_callback() {
		require( FSLM_PLUGIN_BASE . "includes/add_license_key.php" );
	}

	/**
	 * Plugin menu page callback
	 */
	public function license_key_generator_page_callback() {
		require( FSLM_PLUGIN_BASE . "includes/license_key_generator.php" );
	}

	/**
	 * Plugin menu page callback
	 */
	public function add_license_key_generator_rule_page_callback() {
		require( FSLM_PLUGIN_BASE . "includes/add_generator_rule.php" );
	}

	/**
	 * Plugin menu page callback
	 */
	public function settings_page_callback() {
		require( FSLM_PLUGIN_BASE . "includes/pages/admin/settings/settings.php" );
	}

	/**
	 * Plugin menu page callback
	 */
	public function import_page_callback() {
		require( FSLM_PLUGIN_BASE . "includes/lm_import.php" );
	}

	/**
	 * Plugin menu page callback
	 */
	public function export_page_callback() {
		require( FSLM_PLUGIN_BASE . "includes/lm_export.php" );
	}

	/**
	 * Plugin menu page callback
	 */
	public function support_page_callback() {
		require( FSLM_PLUGIN_BASE . "includes/support.php" );
	}

	/**
	 * Plugin menu page callback
	 */
	public function extensions_page_callback() {
		require( FSLM_PLUGIN_BASE . "includes/extensions.php" );
	}

	/**
	 * Plugin init
	 */
	public function page_init() {

		/////////////////////////


		$order_statuses = (array) FS_WC_licenses_Manager::get_terms( 'shop_order_status',
			array( 'hide_empty' => 0, 'orderby' => 'id' ) );

		if ( $order_statuses && ! is_wp_error( $order_statuses ) ) {
			foreach ( $order_statuses as $s ) {

				if ( defined( "WOOCOMMERCE_VERSION" ) && version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {

					$s->slug = str_replace( 'wc-', '', $s->slug );

				}

				register_setting( 'fslm_order_status_option_group', 'fslm_send_when_' . $s->slug );
				register_setting( 'fslm_order_status_option_group', 'fslm_revoke_when_' . $s->slug );
				register_setting( 'fslm_order_status_option_group', 'fslm_hide_when_' . $s->slug );

			}
		}


		/////////////////////////

		register_setting( 'fslm_email_template_option_group', 'fslm_mail_heading' );
		register_setting( 'fslm_email_template_option_group', 'fslm_mail_subject' );
		register_setting( 'fslm_email_template_option_group', 'fslm_mail_message' );
		register_setting( 'fslm_email_template_option_group', 'fslm_add_wc_header_and_footer' );
		register_setting( 'fslm_email_template_option_group', 'fslm_add_lk_wc_de' );
		register_setting( 'fslm_email_template_option_group', 'fslm_add_lk_se' );
		register_setting( 'fslm_email_template_option_group', 'fslm_license_keys_page_url' );
		register_setting( 'fslm_email_template_option_group', 'wclm_defer_sending_woocommerce_emails' );

		register_setting( 'fslm_general_option_group', 'fslm_show_adminbar_notifs' );
		register_setting( 'fslm_general_option_group', 'fslm_guest_customer' );
		register_setting( 'fslm_general_option_group', 'fslm_show_available_license_keys_column' );
		register_setting( 'fslm_general_option_group', 'fslm_show_missing_license_keys_column' );
		register_setting( 'fslm_general_option_group', 'fslm_hide_keys_on_site' );
		register_setting( 'fslm_general_option_group', 'fslm_enable_cart_validation' );
		register_setting( 'fslm_general_option_group', 'fslm_nb_rows_by_page' );
		register_setting( 'fslm_general_option_group', 'fslm_nb_rows_by_page_filter' );
		register_setting( 'fslm_general_option_group', 'fslm_meta_key_name' );
		register_setting( 'fslm_general_option_group', 'fslm_show_on_top' );
		register_setting( 'fslm_general_option_group', 'fslm_generator_chars' );
		register_setting( 'fslm_general_option_group', 'fslm_auto_expire' );
		register_setting( 'fslm_general_option_group', 'fslm_auto_redeem' );
		register_setting( 'fslm_general_option_group', 'fslm_redeem_btn' );
		register_setting( 'fslm_general_option_group', 'fslm_stock_sync' );
		register_setting( 'fslm_general_option_group', 'fslm_delete_keys' );
		register_setting( 'fslm_general_option_group', 'fslm_delete_keys_after_x_days' );
		register_setting( 'fslm_general_option_group', 'fslm_number_of_days' );
		register_setting( 'fslm_general_option_group', 'fslm_queue_system' );
		register_setting( 'fslm_general_option_group', 'fslm_stock_sync_frequency' );
		register_setting( 'fslm_general_option_group', 'fslm_duplicate_license' );
		register_setting( 'fslm_general_option_group', 'fslm_download_links' );
		register_setting( 'fslm_general_option_group', 'fslm_vendors_can_manager_licenses' );

		register_setting( 'fslm_general_option_group', 'fslm_meta_key_name_plural' );
		register_setting( 'fslm_general_option_group', 'fslm_key_delivery' );
		register_setting( 'fslm_general_option_group', 'fslm_display' );
		register_setting( 'fslm_general_option_group', 'fslm_different_keys' );
		register_setting( 'fslm_general_option_group', 'fslm_show_in' );

		register_setting( 'fslm_lkg_option_group', 'fslm_prefix' );
		register_setting( 'fslm_lkg_option_group', 'fslm_chunks_number' );
		register_setting( 'fslm_lkg_option_group', 'fslm_chunks_length' );
		register_setting( 'fslm_lkg_option_group', 'fslm_suffix' );
		register_setting( 'fslm_lkg_option_group', 'fslm_max_instance_number' );
		register_setting( 'fslm_lkg_option_group', 'fslm_valid' );
		register_setting( 'fslm_lkg_option_group', 'fslm_active' );

		register_setting( 'fslm_notifications_option_group', 'fslm_notif_min_licenses_nb' );
		register_setting( 'fslm_notifications_option_group', 'fslm_notif_mail' );
		register_setting( 'fslm_notifications_option_group', 'fslm_notif_mail_to' );

		register_setting( 'fslm_hidden_options', 'fslm_last_sent_notification_email_date' );
		register_setting( 'fslm_hidden_options', 'fslm_page_id' );

		register_setting( 'fslm_extra_option_group', 'fslm_delete_lp_db_tables' );
		register_setting( 'fslm_extra_option_group', 'fslm_delete_lk_db_tables' );
		register_setting( 'fslm_extra_option_group', 'fslm_delete_gr_db_tables' );
		register_setting( 'fslm_extra_option_group', 'fslm_debug_enabled' );
		register_setting( 'fslm_extra_option_group', 'fslm_alt_delivery_method' );

		register_setting( 'fslm_customizations_option_group', 'fslm_is_import_prefix_suffix_enabled' );

		register_setting( 'fslm_api_option_group', 'fslm_api_key' );
		register_setting( 'fslm_api_option_group', 'fslm_private_api_key' );
		register_setting( 'fslm_api_option_group', 'fslm_enable_private_api' );
		register_setting( 'fslm_api_option_group', 'fslm_disable_api_v1' );
		register_setting( 'fslm_api_option_group', 'fslm_disable_api_v2' );
		register_setting( 'fslm_api_option_group', 'fslm_disable_api_v3' );
		register_setting( 'fslm_api_option_group', 'fslm_api_v3_pk' );
		register_setting( 'fslm_api_option_group', 'fslm_api_v3_algo' );
		register_setting( 'fslm_api_option_group', 'fslm_api_v3_passphrase' );
		register_setting( 'fslm_api_option_group', 'fslm_api_v3_encode' );

		global $wp_roles, $fslm_api_v3_endpoints;
		$all_roles = $wp_roles->roles;

		foreach ( $fslm_api_v3_endpoints as $key => $value ) {
			foreach ( $all_roles as $role_slug => $role ) {
				register_setting( 'fslm_api_option_group', 'fslm_api_v3_permission_' . $role_slug . '_' . $key );
			}
		}

		register_setting( 'fslm_update_option_group', 'fslm_db_version' );

		register_setting( 'fslm_subscriptions_option_group', 'fslm_skip_renewals' );

	}

	/**
	 * Load required scripts and localization settings
	 */
	function load_license_manager_admin_scripts() {

		wp_enqueue_style( 'fslm_License_Manager_Style', plugins_url( '/assets/css/style.css', FSLM_PLUGIN_FILE ), array(), '5005' );
		wp_enqueue_style( 'fslm_select2', plugins_url( '/assets/select2/css/select2.min.css', FSLM_PLUGIN_FILE ), array(), '4200' );

		if ( isset( $_GET['page'] )
		     && $_GET['page'] == 'license-manager-settings'
		     && isset( $_GET['tab'] )
		     && $_GET['tab'] == 'emails' ) { // to avoid conflict with other plugins that use TinyMCE

			wp_enqueue_script( 'fslm_tinymce', plugins_url( '/assets/js/tinymce/tinymce.min.js', FSLM_PLUGIN_FILE ),
				array( 'jquery' ) );
		}

		wp_enqueue_script( 'fslm_json_ui', plugins_url( '/assets/js/jsoneditor.min.js', FSLM_PLUGIN_FILE ), array( 'jquery' ) );
		wp_enqueue_script( 'fslm_SortTable', plugins_url( '/assets/js/sortTable.js', FSLM_PLUGIN_FILE ), array( 'jquery' ) );
		wp_enqueue_script( 'fslm_select2_js', plugins_url( '/assets/select2/js/select2.full.min.js', FSLM_PLUGIN_FILE ), array( 'jquery' ) );


		wp_enqueue_script( 'fslm_Main', plugins_url( '/assets/js/main.js', FSLM_PLUGIN_FILE ), array(
			'jquery',
			'jquery-form'
		), '510' );


		$translation_array = array(
			'replace_key' => __( "Replace the existing keys(if any) and reload the page?\n\n" .
			                     "1. If there is no license keys available for the product this action will only delete " .
			                     "the assigned keys.\n2. If the available keys are less then the required number of keys " .
			                     "this action will delete the assigned keys and assign the available ones \"Assign new " .
			                     "license key\" option can later be used to assign the rest of the keys.\n3. If the " .
			                     "license keys generator is enabled and there is no keys available for the product new " .
			                     "keys will be generated.\n4. The option will only add keys equivalent the the ordered " .
			                     "number, any manually assigned keys won't be added", 'fslm' ),

			'generate' => __( 'Generate', 'fslm' ),

			'license_keys' => __( 'license key(s)?', 'fslm' ),

			'delete_this_item' => __( "Delete This item?", 'fslm' ),

			'replace_item_key' => __( "Replace this license key and reload the page?\n\n1. If there is no license keys" .
			                          " available for the product this action will only delete the assigned key.\n2. If the license keys " .
			                          "generator is enabled and there is no keys available for the product a new key will be generated.",
					'fslm' ) . "\n\n" . __( "This feature is not compatible with license keys that can be delivred " .
			                                "multiple times.", "fslm" ),

			'new_item_key' => __( "Add a new license key to this item and reload the page?\n\n1. If there is no " .
			                      "available keys for the product this action won't add anything.\n2. If the license keys generator " .
			                      "is enabled and there is no keys available for the product a new key will be generated.", 'fslm' ) .
			                  "\n\n" . __( "This feature is not compatible with license keys that can be delivered multiple " .
			                               "times.", "fslm" ),

			'replace_keys' => __( "Replace this items license keys and reload the page\n\n1. If there is no license " .
			                      "keys available for the product this action will only delete the assigned keys.\n2. If the " .
			                      "available keys are less then the required number of keys this action will delete the assigned " .
			                      "keys and assign the available ones \"Assign new license key\" option can later be used to assign " .
			                      "the rest of the keys.\n3. If the license keys generator  is enabled and there is no keys " .
			                      "available for the product new keys will be generated.", 'fslm' ) . "\n\n" . __( "This feature is " .
			                                                                                                       "not compatible with license keys that can be delivred " . "multiple times.", "fslm" ),

			'refresh_license_keys' => __( 'Refresh the license keys and reload the page?' . "\n" .
			                              'This action will only change the license keys, the expiration dates, and the activation limit.', 'fslm' ),

			'wclm_assign_missing_keys' => __( 'Assign the missing license keys to the order?' . "\n" .
			                                  'Ensure you have the required license keys added to the license manager.' . "\n\n" .
			                                  'Important: If there are no license keys assigned, add license keys and use the "Assign New License Keys" option first.', 'fslm' ),
		);
		wp_localize_script( 'fslm_Main', 'fslm', $translation_array );

	}

}
