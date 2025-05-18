<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$fslm_debug = get_option( 'fslm_debug_enabled', 'off' );

$fslm_api_v3_endpoints = array(
	'verify'                    => __( 'Verify*', 'fslm' ),
	'activate'                  => __( 'Activate*', 'fslm' ),
	'deactivate'                => __( 'Deactivate*', 'fslm' ),
	'get_license_details'       => __( 'Get License Details*', 'fslm' ),
	'get_product_api_meta'      => __( 'Get Product API Meta', 'fslm' ),
	'get_license_status'        => __( 'Get License Status*', 'fslm' ),
	'get_current_user_licenses' => __( 'Get Current User Licenses', 'fslm' ),
	'register_license_key'      => __( 'Register License Key', 'fslm' ),
	'set_license_status'        => __( 'Set License Status*', 'fslm' ),
	'create_license_key'        => __( 'Create License Key', 'fslm' ),
	'update_license_key'        => __( 'Update License Key', 'fslm' ),
	'delete_license_key'        => __( 'Delete License Key', 'fslm' ),
	'add_license_key_meta'      => __( 'Add License Key Meta*', 'fslm' ),
	'update_license_key_meta'   => __( 'Update License Key Meta*', 'fslm' ),
	'delete_license_key_meta'   => __( 'Delete License Key Meta*', 'fslm' ),
);

$fslm_admin_roles = array( 'administrator', 'shop_manager' );

$fslm_all_allowed = array(
	'verify',
	'activate',
	'deactivate',
	'get_license_details',
	'get_product_api_meta',
	'get_license_status',
	'get_current_user_licenses',
	'register_license_key',

	'add_license_key_meta',
	'update_license_key_meta',
	'delete_license_key_meta',
);

/**
 *
 * Format date
 *
 * @param $date
 * @param bool $expiration_date
 *
 * @return string|void
 */
function fslm_format_date( $date, $expiration_date = false ) {
	if ( $date == '0000-00-00' && $expiration_date ) {
		return __( 'Doesn\'t Expire', 'fslm' );
	}

	if ( $date == '0000-00-00' ) {
		return __( 'None', 'fslm' );
	}

	if ( $date != '' ) {
		$date = strtotime( $date );

		return __( date( 'M', $date ), 'fslm' ) . ' ' . date( 'd, Y', $date );
	}

	return __( 'None', 'fslm' );
}

/**
 *
 * Convert <br> to new license
 *
 * @param $str
 *
 * @return string|string[]
 */
function br2newLine( $str ) {
	$newLineArray = array( '<br>', '<br />', '<br/>' );

	return str_replace( $newLineArray, "\n", $str );
}

/**
 *
 * Remove <br>
 *
 * @param $str
 *
 * @return string|string[]
 */
function fslm_removeBr( $str ) {
	$newLineArray = array( '<br>', '<br />', '<br/>' );

	return str_replace( $newLineArray, '', $str );
}

/**
 *
 * Set encryption keys
 *
 * @param $key
 * @param $vi
 * @param string $action
 */
function set_encryption_key( $key, $vi, $action = 'set' ) {
	$upload_directory = wp_upload_dir();
	$target_dir       = $upload_directory['basedir'] . '/fslm_files/';

	if ( ! file_exists( $target_dir ) ) {
		wp_mkdir_p( $target_dir );

		$fp = fopen( $target_dir . '.htaccess', 'w' );
		fwrite( $fp, 'deny from all' );
		fclose( $fp );

		$fp = fopen( $target_dir . 'encryption_key.php', 'w' );
		fwrite( $fp, '<?php define("ENCRYPTION_KEY", "' . $key . "\");\ndefine(\"ENCRYPTION_VI\", \"" . $vi . '");' );
		fclose( $fp );

		$fp = fopen( $target_dir . 'index.php', 'w' );
		fwrite( $fp, '<?php' );
		fclose( $fp );
	} else {
		if ( $action == 'update' ) {
			$fp = fopen( $target_dir . 'encryption_key.php', 'w' );
			fwrite(
				$fp,
				'<?php define("ENCRYPTION_KEY", "' . $key . "\");\ndefine(\"ENCRYPTION_VI\", \"" . $vi . '");'
			);
			fclose( $fp );
		}
	}
}

