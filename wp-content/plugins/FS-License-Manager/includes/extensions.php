<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once( 'functions.php' ); ?>

<div class="wrap fslm fslm-extensions">

	<?php $notice = wclm_get_extensions_page_notice();

	if ( $notice != '' ) { ?>
		<div class="notice notice-success">
			<p style="margin-bottom: 15px"><strong><?php echo esc_html( $notice ) ?></strong></p>
		</div>
	<?php } ?>

	<h1><?php echo __( 'Extensions', 'fslm' ); ?></h1>

	<div class="postbox">

		<ul class="products addons-products-two-column">
			<li class="product">
				<div class="product-details">
					<div class="product-text-container">
						<h3>
							<a target="_blank"
							   href="https://codecanyon.net/item/woocommerce-license-manager-subscriptions-integration-addon/48905206?ref=firassaidi">
								Subscriptions Integration
							</a>
						</h3>
						<div class="product-developed-by">
							Developed by
							<a class="product-vendor-link"
							   target="_blank"
							   href="https://codecanyon.net/item/woocommerce-license-manager-subscriptions-integration-addon/48905206?ref=firassaidi"
							   target="_blank">
								Firas Saidi
							</a>
						</div>
						<p>
							<?php esc_html_e( 'Adds support for license keys that can be renewed and expire when the subscription expires', 'fslm' ) ?>
						</p>
					</div>
					<span class="product-img-wrap">
                      <img src="https://s3.envato.com/files/472579128/logo.png">
                    </span>
				</div>
				<div class="product-footer">
					<div class="product-price-and-reviews-container">

					</div>
					<a class="button"
					   target="_blank"
					   href="https://codecanyon.net/item/woocommerce-license-manager-subscriptions-integration-addon/48905206?ref=firassaidi">
						<?php esc_html_e( 'View details', 'fslm' ) ?>
					</a>
				</div>
			</li>


			<li class="product">
				<div class="product-details">
					<div class="product-text-container">
						<h3>
							<a target="_blank"
							   href="https://codecanyon.net/item/pools-for-woocommerce-license-manager/38136376?ref=firassaidi">
								Pools For WooCommerce License Manager
							</a>
						</h3>
						<div class="product-developed-by">
							Developed by
							<a class="product-vendor-link"
							   target="_blank"
							   href="https://codecanyon.net/item/pools-for-woocommerce-license-manager/38136376?ref=firassaidi"
							   target="_blank">
								Firas Saidi
							</a>
						</div>
						<p>
							<?php esc_html_e( 'Create groups of license keys that can be assigned to multiple products. Multilingual plugins create a new product for each language', 'fslm' ) ?>
						</p>
					</div>
					<span class="product-img-wrap">
                      <img src="https://s3.envato.com/files/395179531/thumb.png">
                    </span>
				</div>
				<div class="product-footer">
					<div class="product-price-and-reviews-container">

					</div>
					<a class="button"
					   target="_blank"
					   href="https://codecanyon.net/item/pools-for-woocommerce-license-manager/38136376?ref=firassaidi">
						<?php esc_html_e( 'View details', 'fslm' ) ?>
					</a>
				</div>
			</li>


			<li class="product">
				<div class="product-details">
					<div class="product-text-container">
						<h3>
							<a target="_blank"
							   href="https://codecanyon.net/item/woocommerce-license-manager-register-product-addon/48712768?ref=firassaidi">
								Register Product
							</a>
						</h3>
						<div class="product-developed-by">
							Developed by
							<a class="product-vendor-link"
							   target="_blank"
							   href="https://codecanyon.net/item/woocommerce-license-manager-register-product-addon/48712768?ref=firassaidi"
							   target="_blank">
								Firas Saidi
							</a>
						</div>
						<p>
							<?php esc_html_e( 'Give your customers the option to register license keys purchased through your retail partners.', 'fslm' ) ?>
						</p>
					</div>
					<span class="product-img-wrap">
                      <img src="https://s3.envato.com/files/471234894/thumb.jpg">
                    </span>
				</div>
				<div class="product-footer">
					<div class="product-price-and-reviews-container">

					</div>
					<a class="button"
					   target="_blank"
					   href="https://codecanyon.net/item/woocommerce-license-manager-register-product-addon/48712768?ref=firassaidi">
						<?php esc_html_e( 'View details', 'fslm' ) ?>
					</a>
				</div>
			</li>
		</ul>
	</div>
</div>
