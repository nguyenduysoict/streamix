<?php

// Prevent direct access
defined( 'ABSPATH' ) or die();


class FSLM_APIv3 extends WP_REST_Controller {

	public function __construct() {
		require_once FSLM_PLUGIN_DIR . '/includes/api/v3/api-responses.php';
	}

	public function register_routes() {
		$version   = 3;
		$namespace = 'wclm/v' . $version;

		register_rest_route( $namespace, '/verify/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'verify' ),
				'permission_callback' => array( $this, 'verify_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/activate/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'activate' ),
				'permission_callback' => array( $this, 'activate_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/deactivate/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'deactivate' ),
				'permission_callback' => array( $this, 'deactivate_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/get-license-details/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'get_license_details' ),
				'permission_callback' => array( $this, 'get_license_details_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/get-product-api-meta/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'get_product_api_meta' ),
				'permission_callback' => array( $this, 'get_product_api_meta_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/get-license-status/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'get_license_status' ),
				'permission_callback' => array( $this, 'get_license_status_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/get-current-user-licenses/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'get_current_user_licenses' ),
				'permission_callback' => array( $this, 'get_current_user_licenses_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/register-license-key/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'register_license_key' ),
				'permission_callback' => array( $this, 'register_license_key_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/set-license-status/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'set_license_status' ),
				'permission_callback' => array( $this, 'set_license_status_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/create-license-key/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'create_license_key' ),
				'permission_callback' => array( $this, 'create_license_key_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/update-license-key/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'update_license_key' ),
				'permission_callback' => array( $this, 'update_license_key_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/delete-license-key/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'delete_license_key' ),
				'permission_callback' => array( $this, 'delete_license_key_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/add-license-key-meta/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'add_license_key_meta' ),
				'permission_callback' => array( $this, 'add_license_key_meta_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/update-license-key-meta/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'update_license_key_meta' ),
				'permission_callback' => array( $this, 'update_license_key_meta_permissions_check' )
			)
		] );

		register_rest_route( $namespace, '/delete-license-key-meta/', [
			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'delete_license_key_meta' ),
				'permission_callback' => array( $this, 'delete_license_key_meta_permissions_check' )
			)
		] );
	}

	/**
	 * Verify license key validity
	 *
	 * @param $request
	 *
	 * @return string[]|WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function verify( $request ) {
		$license_key_decrypted = $request['license_key'];
		$device_id             = isset( $request['device_id'] ) ? $request['device_id'] : 'none';
		$license_key           = encrypt_decrypt( 'encrypt', $license_key_decrypted, ENCRYPTION_KEY, ENCRYPTION_VI );

		if ( ! $this->license_owner_check( $license_key ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::NOT_OWNER );
		}

		return $this->sign_response( $this->verify_helper( $license_key_decrypted, $device_id, false ) );
	}

	/**
	 * Activate
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function activate( $request ) {
		global $wpdb;

		$license_key_decrypted = $request['license_key'];
		$device_id             = isset( $request['device_id'] ) ? $request['device_id'] : 'none';

		$license_key = $license_key_decrypted;
		$license_key = encrypt_decrypt( 'encrypt', $license_key, ENCRYPTION_KEY, ENCRYPTION_VI );

		$verification = $this->verify_helper( $license_key_decrypted );

		if ( ! $this->license_owner_check( $license_key ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::NOT_OWNER );
		}

		if ( $verification['code'] == '500' ) {
			$query = $wpdb->get_row( "SELECT number_use_remaining, device_id FROM {$wpdb->prefix}wc_fs_product_licenses_keys WHERE license_key='{$license_key}'" );

			if ( $query ) {
				$validated = false;

				$number_use_remaining = $query->number_use_remaining;
				$device_id_current    = $query->device_id;
				if ( $query->device_id == '[]' ) {
					$device_id_current = '';
				}

				$devices = array();

				$device_id_json = json_decode( $device_id_current );

				if ( $device_id != 'none' && $device_id != '' ) {

					$used = $number_use_remaining - 1;

					if ( $device_id_json != null && is_array( $device_id_json ) && json_last_error() === JSON_ERROR_NONE ) {
						if ( in_array( $device_id, $device_id_json ) && $device_id_current != 'none' ) {
							$devices   = $device_id_json;
							$used      = $number_use_remaining;
							$validated = true;
						} else {
							if ( ! in_array( $device_id, $device_id_json ) && $device_id_current != 'none' ) {
								$device_id_json[] = $device_id;
								$devices          = $device_id_json;

								if ( $number_use_remaining <= 0 ) {
									return $this->sign_response( FSLM_APIv3_Responses::ACTIVATION_MAX_REACHED );
								}
							}
						}
					} else {
						if ( $device_id_current == $device_id && $device_id_current != 'none' && $device_id_current != '' ) {
							$devices[] = $device_id;
							$used      = $number_use_remaining;
							$validated = true;
						} else {
							if ( $device_id_current != $device_id && $device_id_current != 'none' && $device_id_current != '' ) {
								$devices[] = $device_id_current;
								$devices[] = $device_id;

								if ( $number_use_remaining <= 0 ) {
									return $this->sign_response( FSLM_APIv3_Responses::ACTIVATION_MAX_REACHED );
								}
							} else {
								if ( $device_id_current != $device_id && $device_id_current != 'none' && $device_id_current == '' ) {
									$devices[] = $device_id;

									if ( $number_use_remaining <= 0 ) {
										return $this->sign_response( FSLM_APIv3_Responses::ACTIVATION_MAX_REACHED );
									}
								}
							}
						}
					}

					$data = array(
						'number_use_remaining' => $used,
						'activation_date'      => date( 'Y-m-d H:i:s' ),
						'license_status'       => 'active',
						'device_id'            => json_encode( $devices )
					);


					$where  = array(
						'license_key' => $license_key
					);
					$result = $wpdb->update( "{$wpdb->prefix}wc_fs_product_licenses_keys", $data, $where );

					if ( $result == 1 || $validated ) {
						return $this->sign_response( FSLM_APIv3_Responses::LICENSE_KEY_ACTIVATED );
					} else {
						return $this->sign_response( FSLM_APIv3_Responses::ERROR );
					}

				} else {
					if ( $number_use_remaining > 0 && ( $device_id == 'none' || $device_id == '' ) ) {

						if ( ( $device_id_current != 'none' && $device_id_current != '' && $device_id_json != null && is_array( $device_id_json ) && count( $device_id_json ) > 0 ) && ( $device_id == 'none' || $device_id == '' ) ) {
							return $this->sign_response( FSLM_APIv3_Responses::DEVICE_ID_REQUIRED_ACTIVATION );
						}

						$used = $number_use_remaining - 1;

						$data = array(
							'number_use_remaining' => $used,
							'activation_date'      => date( 'Y-m-d H:i:s' ),
							'license_status'       => 'active'
						);

						$where  = array( 'license_key' => $license_key );
						$result = $wpdb->update( "{$wpdb->prefix}wc_fs_product_licenses_keys", $data, $where );

						if ( $result == 1 ) {
							return $this->sign_response( FSLM_APIv3_Responses::LICENSE_KEY_ACTIVATED );
						} else {
							return $this->sign_response( FSLM_APIv3_Responses::ERROR );
						}

					} else {
						return $this->sign_response( FSLM_APIv3_Responses::ACTIVATION_MAX_REACHED );
					}
				}

			}

			return $this->sign_response( FSLM_APIv3_Responses::INVALID_LICENSE_KEY );
		} else {
			return $this->sign_response( $verification );
		}
	}

	/**
	 * Deactivate
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function deactivate( $request ) {
		global $wpdb;

		$license_key_decrypted = $request['license_key'];
		$device_id             = isset( $request['device_id'] ) ? $request['device_id'] : 'none';

		$license_key = $license_key_decrypted;
		$license_key = encrypt_decrypt( 'encrypt', $license_key, ENCRYPTION_KEY, ENCRYPTION_VI );

		$verification = $this->verify_helper( $license_key_decrypted );

		if ( ! $this->license_owner_check( $license_key ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::NOT_OWNER );
		}

		if ( $verification['code'] == '500' ) {

			$query = $wpdb->get_results( "SELECT max_instance_number, number_use_remaining, license_status, device_id FROM {$wpdb->prefix}wc_fs_product_licenses_keys WHERE license_key='{$license_key}'" );
			if ( $query ) {
				$query = $query[0];

				$max_instance_number  = $query->max_instance_number;
				$number_use_remaining = $query->number_use_remaining;
				$license_status       = $query->license_status;
				$device_id_current    = $query->device_id;

				$active = ( $number_use_remaining + 1 == $max_instance_number ) ? 'inactive' : $license_status;

				if ( $number_use_remaining < $max_instance_number ) {

					$data = array(
						'number_use_remaining' => $number_use_remaining + 1,
						'license_status'       => $active
					);

					$device_id_json = array_values( json_decode( $query->device_id ) );

					if ( $device_id != 'none' && $device_id != '' ) {

						if ( $device_id_json != null && is_array( $device_id_json ) && json_last_error() === JSON_ERROR_NONE ) {
							if ( in_array( $device_id, $device_id_json ) && $device_id_current != 'none' ) {
								if ( ( $key = array_search( $device_id, $device_id_json ) ) !== false ) {
									unset( $device_id_json[ $key ] );
									$devices           = array_values( $device_id_json );
									$data['device_id'] = json_encode( $devices );
								}
							} else {
								if ( ! in_array( $device_id, $device_id_json ) ) {
									return $this->sign_response( FSLM_APIv3_Responses::INVALID_DEVICE_ID );
								}
							}
						} else {
							if ( $device_id_current == $device_id && $device_id_current != 'none' ) {
								$devices           = array_values( $device_id_json );
								$data['device_id'] = json_encode( $devices );
							} else {
								if ( $device_id_current != $device_id && $device_id_current != 'none' ) {
									return $this->sign_response( FSLM_APIv3_Responses::INVALID_DEVICE_ID );
								} else {
									return $this->sign_response( FSLM_APIv3_Responses::ERROR );
								}
							}
						}
					} else {
						if ( ( $device_id_current != 'none' && $device_id_current != '' && $device_id_json != null && is_array( $device_id_json ) && count( $device_id_json ) > 0 ) && ( $device_id == 'none' || $device_id == '' ) ) {
							return $this->sign_response( FSLM_APIv3_Responses::DEVICE_ID_REQUIRED_DEACTIVATION );
						}
					}


					$where  = array(
						'license_key' => $license_key
					);
					$result = $wpdb->update( "{$wpdb->prefix}wc_fs_product_licenses_keys", $data, $where );

					if ( $result == 1 ) {
						return $this->sign_response( FSLM_APIv3_Responses::LICENSE_KEY_DEACTIVATED );
					} else {
						return $this->sign_response( FSLM_APIv3_Responses::ERROR );
					}
				} else {
					return $this->sign_response( FSLM_APIv3_Responses::LICENSE_ALREADY_INACTIVE );
				}

			}

			return $this->sign_response( FSLM_APIv3_Responses::INVALID_LICENSE_KEY );
		} else {
			return $this->sign_response( $verification );
		}
	}

	/**
	 * Get license key details
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_license_details( $request ) {
		global $wpdb;

		$license_key_decrypted = $request['license_key'];

		$license_key = $license_key_decrypted;
		$license_key = encrypt_decrypt( 'encrypt', $license_key, ENCRYPTION_KEY, ENCRYPTION_VI );

		$verification = $this->verify_helper( $license_key_decrypted );

		if ( ! $this->license_owner_check( $license_key ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::NOT_OWNER );
		}

		if ( $verification['code'] == '500' ) {
			$query = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wc_fs_product_licenses_keys WHERE license_key='{$license_key}'",
				ARRAY_A );

			if ( $query ) {
				$product_id   = $query['product_id'];
				$product_name = $wpdb->get_row(
					$wpdb->prepare( "SELECT 
                                            post_title 
                                       FROM 
                                            {$wpdb->posts} 
                                       WHERE 
                                            ID = '$product_id'", "product"
					), ARRAY_A );


				$query['product_name'] = '';
				if ( $product_name ) {
					$query['product_name'] = $product_name['post_title'];
				}


				$variation_id   = $query['variation_id'];
				$variation_name = $wpdb->get_row(
					$wpdb->prepare( "SELECT 
                                            post_title 
                                       FROM 
                                            {$wpdb->posts} 
                                       WHERE 
                                            ID = '$variation_id'", "product_variation"
					), ARRAY_A );

				$query['variation_name'] = '';
				if ( $variation_name ) {
					$query['variation_name'] = $variation_name['post_title'];
				}

				$query['license_status'] = strtolower( $query['license_status'] );

				$query['delivery_limit'] = $query['delivre_x_times'];
				unset( $query['delivre_x_times'] );

				$query['remaining_delivery_times'] = $query['remaining_delivre_x_times'];
				unset( $query['remaining_delivre_x_times'] );

				$query['activation_limit'] = $query['max_instance_number'];
				unset( $query['max_instance_number'] );

				$query['remaining_activations'] = $query['number_use_remaining'];
				unset( $query['number_use_remaining'] );

				$query['license_key'] = $license_key_decrypted;
				$query['device_ids']  = $query['device_id'] ? json_decode( $query['device_id'] ) : array();
				unset( $query['device_id'] );

				$query['expiration_date'] = $query['expiration_date'] == "0000-00-00" ? null : $query['expiration_date'];
				$query['activation_date'] = $query['activation_date'] == "0000-00-00" ? null : $query['activation_date'];
				$query['creation_date']   = $query['creation_date'] == "0000-00-00" ? null : $query['creation_date'];
				$query['sold_date']       = $query['sold_date'] == "0000-00-00" ? null : $query['sold_date'];

			}

			$meta = $wpdb->get_results( "SELECT id, meta_key, meta_value FROM {$wpdb->prefix}wc_fs_license_key_meta WHERE license_id='{$query['license_id']}'" );
			if ( $meta ) {
				$query['license_key_meta'] = $meta;
			} else {
				$query['license_key_meta'] = array();
			}

			return $this->sign_response( $query );

		} else {
			return $this->sign_response( $verification );
		}
	}

	/**
	 * Product API Meta
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_product_api_meta( $request ) {

		$product_id = $request['product_id'];

		$meta = array(
			'software_name'        => get_post_meta( (int) $product_id, 'fslm_sn', true ),
			'software_id'          => get_post_meta( (int) $product_id, 'fslm_sid', true ),
			'software_version'     => get_post_meta( (int) $product_id, 'fslm_sv', true ),
			'software_author'      => get_post_meta( (int) $product_id, 'fslm_sa', true ),
			'software_url'         => get_post_meta( (int) $product_id, 'fslm_surl', true ),
			'software_last_update' => get_post_meta( (int) $product_id, 'fslm_slu', true ),
		);

		$extra_data  = get_post_meta( (int) $product_id, 'fslm_sed', true );
		$custom_data = json_decode( $extra_data );

		if ( json_last_error() === JSON_ERROR_NONE && $extra_data != "" ) {
			$meta['software_extra_data'] = $custom_data;
		} else {
			$meta['software_extra_data'] = $extra_data;
		}

		return $this->sign_response( $meta );
	}

	/**
	 * Get license status
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_license_status( $request ) {
		global $wpdb, $fs_wc_licenses_manager;

		if ( get_option( 'fslm_auto_expire', '' ) == 'on' ) {
			$fs_wc_licenses_manager->auto_expire_license_keys();
		}

		$license_key_decrypted = $request['license_key'];
		$license_key           = $license_key_decrypted;
		$license_key           = encrypt_decrypt( 'encrypt', $license_key, ENCRYPTION_KEY, ENCRYPTION_VI );

		if ( ! $this->license_owner_check( $license_key ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::NOT_OWNER );
		}

		$query = $wpdb->get_row( "SELECT license_status FROM {$wpdb->prefix}wc_fs_product_licenses_keys WHERE license_key='{$license_key}'" );

		if ( $query ) {
			return $this->sign_response( array(
				'license_status' => strtolower( $query->license_status )
			) );
		}

		return $this->sign_response( FSLM_APIv3_Responses::INVALID_LICENSE_KEY );
	}

	/**
	 * Get current user purchased license keys
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_current_user_licenses( $request ) {
		global $wpdb;

		$user_id = get_current_user_id();

		$results = $wpdb->get_results( "
            SELECT 
                * 
            FROM
                {$wpdb->prefix}wc_fs_product_licenses_keys
            WHERE 
                order_id IN
                    (
                        SELECT DISTINCT 
                            pm.post_id AS order_id
                        FROM 
                            {$wpdb->prefix}postmeta AS pm
                        LEFT JOIN 
                            {$wpdb->prefix}posts AS p
                        ON 
                            pm.post_id = p.ID 
                        WHERE 
                            p.post_type = 'shop_order'
                            AND pm.meta_key = '_customer_user'       
                            AND pm.meta_value = {$user_id}
                        ORDER BY 
                            pm.post_id DESC
                    ) OR
                
                order_id IN
                    (
                        SELECT DISTINCT 
                           id
                        FROM 
                            {$wpdb->prefix}wc_orders 
                        WHERE 
                            type = 'shop_order'   
                            AND customer_id = {$user_id}
                        ORDER BY 
                            id DESC
                    )
            ORDER BY 
                license_id DESC
        ", ARRAY_A );

		foreach ( $results as $key => $value ) {

			$product_id   = $results[ $key ]['product_id'];
			$product_name = $wpdb->get_row(
				$wpdb->prepare( "SELECT 
                                            post_title 
                                       FROM 
                                            {$wpdb->posts} 
                                       WHERE 
                                            ID = '$product_id'", "product"
				), ARRAY_A );


			$results[ $key ]['product_name'] = '';
			if ( $product_name ) {
				$results[ $key ]['product_name'] = $product_name['post_title'];
			}


			$variation_id   = $results[ $key ]['variation_id'];
			$variation_name = $wpdb->get_row(
				$wpdb->prepare( "SELECT 
                                            post_title 
                                       FROM 
                                            {$wpdb->posts} 
                                       WHERE 
                                            ID = '$variation_id'", "product_variation"
				), ARRAY_A );

			$results[ $key ]['variation_name'] = '';
			if ( $variation_name ) {
				$results[ $key ]['variation_name'] = $variation_name['post_title'];
			}

			$results[ $key ]['license_status'] = strtolower( $results[ $key ]['license_status'] );

			$results[ $key ]['delivery_limit'] = $results[ $key ]['delivre_x_times'];
			unset( $results[ $key ]['delivre_x_times'] );

			$results[ $key ]['remaining_delivery_times'] = $results[ $key ]['remaining_delivre_x_times'];
			unset( $results[ $key ]['remaining_delivre_x_times'] );

			$results[ $key ]['activation_limit'] = $results[ $key ]['max_instance_number'];
			unset( $results[ $key ]['max_instance_number'] );

			$results[ $key ]['remaining_activations'] = $results[ $key ]['number_use_remaining'];
			unset( $results[ $key ]['number_use_remaining'] );

			$results[ $key ]['license_key'] = encrypt_decrypt( 'decrypt', $results[ $key ]['license_key'], ENCRYPTION_KEY,
				ENCRYPTION_VI );;
			$results[ $key ]['device_ids'] = $value['device_id'] ? json_decode( $value['device_id'] ) : array();
			unset( $results[ $key ]['device_id'] );

			$results[ $key ]['expiration_date'] = $results[ $key ]['expiration_date'] == "0000-00-00" ? null : $results[ $key ]['expiration_date'];
			$results[ $key ]['activation_date'] = $results[ $key ]['activation_date'] == "0000-00-00" ? null : $results[ $key ]['activation_date'];
			$results[ $key ]['creation_date']   = $results[ $key ]['creation_date'] == "0000-00-00" ? null : $results[ $key ]['creation_date'];
			$results[ $key ]['sold_date']       = $results[ $key ]['sold_date'] == "0000-00-00" ? null : $results[ $key ]['sold_date'];

			$meta = $wpdb->get_results( "SELECT id, meta_key, meta_value FROM {$wpdb->prefix}wc_fs_license_key_meta WHERE license_id='{$value['license_id']}'" );
			if ( $meta ) {
				$results[ $key ]['license_key_meta'] = $meta;
			} else {
				$results[ $key ]['license_key_meta'] = array();
			}
		}

		return $this->sign_response( array( 'licenses' => $results ) );

	}

	/**
	 * Register License Key
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @throws WC_Data_Exception
	 */
	public function register_license_key( $request ) {
		global $wpdb;

		$license_key = encrypt_decrypt( 'encrypt', $request['license_key'], ENCRYPTION_KEY, ENCRYPTION_VI );
		$query       = $wpdb->get_row( "SELECT 
                                           license_id, product_id, variation_id, max_instance_number, expiration_date, valid  
                                       FROM 
                                           {$wpdb->prefix}wc_fs_product_licenses_keys 
                                       WHERE 
                                           license_key='{$license_key}'
                                           AND license_status = 'unregistered'" );

		if ( $query ) {
			$current_user = wp_get_current_user();

			if ( $query->valid > 0 ) {
				$expiration_date = date( 'Y-m-d',
					strtotime( date( 'Y-m-d' ) . ' + ' . $query->valid . ' ' . 'days' ) );
			} else {
				if ( ( $query->expiration_date != '0000-00-00' ) && ( $query->expiration_date != '' ) ) {
					$expiration_date = $query->expiration_date;
				} else {
					$expiration_date = "0000-00-00";
				}
			}

			$result = $this->create_wc_order( array(
				'address'        => array(
					'first_name' => $current_user->billing_first_name,
					'last_name'  => $current_user->billing_first_name,
					'company'    => $current_user->billing_company,
					'email'      => $current_user->billing_email,
					'phone'      => $current_user->billing_phone,
					'address_1'  => $current_user->billing_address_1,
					'address_2'  => $current_user->billing_address_2,
					'city'       => $current_user->billing_city,
					'state'      => $current_user->billing_state,
					'postcode'   => $current_user->billing_postcode,
					'country'    => $current_user->billing_country
				),
				'user_id'        => $current_user->ID,
				'payment_method' => 'License Manager API',
				'order_status'   => array(
					'status' => 'completed',
					'note'   => 'License Manager API - Register a license key endpoint',
				),
				'line_items'     => array(
					array(
						'quantity' => 0,
						'args'     => array(
							'product_id'   => $query->product_id,
							'variation_id' => $query->variation_id,
							'variation'    => array()
						)
					),
				),
				'license_key'    => array(
					"license_id"          => $query->license_id,
					"product_id"          => $query->product_id,
					"variation_id"        => $query->variation_id,
					"license_key"         => $license_key,
					"max_instance_number" => $query->max_instance_number,
					"expiration_date"     => $expiration_date,
					"visible"             => "Yes"
				)
			) );


			$wpdb->update(
				"{$wpdb->prefix}wc_fs_product_licenses_keys",
				array(
					"order_id"                  => $result['order_id'],
					"remaining_delivre_x_times" => 0,
					"owner_first_name"          => $current_user->user_firstname,
					"owner_last_name"           => $current_user->user_lastname,
					"owner_email_address"       => $current_user->user_email,
					"expiration_date"           => $expiration_date,
					"sold_date"                 => date( 'Y-m-d' ),
					"license_status"            => "sold"
				),
				array(
					'license_id' => $query->license_id
				)
			);

			return $this->sign_response( FSLM_APIv3_Responses::UNREGISTERED_LICENSE_KEY_ASSIGNED );
		} else {
			return $this->sign_response( FSLM_APIv3_Responses::UNREGISTERED_LICENSE_KEY_NOT_FOUND );
		}

	}

	/**
	 * Set license status
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function set_license_status( $request ) {
		global $wpdb;

		$license_key = encrypt_decrypt( 'encrypt', $request['license_key'], ENCRYPTION_KEY, ENCRYPTION_VI );
		$status      = sanitize_text_field( strtolower( $request['status'] ) );

		$verification = $this->verify_helper( $request['license_key'] );

		if ( ! $this->license_owner_check( $license_key ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::NOT_OWNER );
		}

		if ( $verification['code'] == '500' ) {
			$result = $wpdb->update(
				"{$wpdb->prefix}wc_fs_product_licenses_keys",
				array(
					'license_status' => $status
				),
				array(
					'license_key' => $license_key
				)
			);

			if ( ! is_wp_error( $result ) ) {
				return $this->sign_response( FSLM_APIv3_Responses::LICENSE_STATUS_UPDATED );
			} else {
				return $this->sign_response( FSLM_APIv3_Responses::ERROR );
			}
		}

		return $this->sign_response( FSLM_APIv3_Responses::INVALID_LICENSE_KEY );
	}

	/**
	 * Create license keys
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function create_license_key( $request ) {
		global $wpdb, $fs_wc_licenses_manager;

		$license_keys = json_decode( $request['license_keys'] );

		$product_id   = (int) $request['product_id'];
		$variation_id = (int) $request['variation_id'];

		if ( ! $this->product_exists( $product_id ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::INVALID_PRODUCT_ID );
		}

		if ( ! $this->variation_exists( $variation_id ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::INVALID_VARIATION_ID );
		}

		if ( $request['order_id'] != 0 && get_post_type( $request['order_id'] ) != "shop_order" ) {
			return $this->sign_response( FSLM_APIv3_Responses::INVALID_ORDER_ID );
		}

		$owner_first_name    = sanitize_text_field( $request['owner_first_name'] );
		$owner_last_name     = sanitize_text_field( $request['owner_last_name'] );
		$owner_email_address = sanitize_email( $request['owner_email_address'] );

		$activation_date = null;
		if ( preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $request['activation_date'] ) === 1 ) {
			$activation_date = $request['activation_date'];
		}

		$expiration_date = null;
		if ( preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $request['expiration_date'] ) === 1 ) {
			$expiration_date = $request['expiration_date'];
		}

		$sold_date = null;
		if ( preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $request['sold_date'] ) === 1 ) {
			$sold_date = $request['sold_date'];
		}

		$delivery_limit           = (int) $request['delivery_limit'];
		$remaining_delivery_times = isset( $request['remaining_delivery_times'] ) ? (int) $request['remaining_delivery_times'] : $delivery_limit;

		$activation_limit      = (int) $request['activation_limit'];
		$remaining_activations = isset( $request['remaining_activations'] ) ? (int) $request['remaining_activations'] : $activation_limit;

		$validity = (int) $request['validity_days'];

		$order_id = (int) $request['order_id'];

		$license_status = sanitize_text_field( $request['license_status'] );

		$allow_duplicate = get_option( 'fslm_duplicate_license', '' );

		$result = array(
			"total"              => 0,
			"added"              => 0,
			"duplicate"          => 0,
			"duplicates_allowed" => $allow_duplicate == 'on'
		);

		$added_license_keys = array();

		$item_id = 0;

		if ( $order_id != 0 ) {
			$order       = wc_get_order( $order_id );
			$order_items = $order->get_items();

			$order_invalid_product_error   = true;
			$order_invalid_variation_error = true;

			foreach ( $order_items as $key => $value ) {
				if ( $value->get_product_id() == $product_id ) {
					$order_invalid_product_error = false;
					$item_id                     = $key;
				}

				if ( $variation_id != 0 && $value->get_variation_id() == $product_id ) {
					$order_invalid_variation_error = false;
				}
			}

			if ( $order_invalid_product_error ) {
				return $this->sign_response( FSLM_APIv3_Responses::ORDER_INVALID_PRODUCT );
			}

			if ( $variation_id != 0 && $order_invalid_variation_error ) {
				return $this->sign_response( FSLM_APIv3_Responses::ORDER_INVALID_VARIATION );
			}
		}

		foreach ( $license_keys as $license_key ) {

			$encrypted_license_keys = encrypt_decrypt( 'encrypt', $license_key, ENCRYPTION_KEY, ENCRYPTION_VI );

			$exist = $fs_wc_licenses_manager->licenseKeyExist( $encrypted_license_keys );

			if ( ( in_array( $license_key, $added_license_keys ) || $exist ) && $allow_duplicate != 'on' ) {
				$result['duplicate'] ++;
			} else {

				if ( in_array( $license_key, $added_license_keys ) || $exist ) {
					$result['duplicate'] ++;
				}

				$result['added'] ++;

				$data = array(
					'product_id'                => $product_id,
					'variation_id'              => $variation_id,
					'owner_first_name'          => $owner_first_name,
					'owner_last_name'           => $owner_last_name,
					'owner_email_address'       => $owner_email_address,
					'license_key'               => $encrypted_license_keys,
					'delivre_x_times'           => $delivery_limit,
					'remaining_delivre_x_times' => $remaining_delivery_times,
					'max_instance_number'       => $activation_limit,
					'number_use_remaining'      => $remaining_activations,
					'creation_date'             => date( 'Y-m-d' ),
					'activation_date'           => $activation_date,
					'expiration_date'           => $expiration_date,
					'sold_date'                 => $sold_date,
					'valid'                     => $validity,
					'order_id'                  => $order_id,
					'license_status'            => strtolower( $license_status )
				);

				$wpdb->insert( "{$wpdb->prefix}wc_fs_product_licenses_keys", $data );

				if ( $order_id != 0 ) {
					$license_id         = $wpdb->insert_id;
					$order_license_keys = $this->get_order_license_keys( $order_id );

					$key_found = 0;
					foreach ( $order_license_keys as $key => $order_license_key ) {
						if ( $order_license_key['license_key'] == $encrypted_license_keys ) {
							$key_found = $key;
						}
					}

					$new_meta = array(
						"license_id"          => $license_id,
						"item_id"             => $item_id,
						"product_id"          => $product_id,
						"variation_id"        => $variation_id,
						"license_key"         => $encrypted_license_keys,
						"max_instance_number" => $activation_limit,
						"visible"             => "Yes",
						"uses"                => "0",
						"expiration_date"     => $expiration_date
					);

					if ( $key_found != 0 ) {
						$order_license_keys[ $key_found ] = $new_meta;
					} else {
						$order_license_keys[] = $new_meta;
					}

					delete_post_meta( $order_id, 'fslm_json_license_details' );
					delete_post_meta( $order_id, 'fslm_licensed' );

					add_post_meta( $order_id, 'fslm_json_license_details', json_encode( $order_license_keys ), true );
					add_post_meta( $order_id, 'fslm_licensed', 'true', true );

				}

			}

			$added_license_keys[] = $license_key;
			$result['total'] ++;

		}

		$response         = FSLM_APIv3_Responses::LICENSE_CREATED;
		$response['data'] = $result;

		return $this->sign_response( $response );

	}

	/**
	 * Update license key
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function update_license_key( $request ) {
		global $wpdb;
		if ( ! $this->product_exists( (int) $request['product_id'] ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::INVALID_PRODUCT_ID );
		}

		if ( ! $this->variation_exists( (int) $request['variation_id'] ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::INVALID_VARIATION_ID );
		}

		if ( $request['order_id'] != 0 && get_post_type( $request['order_id'] ) != "shop_order" ) {
			return $this->sign_response( FSLM_APIv3_Responses::INVALID_ORDER_ID );
		}

		$license_key            = $request['license_key'];
		$encrypted_license_keys = encrypt_decrypt( 'encrypt', $license_key, ENCRYPTION_KEY, ENCRYPTION_VI );


		if ( ! $this->license_owner_check( $encrypted_license_keys ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::NOT_OWNER );
		}

		$verification = $this->verify_helper( $license_key );

		if ( $verification['code'] == '500' ) {
			$license_id = $wpdb->get_var( "SELECT license_id FROM {$wpdb->prefix}wc_fs_product_licenses_keys WHERE license_key='{$encrypted_license_keys}'" );

			$data = array();

			if ( isset( $request['product_id'] ) ) {
				$data['product_id'] = (int) $request['product_id'];
			}

			if ( isset( $request['variation_id'] ) ) {
				$data['variation_id'] = (int) $request['variation_id'];
			}

			if ( isset( $request['owner_first_name'] ) ) {
				$data['owner_first_name'] = sanitize_text_field( $request['owner_first_name'] );
			}

			if ( isset( $request['owner_last_name'] ) ) {
				$data['owner_last_name'] = sanitize_text_field( $request['owner_last_name'] );
			}

			if ( isset( $request['owner_email_address'] ) ) {
				$data['owner_email_address'] = sanitize_email( $request['owner_email_address'] );
			}

			if ( isset( $request['activation_date'] ) ) {
				if ( preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $request['activation_date'] ) === 1 ) {
					$data['activation_date'] = $request['activation_date'];
				}
			}

			if ( isset( $request['expiration_date'] ) ) {
				if ( preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $request['expiration_date'] ) === 1 ) {
					$data['expiration_date'] = $request['expiration_date'];
				}
			}

			if ( isset( $request['sold_date'] ) ) {
				if ( preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $request['sold_date'] ) === 1 ) {
					$data['sold_date'] = $request['sold_date'];
				}
			}

			if ( isset( $request['creation_date'] ) ) {
				if ( preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $request['creation_date'] ) === 1 ) {
					$data['creation_date'] = $request['creation_date'];
				}
			}

			if ( isset( $request['delivery_limit'] ) ) {
				$data['delivre_x_times'] = (int) $request['delivery_limit'];
			}

			if ( isset( $request['remaining_delivery_times'] ) ) {
				$data['remaining_delivre_x_times'] = (int) $request['remaining_delivery_times'];
			}

			if ( isset( $request['activation_limit'] ) ) {
				$data['max_instance_number'] = (int) $request['activation_limit'];
			}

			if ( isset( $request['remaining_activations'] ) ) {
				$data['number_use_remaining'] = (int) $request['remaining_activations'];
			}

			if ( isset( $request['validity_days'] ) ) {
				$data['valid'] = (int) $request['validity_days'];
			}

			if ( isset( $request['order_id'] ) ) {
				$data['order_id'] = (int) $request['order_id'];
			}

			if ( isset( $request['license_status'] ) ) {
				$data['license_status'] = sanitize_text_field( strtolower( $request['license_status'] ) );
			}

			$where = array(
				'license_id' => $license_id
			);

			$item_id = 0;

			if ( $request['order_id'] != 0 ) {
				$order       = wc_get_order( $request['order_id'] );
				$order_items = $order->get_items();

				$order_invalid_product_error   = true;
				$order_invalid_variation_error = true;

				foreach ( $order_items as $key => $value ) {
					if ( $value->get_product_id() == $request['product_id'] ) {
						$order_invalid_product_error = false;
						$item_id                     = $key;
					}

					if ( $request['variation_id'] != 0 && $value->get_variation_id() == $request['variation_id'] ) {
						$order_invalid_variation_error = false;
					}
				}

				if ( $order_invalid_product_error ) {
					return $this->sign_response( FSLM_APIv3_Responses::ORDER_INVALID_PRODUCT );
				}

				if ( $request['variation_id'] != 0 && $order_invalid_variation_error ) {
					return $this->sign_response( FSLM_APIv3_Responses::ORDER_INVALID_VARIATION );
				}
			}

			$wpdb->update( "{$wpdb->prefix}wc_fs_product_licenses_keys", $data, $where );

			if ( $request['order_id'] != 0 ) {
				$license_id         = $wpdb->insert_id;
				$order_license_keys = $this->get_order_license_keys( $request['order_id'] );

				$key_found = 0;
				foreach ( $order_license_keys as $key => $order_license_key ) {
					if ( $order_license_key['license_key'] == $encrypted_license_keys ) {
						$key_found = $key;
					}
				}

				$new_meta = array(
					"license_id"          => $license_id,
					"item_id"             => $item_id,
					"product_id"          => $request['product_id'],
					"variation_id"        => $request['variation_id'],
					"license_key"         => $encrypted_license_keys,
					"max_instance_number" => $request['activation_limit'],
					"visible"             => "Yes",
					"uses"                => "0",
					"expiration_date"     => $request['expiration_date']
				);

				if ( $key_found != 0 ) {
					$order_license_keys[ $key_found ] = $new_meta;
				} else {
					$order_license_keys[] = $new_meta;
				}

				delete_post_meta( $request['order_id'], 'fslm_json_license_details' );
				delete_post_meta( $request['order_id'], 'fslm_licensed' );

				add_post_meta( $request['order_id'], 'fslm_json_license_details', json_encode( $order_license_keys ),
					true );
				add_post_meta( $request['order_id'], 'fslm_licensed', 'true', true );

			}

			return $this->sign_response( FSLM_APIv3_Responses::LICENSE_UPDATED );

		} else {
			return $this->sign_response( $verification );
		}
	}

	/**
	 * Update license key
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function delete_license_key( $request ) {
		global $wpdb;

		$encrypted_license_keys = encrypt_decrypt( 'encrypt', $request['license_key'], ENCRYPTION_KEY, ENCRYPTION_VI );

		$verification = $this->verify_helper( $request['license_key'] );

		if ( $verification['code'] == '500' ) {
			$wpdb->delete(
				"{$wpdb->prefix}wc_fs_product_licenses_keys",
				array(
					'license_key' => $encrypted_license_keys
				)
			);

			return $this->sign_response( FSLM_APIv3_Responses::LICENSE_DELETED );

		} else {
			return $this->sign_response( $verification );
		}
	}

	/**
	 * Add license key meta
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function add_license_key_meta( $request ) {
		global $wpdb;

		$encrypted_license_keys = encrypt_decrypt( 'encrypt', $request['license_key'], ENCRYPTION_KEY, ENCRYPTION_VI );

		if ( ! $this->license_owner_check( $encrypted_license_keys ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::NOT_OWNER );
		}

		$verification = $this->verify_helper( $request['license_key'] );

		if ( $verification['code'] == '500' ) {
			$license_id = $wpdb->get_var( "SELECT license_id FROM {$wpdb->prefix}wc_fs_product_licenses_keys WHERE license_key='{$encrypted_license_keys}'" );

			$meta_key   = sanitize_text_field( $request['meta_key'] );
			$meta_value = sanitize_text_field( $request['meta_value'] );

			// API users can set 'admin only' meta keys, but they can't delete/update them once they are added.
			// Only an authenticated admin or shop manager can update/delete admin only meta keys.
			$admin_only = sanitize_text_field( $request['admin_only'] ) == 'true' ? 1 : 0;


			$key_id = $wpdb->get_var( "SELECT id FROM {$wpdb->prefix}wc_fs_license_key_meta WHERE license_id='{$license_id}' AND meta_key = '{$meta_key}'" );

			if ( $key_id ) {
				return $this->sign_response( FSLM_APIv3_Responses::META_KEY_ALREADY_EXISTS );
			} else {
				$result = $wpdb->insert(
					"{$wpdb->prefix}wc_fs_license_key_meta",
					array(
						'license_id' => $license_id,
						'meta_key'   => $meta_key,
						'meta_value' => $meta_value,
						'admin_only' => $admin_only
					)
				);

				if ( $result >= 1 ) {
					return $this->sign_response( FSLM_APIv3_Responses::META_KEY_ADDED );
				} else {
					return $this->sign_response( FSLM_APIv3_Responses::ERROR );
				}
			}

		} else {
			return $this->sign_response( $verification );
		}
	}

	/**
	 * Update license key meta
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function update_license_key_meta( $request ) {
		global $wpdb;

		$encrypted_license_keys = encrypt_decrypt( 'encrypt', $request['license_key'], ENCRYPTION_KEY, ENCRYPTION_VI );

		if ( ! $this->license_owner_check( $encrypted_license_keys ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::NOT_OWNER );
		}

		$verification = $this->verify_helper( $request['license_key'] );

		if ( $verification['code'] == '500' ) {
			$license_id = $wpdb->get_var( "SELECT license_id FROM {$wpdb->prefix}wc_fs_product_licenses_keys WHERE license_key='{$encrypted_license_keys}'" );

			$meta_key   = sanitize_text_field( $request['meta_key'] );
			$meta_value = sanitize_text_field( $request['meta_value'] );

			$key = $wpdb->get_row( "SELECT id, admin_only FROM {$wpdb->prefix}wc_fs_license_key_meta WHERE license_id='{$license_id}' AND meta_key = '{$meta_key}'" );

			if ( $key && $key->id ) {

				if ( $key->admin_only ) {
					$roles = wp_get_current_user()->roles;

					if ( ! in_array( 'administrator', $roles ) && ! in_array( 'shop_manager', $roles ) ) {
						return $this->sign_response( FSLM_APIv3_Responses::META_KEY_ADMIN_ONLY );
					}
				}


				$result = $wpdb->update(
					"{$wpdb->prefix}wc_fs_license_key_meta",
					array(
						'meta_value' => $meta_value
					),
					array(
						'license_id' => $license_id,
						'meta_key'   => $meta_key,
					)
				);

				if ( $result !== false ) {
					return $this->sign_response( FSLM_APIv3_Responses::META_KEY_UPDATED );
				}
			} else {
				return $this->sign_response( FSLM_APIv3_Responses::META_KEY_DOESNT_EXIST );
			}

		} else {
			return $this->sign_response( $verification );
		}
	}

	/**
	 * Delete license key meta
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function delete_license_key_meta( $request ) {
		global $wpdb;

		$encrypted_license_keys = encrypt_decrypt( 'encrypt', $request['license_key'], ENCRYPTION_KEY, ENCRYPTION_VI );

		if ( ! $this->license_owner_check( $encrypted_license_keys ) ) {
			return $this->sign_response( FSLM_APIv3_Responses::NOT_OWNER );
		}

		$verification = $this->verify_helper( $request['license_key'] );

		if ( $verification['code'] == '500' ) {
			$license_id = $wpdb->get_var( "SELECT license_id FROM {$wpdb->prefix}wc_fs_product_licenses_keys WHERE license_key='{$encrypted_license_keys}'" );

			$meta_key = sanitize_text_field( $request['meta_key'] );

			$key = $wpdb->get_row( "SELECT id, admin_only FROM {$wpdb->prefix}wc_fs_license_key_meta WHERE license_id='{$license_id}' AND meta_key = '{$meta_key}'" );

			if ( $key && $key->id ) {

				if ( $key->admin_only ) {
					$roles = wp_get_current_user()->roles;

					if ( ! in_array( 'administrator', $roles ) && ! in_array( 'shop_manager', $roles ) ) {
						return $this->sign_response( FSLM_APIv3_Responses::META_KEY_ADMIN_ONLY );
					}
				}


				$result = $wpdb->delete(
					"{$wpdb->prefix}wc_fs_license_key_meta",
					array(
						'license_id' => $license_id,
						'meta_key'   => $meta_key,
					)
				);

				if ( $result !== false ) {
					return $this->sign_response( FSLM_APIv3_Responses::META_KEY_DELETED );
				}
			} else {
				return $this->sign_response( FSLM_APIv3_Responses::META_KEY_DOESNT_EXIST );
			}

		} else {
			return $this->sign_response( $verification );
		}
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function delete_license_key_meta_permissions_check( $request ) {
		$endpoint = 'delete_license_key_meta';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function update_license_key_meta_permissions_check( $request ) {
		$endpoint = 'update_license_key_meta';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function add_license_key_meta_permissions_check( $request ) {
		$endpoint = 'add_license_key_meta';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function verify_permissions_check( $request ) {
		$endpoint = 'verify';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function activate_permissions_check( $request ) {
		$endpoint = 'activate';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function deactivate_permissions_check( $request ) {
		$endpoint = 'deactivate';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function get_license_details_permissions_check( $request ) {
		$endpoint = 'get_license_details';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function get_product_api_meta_permissions_check( $request ) {
		$endpoint = 'get_product_api_meta';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function get_license_status_permissions_check( $request ) {
		$endpoint = 'get_license_status';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function get_current_user_licenses_permissions_check( $request ) {
		$endpoint = 'get_current_user_licenses';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function register_license_key_permissions_check( $request ) {
		$endpoint = 'register_license_key';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function set_license_status_permissions_check( $request ) {
		$endpoint = 'set_license_status';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function create_license_key_permissions_check( $request ) {
		$endpoint = 'create_license_key';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function update_license_key_permissions_check( $request ) {
		$endpoint = 'update_license_key';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Check permissions
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function delete_license_key_permissions_check( $request ) {
		$endpoint = 'delete_license_key';

		return $this->permission_check( $endpoint );
	}

	/**
	 * Permission check
	 *
	 * @param $endpoint
	 *
	 * @return bool
	 */
	public function permission_check( $endpoint ) {
		global $fslm_admin_roles, $fslm_all_allowed;

		$current_user = wp_get_current_user();
		$roles        = $current_user->roles;

		foreach ( $roles as $role ) {
			$default = 'off';
			if ( in_array( $role, $fslm_admin_roles ) ||
			     in_array( $endpoint, $fslm_all_allowed ) ) {
				$default = 'on';
			}

			$permission = get_option( "fslm_api_v3_permission_{$role}_{$endpoint}", $default );

			if ( $permission == 'on' ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Verify license key
	 *
	 * @param $license_key_decrypted
	 * @param string $device_id
	 * @param bool $internal_use
	 *
	 * @return string[]
	 */
	public function verify_helper( $license_key_decrypted, $device_id = 'none', $internal_use = true ) {
		global $wpdb;

		$license_key = $license_key_decrypted;
		$license_key = encrypt_decrypt( 'encrypt', $license_key, ENCRYPTION_KEY, ENCRYPTION_VI );

		$query = $wpdb->get_row( "SELECT expiration_date, device_id, license_status FROM {$wpdb->prefix}wc_fs_product_licenses_keys WHERE license_key='{$license_key}'" );

		if ( $query ) {
			if ( $internal_use ) {
				return FSLM_APIv3_Responses::VALID;
			}

			$expiration_date = $query->expiration_date;

			if ( $device_id != 'none' ) {
				$device_id_json = json_decode( $query->device_id );
				if ( json_last_error() === JSON_ERROR_NONE && $query->device_id != "" && $query->device_id != null ) {
					if ( in_array( $device_id, $device_id_json ) ) {

						if ( ( strtotime( $expiration_date ) > time() || $expiration_date == '0000-00-00' || $expiration_date == '' || $expiration_date == null ) && strtolower( $query->license_status ) != 'expired' ) {
							return FSLM_APIv3_Responses::VALID;
						}

					} else {
						return FSLM_APIv3_Responses::INVALID_DEVICE_ID;
					}
				} else {
					return FSLM_APIv3_Responses::INVALID_DEVICE_ID;
				}
			}


			if ( ( strtotime( $expiration_date ) > time() || $expiration_date == '0000-00-00' || $expiration_date == '' || $expiration_date == null ) && strtolower( $query->license_status ) != 'expired' ) {
				return FSLM_APIv3_Responses::VALID;
			}

			unset( $query );

			return FSLM_APIv3_Responses::EXPIRED;

		}

		return FSLM_APIv3_Responses::INVALID_LICENSE_KEY;
	}

	/**
	 * Check if the user making the API request owns the licenses.
	 *
	 * @param $encrypted_license_key
	 *
	 * @return bool
	 */
	public function license_owner_check( $encrypted_license_key ) {
		global $wpdb;

		$roles = wp_get_current_user()->roles;

		// Admins and shop managers can access any license keys.
		if ( in_array( 'administrator', $roles ) || in_array( 'shop_manager', $roles ) ) {
			return true;
		}

		$license_key = $encrypted_license_key;

		$order_id = $wpdb->get_var( "SELECT order_id FROM {$wpdb->prefix}wc_fs_product_licenses_keys WHERE license_key='{$license_key}'" );
		if ( $order_id ) {
			$current_user_id = get_current_user_id();

			$user_id = $wpdb->get_var( "
                SELECT 
                    meta_value
                FROM 
                    {$wpdb->prefix}postmeta
                WHERE 
                    meta_key = '_customer_user'       
                    AND post_id = {$order_id}
                LIMIT 1
            " );

			if ( $user_id == $current_user_id ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if a product ID is valid
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function product_exists( $id ) {
		global $wpdb;

		$id = $wpdb->get_var( "
            SELECT 
                ID 
            FROM 
                {$wpdb->posts} 
            WHERE 
                post_type = 'product' AND post_status != 'auto-draft' AND ID = $id"
		);

		if ( $id ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if a variation ID is valid
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function variation_exists( $id ) {
		global $wpdb;

		if ( $id == 0 ) {
			return true;
		}

		$id = $wpdb->get_var( "
            SELECT 
                ID 
            FROM 
                {$wpdb->posts} 
            WHERE 
                post_type = 'product_variation' AND post_status != 'auto-draft' AND ID = $id"
		);

		if ( $id ) {
			return true;
		}

		return false;
	}

	/**
	 * Get order license keys
	 *
	 * @param $order_id
	 *
	 * @return mixed|null
	 */
	public function get_order_license_keys( $order_id ) {
		$meta = get_post_meta( $order_id, 'fslm_json_license_details', true );
		$json = str_replace( "\\", "", $meta );

		return json_decode( $json, true );
	}

	public function sign_response( $data ) {
		$data['api_timestamp'] = time();
		$response              = json_encode( $data );

		$private_key = get_option( 'fslm_api_v3_pk', '' );
		$passphrase  = get_option( 'fslm_api_v3_passphrase', '' );
		$algorithm   = get_option( 'fslm_api_v3_algo', '7' );
		$encode      = get_option( 'fslm_api_v3_encode', '' );

		if ( $encode == 'on' ) {
			$response = base64_encode( $response );
		}

		$binary_signature = '';
		$signature        = '';

		if ( extension_loaded( 'openssl' ) ) {
			if ( $private_key == '' ) {
				$signature = 'private key not set';
			} else {
				$pk_id     = openssl_pkey_get_private( $private_key, $passphrase );
				$is_signed = openssl_sign( $response, $binary_signature, $pk_id, (int) $algorithm );
				if ( $is_signed ) {
					$signature = base64_encode( $binary_signature );
				} else {
					$signature = openssl_error_string();
				}
			}
		} else {
			$signature = 'openssl not installed on your server';
		}

		return rest_ensure_response( array(
			'response'  => ( $encode == 'on' ? $response : $data ),
			'signature' => $signature
		) );
	}

	/**
	 * Create WooCommerce order
	 *
	 * @param $data
	 *
	 * @return array
	 * @throws WC_Data_Exception
	 */
	function create_wc_order( $data ) {
		$result = array();

		$order = wc_create_order( array(
			'status' => 'on-hold'
		) );

		// Set Billing and Shipping addresses
		foreach ( array( 'billing_', 'shipping_' ) as $type ) {
			foreach ( $data['address'] as $key => $value ) {
				if ( $type === 'shipping_' && in_array( $key, array( 'email', 'phone' ) ) ) {
					continue;
				}

				$type_key = $type . $key;

				if ( is_callable( array( $order, "set_{$type_key}" ) ) ) {
					$order->{"set_{$type_key}"}( $value );
				}
			}
		}

		// Set other details
		$order->set_created_via( 'License Manager API' );
		$order->set_customer_id( $data['user_id'] );
		$order->set_currency( get_woocommerce_currency() );
		$order->set_prices_include_tax( 'yes' === get_option( 'woocommerce_prices_include_tax' ) );
		$order->set_customer_note( isset( $data['order_comments'] ) ? $data['order_comments'] : '' );
		$order->set_payment_method( 'License Manager API' );

		$calculate_taxes_for = array(
			'country'  => $data['address']['country'],
			'state'    => $data['address']['state'],
			'postcode' => $data['address']['postcode'],
			'city'     => $data['address']['city']
		);


		// Line items
		foreach ( $data['line_items'] as $line_item ) {
			$args    = $line_item['args'];
			$product = wc_get_product( isset( $args['variation_id'] ) && $args['variation_id'] > 0 ? $args['variation_id'] : $args['product_id'] );
			$item_id = $order->add_product( $product, $line_item['quantity'], $line_item['args'] );

			$item = $order->get_item( $item_id, false );

			//$item->calculate_taxes($calculate_taxes_for);
			$item->save();

			$result['item_id'] = $item_id;
		}


		// Coupon items
		if ( isset( $data['coupon_items'] ) ) {
			foreach ( $data['coupon_items'] as $coupon_item ) {
				$order->apply_coupon( sanitize_title( $coupon_item['code'] ) );
			}
		}

		// Fee items
		if ( isset( $data['fee_items'] ) ) {
			foreach ( $data['fee_items'] as $fee_item ) {
				$item = new WC_Order_Item_Fee();

				$item->set_name( $fee_item['name'] );
				$item->set_total( $fee_item['total'] );
				$tax_class = isset( $fee_item['tax_class'] ) && $fee_item['tax_class'] != 0 ? $fee_item['tax_class'] : 0;
				$item->set_tax_class( $tax_class ); // O if not taxable

				$item->calculate_taxes( $calculate_taxes_for );

				$item->save();
				$order->add_item( $item );
			}
		}


		// Set calculated totals
		$order->calculate_totals();

		$order_id = $order->get_id();

		$result['order_id'] = $order_id;

		$json_license_details            = $data['license_key'];
		$json_license_details['item_id'] = $result['item_id'];

		add_post_meta( $result['order_id'], 'fslm_json_license_details', json_encode( array( $json_license_details ) ),
			true );
		add_post_meta( $result['order_id'], 'fslm_licensed', 'true', true );

		$order->update_status( $data['order_status']['status'], $data['order_status']['note'], true );

		return $result;
	}
}
