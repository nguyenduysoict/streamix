<?php
// Add custom Theme Functions here

//Rút gọn tiêu đề sản phẩm
add_filter( 'the_title', 'short_title_product', 10, 2 );
function short_title_product( $title, $id ) {
    if (get_post_type( $id ) === 'product' & !is_single() ) {
        return wp_trim_words( $title, 5 ); 
    } else {
        return $title;
    }
}

//Content product

function add_content_after_product_title( $product ) {?>
  <?php
   
    $sold_product = get_field('san_pham_da_ban');
     
  ?>
  <?php 
    global $post;
    $terms = get_the_terms($post->ID, 'product_cat');
    foreach ($terms as $term) {
        if ($term->parent == 0) { // Kiểm tra nếu là danh mục chính
            $product_cat_id = $term->term_id;
            $product_cat_name = $term->name;
            break;
        }
    }
     
  ?>
    <div class="cat-content">
      <span class="product-item-cat"><?php echo $product_cat_name; ?></span>
      <span class="sales">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="24px" width="24px" fill="#a2a2b0">
              <path d="M0 0h24v24H0V0z" fill="none"></path>
              <path d="M17 18c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm0-3l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1v2h2l3.6 7.59L3.62 17H19v-2H7z"></path>
          </svg>
          <span>
          <?php echo $sold_product; ?>
          </span>
      </span>
    </div>
    <?php
  
}
add_action( 'woocommerce_shop_loop_item_title', 'add_content_after_product_title', 10 );


function add_buy_button_after_product_title( $product ) {
    echo '<div class="product-item__btn btn-green">
      <a href="' . esc_url( get_permalink( $product_id ) ) . '">
        Buy
        <span class="btn-content">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#FFFFFF">
            <path d="M0 0h24v24H0V0z" fill="none"></path>
            <path d="M17 18c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm0-3l1.1-2h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1v2h2l3.6 7.59L3.62 17H19v-2H7z"></path>
          </svg>
        </span>
      </a>
    </div>';
  }
  add_action( 'woocommerce_after_shop_loop_item_title', 'add_buy_button_after_product_title', 10 );
  


//Chỉnh sửa position
function shuffle_variable_product_elements(){
  if ( is_product() ) {
      global $post;
      $product = wc_get_product( $post->ID );
      if ( $product->is_type( 'variable' ) ) {
          remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
          add_action( 'woocommerce_before_variations_form', 'woocommerce_single_variation', 20 );

          // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
          // add_action( 'woocommerce_before_variations_form', 'woocommerce_template_single_title', 10 );

          // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
          // add_action( 'woocommerce_before_variations_form', 'woocommerce_template_single_excerpt', 30 );
      }
  }
}
add_action( 'woocommerce_before_single_product', 'shuffle_variable_product_elements' );

function custom_flatsome_woocommerce_login_icon() {
  ?>
  <div class="custom-woocommerce-login-icon">
      <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>">
          <i class="fas fa-user-circle"></i> <span>Đăng nhập</span>
      </a>
  </div>
  <?php
}

add_action('flatsome_after_account_user', 'custom_flatsome_woocommerce_login_icon');


