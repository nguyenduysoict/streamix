<?php
/*
* Plugin Name: DevVN - Woocommerce Reviews
* Version: 1.4.1
* Requires PHP: 7.2
* Description: Thay đổi giao diện phần đánh giá và thêm phần thảo luận cho chi tiết sản phẩm trong Woocommerce
* Author: Lê Văn Toản
* Author URI: https://levantoan.com/
* Plugin URI: https://levantoan.com/san-pham/devvn-woocommerce-reviews/
* Text Domain: devvn-reviews
* Domain Path: /languages
* WC requires at least: 3.5.4
* WC tested up to: 7.0.1
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !defined( 'DEVVN_REVIEWS_BASENAME' ) )
    define( 'DEVVN_REVIEWS_BASENAME', plugin_basename( __FILE__ ) );

if ( !defined( 'DEVVN_REVIEWS_VERSION_NUM' ) )
    define( 'DEVVN_REVIEWS_VERSION_NUM', '1.4.1' );


    include 'devvn-woocommerce-reviews-main.php';