/**
 *
 * Encrypt/decrypt license keys
 *
 * @param $action
 * @param $string
 * @param $secret_key
 * @param $secret_iv
 *
 * @return bool|false|string
 */
function encrypt_decrypt( $action, $string, $secret_key, $secret_iv ) {
	$output = false;

	if ( $secret_key == '' && $secret_iv == '' ) {
		return $string;
	}

	if ( ! extension_loaded( 'openssl' ) ) {
		return $string;
	}

	$encrypt_method = 'AES-256-CBC';

	// hash
	$key = hash( 'sha256', $secret_key );

	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

	if ( $action == 'encrypt' ) {
		$output = openssl_encrypt( $string, $encrypt_method, $key, 0, $iv );
		$output = base64_encode( $output );
	} else {
		if ( $action == 'decrypt' ) {
			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		}
	}

	return $output;
}

/**
 * Months translation
 */
$months = array(
	array(
		'number' => '01',
		'text'   => __( 'Jan', 'fslm' ),
	),
	array(
		'number' => '02',
		'text'   => __( 'Feb', 'fslm' ),
	),
	array(
		'number' => '03',
		'text'   => __( 'Mar', 'fslm' ),
	),
	array(
		'number' => '04',
		'text'   => __( 'Apr', 'fslm' ),
	),
	array(
		'number' => '05',
		'text'   => __( 'May', 'fslm' ),
	),
	array(
		'number' => '06',
		'text'   => __( 'Jun', 'fslm' ),
	),
	array(
		'number' => '07',
		'text'   => __( 'Jul', 'fslm' ),
	),
	array(
		'number' => '08',
		'text'   => __( 'Aug', 'fslm' ),
	),
	array(
		'number' => '09',
		'text'   => __( 'Sep', 'fslm' ),
	),
	array(
		'number' => '10',
		'text'   => __( 'Oct', 'fslm' ),
	),
	array(
		'number' => '11',
		'text'   => __( 'Nov', 'fslm' ),
	),
	array(
		'number' => '12',
		'text'   => __( 'Dec', 'fslm' ),
	),
);

/**
 * Order status translation
 */
$status = array(
	'Available'    => __( 'Available', 'fslm' ),
	'Active'       => __( 'Active', 'fslm' ),
	'Expired'      => __( 'Expired', 'fslm' ),
	'Inactive'     => __( 'Inactive', 'fslm' ),
	'Returned'     => __( 'Returned', 'fslm' ),
	'Sold'         => __( 'Sold', 'fslm' ),
	'Redeemed'     => __( 'Redeemed', 'fslm' ),
	'unregistered' => __( 'Unregistered', 'fslm' ),
);

/**
 * Validate database version
 */
function fslm_verify_database() {
	global $wpdb;

	$current_database_columns = array();
	$last_version_columns     = array(
		'license_id',
		'product_id',
		'variation_id',
		'license_key',
		'image_license_key',
		'license_status',
		'owner_first_name',
		'owner_last_name',
		'owner_email_address',
		'delivre_x_times',
		'remaining_delivre_x_times',
		'max_instance_number',
		'number_use_remaining',
		'activation_date',
		'creation_date',
		'sold_date',
		'expiration_date',
		'valid',
		'order_id',
		'device_id',
	);

	$table_name_1 = $wpdb->prefix . 'wc_fs_product_licenses_keys';
	$table_name_2 = $wpdb->prefix . 'wc_fs_product_licenses_keys_generator_rules';
	$table_name_3 = $wpdb->prefix . 'wc_fs_licensed_products';
	$table_name_4 = $wpdb->prefix . 'wc_fs_queue';
	$table_name_5 = $wpdb->prefix . 'wc_fs_license_key_meta';

	if (
		( $table_name_1 === $wpdb->get_var( "SHOW TABLES LIKE '$table_name_1'" ) ) &&
		( $table_name_2 === $wpdb->get_var( "SHOW TABLES LIKE '$table_name_2'" ) ) &&
		( $table_name_3 === $wpdb->get_var( "SHOW TABLES LIKE '$table_name_3'" ) ) &&
		( $table_name_4 === $wpdb->get_var( "SHOW TABLES LIKE '$table_name_4'" ) ) &&
		( $table_name_5 === $wpdb->get_var( "SHOW TABLES LIKE '$table_name_5'" ) )
	) {

		$query = $wpdb->get_results( "SHOW COLUMNS FROM `{$table_name_1}`" );

		foreach ( $query as $q ) {
			$current_database_columns[] = $q->Field;
		}

		if ( array_diff( $last_version_columns, $current_database_columns ) ) {
			echo '<div class="error">' . '<h4>' . __(
					'Your database is not up to date.',
					'fslm'
				) . '</h4><form method="post" action="options.php"><input type="hidden" name="fslm_db_version" value="0">';

			settings_fields( 'fslm_update_option_group' );
			do_settings_sections( 'fslm_update_option_group' );

			submit_button( __( 'Update Now', 'fslm' ) );

			echo '</form></p></div>';
		}
	} else {

		echo '<div class="error">' . '<h4>' . __(
				'Database tables missing.',
				'fslm'
			) . '</h4><form method="post" action="options.php"><input type="hidden" name="fslm_db_version" value="0">';

		settings_fields( 'fslm_update_option_group' );
		do_settings_sections( 'fslm_update_option_group' );

		submit_button( __( 'Create required database tables', 'fslm' ) );

		echo '</form></p></div>';

	}

}