/*
* Add quick buy button go to checkout after click
* Author: levantoan.com
*/
add_action('woocommerce_after_add_to_cart_button','devvn_quickbuy_after_addtocart_button');
function devvn_quickbuy_after_addtocart_button(){
    global $product;
    ?>
    <style>
        .devvn-quickbuy button.single_add_to_cart_button.loading:after {
            display: none;
        }
        .devvn-quickbuy button.single_add_to_cart_button.button.alt.loading {
            color: #fff;
            pointer-events: none !important;
        }
        .devvn-quickbuy button.buy_now_button {
            position: relative;
            color: rgba(255,255,255,0.05);
        }
        .devvn-quickbuy button.buy_now_button:after {
            animation: spin 500ms infinite linear;
            border: 2px solid #fff;
            border-radius: 32px;
            border-right-color: transparent !important;
            border-top-color: transparent !important;
            content: "";
            display: block;
            height: 16px;
            top: 50%;
            margin-top: -8px;
            left: 50%;
            margin-left: -8px;
            position: absolute;
            width: 16px;
        }
    </style>
    <button type="button" class="button buy_now_button">
        <?php _e('Mua ngay', 'devvn'); ?>
    </button>
    <input type="hidden" name="is_buy_now" class="is_buy_now" value="0" autocomplete="off"/>
    <script>
        jQuery(document).ready(function(){
            jQuery('.is_buy_now').val('0');
            jQuery('body').on('click', '.buy_now_button', function(e){
                e.preventDefault();
                var thisParent = jQuery(this).parents('form.cart');
                if(jQuery('.single_add_to_cart_button', thisParent).hasClass('disabled')) {
                    jQuery('.single_add_to_cart_button', thisParent).trigger('click');
                    return false;
                }
                thisParent.addClass('devvn-quickbuy');
                jQuery('.is_buy_now', thisParent).val('1');
                jQuery('.single_add_to_cart_button', thisParent).trigger('click');
            });
        });
        jQuery( document.body ).on( 'added_to_cart', function (e, fragments, cart_hash, addToCartButton){
            let thisForm  = addToCartButton.closest('.cart');
            let is_buy_now = parseInt(jQuery('.is_buy_now', thisForm).val()) || 0;
            if(is_buy_now === 1 && typeof wc_add_to_cart_params !== "undefined") {
                window.location = wc_add_to_cart_params.cart_url;
            }
        });
    </script>
    <?php
}
add_filter('woocommerce_add_to_cart_redirect', 'redirect_to_checkout');
function redirect_to_checkout($redirect_url) {
    if(!get_theme_mod( 'ajax_add_to_cart' )) {
        if (isset($_REQUEST['is_buy_now']) && $_REQUEST['is_buy_now'] && get_option('woocommerce_cart_redirect_after_add') !== 'yes') {
            $redirect_url = wc_get_checkout_url(); //or wc_get_cart_url()
        }
    }
    return $redirect_url;
}
add_filter('woocommerce_get_script_data', 'devvn_woocommerce_get_script_data', 10, 2);
function devvn_woocommerce_get_script_data($params, $handle) {
    if($handle == 'wc-add-to-cart'){
        $params['cart_url'] = wc_get_checkout_url();
    }
    return $params;
}


//Tách các tab của product single
if ( ! function_exists( 'woocommerce_output_product_data_tabs' ) ) {
  function woocommerce_output_product_data_tabs() {
     wc_get_template( 'single-product/tabs/tabs.php' );
  }
}
function woocommerce_output_product_data_tabs() {
  $product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
  if ( empty( $product_tabs ) ) return;
  echo '<div class="woocommerce-tabs wc-tabs-wrapper">';
  foreach ( $product_tabs as $key => $product_tab ) {
     ?>
        <div id="tab-<?php echo esc_attr( $key ); ?>">
           <?php
           if ( isset( $product_tab['callback'] ) ) {
              call_user_func( $product_tab['callback'], $key, $product_tab );
           }
           ?>
        </div>
     <?php         
  }
  echo '</div>';
}


/*
* Author: GiuseArt - https://giuseart.com
* Đoạn code thu gọn nội dung bao gồm cả nút xem thêm và thu gọn lại sau khi đã click vào xem thêm
*/
add_action('wp_footer','giuseart_readmore_flatsome');
function giuseart_readmore_flatsome(){
    ?>
    <style>
        .single-product div#tab-description {
            overflow: hidden;
            position: relative;
            padding-bottom: 25px;
        }
        .fix_height{
            max-height: 800px;
            overflow: hidden;
            position: relative;
        }
        .single-product .tab-panels div#tab-description.panel:not(.active) {
            height: 0 !important;
        }
        .giuseart_readmore_flatsome {
            text-align: center;
            cursor: pointer;
            position: absolute;
            z-index: 10;
            bottom: 0;
            width: 100%;
            background: #fff;
        }
        .giuseart_readmore_flatsome:before {
            height: 55px;
            margin-top: -45px;
            content: "";
            background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
            background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 100%);
            background: linear-gradient(to bottom, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff00', endColorstr='#ffffff',GradientType=0 );
            display: block;
        }
        .giuseart_readmore_flatsome a {
          color: #f27474;
          display: block;
          border: solid 2px;
          border-radius: 9px;
        }
        .giuseart_readmore_flatsome a:after {
            content: '';
            width: 0;
            right: 0;
            border-top: 6px solid #318A00;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            display: inline-block;
            vertical-align: middle;
            margin: -2px 0 0 5px;
        }
        .giuseart_readmore_flatsome_less a:after {
            border-top: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-bottom: 6px solid #318A00;
        }
        .giuseart_readmore_flatsome_less:before {
            display: none;
        }
    </style>
    <script>
        (function($){
            $(document).ready(function(){
                $(window).on('load', function(){
                    if($('.single-product div#tab-description').length > 0){
                        let wrap = $('.single-product div#tab-description');
                        let current_height = wrap.height();
                        let your_height = 800;
                        if(current_height > your_height){
                            wrap.addClass('fix_height');
                            wrap.append(function(){
                                return '<div class="giuseart_readmore_flatsome giuseart_readmore_flatsome_more"><a title="Xem thêm" href="javascript:void(0);">Xem thêm</a></div>';
                            });
                            wrap.append(function(){
                                return '<div class="giuseart_readmore_flatsome giuseart_readmore_flatsome_less" style="display: none;"><a title="Xem thêm" href="javascript:void(0);">Thu gọn</a></div>';
                            });
                            $('body').on('click','.giuseart_readmore_flatsome_more', function(){
                                wrap.removeClass('fix_height');
                                $('body .giuseart_readmore_flatsome_more').hide();
                                $('body .giuseart_readmore_flatsome_less').show();
                            });
                            $('body').on('click','.giuseart_readmore_flatsome_less', function(){
                                wrap.addClass('fix_height');
                                $('body .giuseart_readmore_flatsome_less').hide();
                                $('body .giuseart_readmore_flatsome_more').show();
                            });
                        }
                    }
                });
            });
        })(jQuery);
    </script>
    <?php
}

//Thay đổi field trang checkout
add_filter( 'woocommerce_checkout_fields' , 'custom_checkout_form' );
function custom_checkout_form( $fields ) {
    unset($fields['billing']['billing_postcode']); //Ẩn postCode
    unset($fields['billing']['billing_state']); //Ẩn bang hạt
    unset($fields['billing']['billing_country']);// Ẩn quốc gia
    unset($fields['billing']['billing_address_2']); //billing_company
    unset($fields['billing']['billing_address_1']); //billing_company
    
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['order_comments']);// Ẩn quốc gia
    unset($fields['billing']['billing_city']); //Ẩn select box chọn thành phố
    //unset($fields['billing']['billing_email']); 
     $fields['billing']['billing_first_name']['placeholder'] = "VD: Nguyễn Văn A";
     $fields['billing']['billing_phone']['placeholder'] = "0988xxxxxx";
     $fields['billing']['billing_email']['placeholder'] = "your_email@gmail.com"; 
    return $fields;
}
function custom_checkout_field_label( $fields ) {
    // $fields['address_1']['label'] = 'Địa chỉ nhận hàng';
 
    $fields['first_name']['label'] = 'Họ và tên';
    return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'custom_checkout_field_label' );

//Get reviews count woo to shortcode
function get_product_reviews_count_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => null,
    ], $atts, 'product_reviews_count');

    if (!$atts['id']) {
        global $product;
        $product_id = $product->get_id();
    } else {
        $product_id = $atts['id'];
    }

    $product = wc_get_product($product_id);
    return $product->get_review_count();
}
add_shortcode('product_reviews_count', 'get_product_reviews_count_shortcode');

//footer menu
function register_footer_menu_mobile() {
	register_nav_menus(
		array( 'footer-menu-mobile' => __( 'Footer Menu Mobile' ) )
	);}
add_action( 'init', 'register_footer_menu_mobile' );

function add_footer_menu_mobile() {
	wp_nav_menu( array( 'theme_location' => 'footer-menu-mobile', 'container_class' => 'footer-menu-mobile' ) );
}
add_action('flatsome_after_header', 'add_footer_menu_mobile');

// Add Icon Menu in Widget
add_filter('wp_nav_menu_args', 'wpex_ux_menu_icon');
add_filter('widget_nav_menu_args', 'wpex_ux_menu_icon');
function wpex_ux_menu_icon($args) {
   return array_merge( $args, array(
	  'walker' => new FlatsomeNavDropdown(),
   ) );
}