function wclm_access_check(): bool {
	$code = get_option( 'fslm_ebun', '' );

	if ( preg_match( "/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $code ) ) {
		return true;
	} elseif ( preg_match( "/^([a-f0-9]{8})-(([a-f0-9]{4})-){2}([a-f0-9]{12})-([a-f0-9]{4})$/i", $code ) ) {
		return true;
	}

	return false;
}

/**
 * Check if vendors have permission to manage plugins features
 *
 * @return boolean
 */
function fslm_vendors_permission() {

	if ( 'on' === get_option( 'fslm_vendors_can_manager_licenses', '' ) ) {
		$user          = wp_get_current_user();
		$allowed_roles = array( 'wc_product_vendors_manager_vendor', 'wc_product_vendors_admin_vendor' );
		if ( array_intersect( $allowed_roles, $user->roles ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Check if the current user is a vendor admin
 *
 * @return bool
 */
function fslm_is_vendor_admin() {
	$user          = wp_get_current_user();
	$allowed_roles = array( 'wc_product_vendors_admin_vendor' );
	if ( array_intersect( $allowed_roles, $user->roles ) ) {
		return true;
	}

	return false;
}

/**
 * Check if the current user is a vendor manager
 *
 * @return bool
 */
function fslm_is_vendor_manager() {
	$user          = wp_get_current_user();
	$allowed_roles = array( 'wc_product_vendors_manager_vendor' );
	if ( array_intersect( $allowed_roles, $user->roles ) ) {
		return true;
	}

	return false;
}


/**
 * Check if the current user is a vendor manager
 *
 * @return bool
 */
function fslm_is_administrator() {
	$user          = wp_get_current_user();
	$allowed_roles = array( 'administrator' );
	if ( array_intersect( $allowed_roles, $user->roles ) ) {
		return true;
	}

	return false;
}

/**
 * @return string
 */
function wclm_get_extensions_page_notice(): string {
	$notice = '';

	if ( get_option( 'wclm_extensions_page_notice_next_check', 0 ) < time() ) {
		$curlSession = curl_init();
		curl_setopt( $curlSession, CURLOPT_URL, 'https://firassaidi.com/announcement/extensions-page-notice.php' );
		curl_setopt( $curlSession, CURLOPT_RETURNTRANSFER, true );

		$notice = curl_exec( $curlSession );
		curl_close( $curlSession );

		if ( ! add_option( 'wclm_extensions_page_notice', $notice ) ) {
			update_option( 'wclm_extensions_page_notice', $notice );
		}

		if ( ! add_option( 'wclm_extensions_page_notice_next_check', strtotime( "+1 hour" ) ) ) {
			update_option( 'wclm_extensions_page_notice_next_check', strtotime( "+1 hour" ) );
		}
	} else {
		$notice = get_option( 'wclm_extensions_page_notice', '' );
	}

	return $notice;
}