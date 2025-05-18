<div class="ui segment arconfig-panel hidden" id="arcontactus-about">
    <div class="text-center">
        <a href="<?php echo admin_url('options-general.php?page=ar-contactus-key-config&debug=' . (AR_CONTACTUS_DEBUG? '0' : '1')) ?>" class="arcu-debug-button <?php echo AR_CONTACTUS_DEBUG? 'active' : '' ?>" 
           title="<?php echo AR_CONTACTUS_DEBUG? __('Debug mode is active. Click to disable debug mode.', 'ar-contactus') :  __('Debug mode is off. Click to activate debug mode.', 'ar-contactus') ?>">
            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bug" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-bug fa-w-16 fa-2x"><path fill="currentColor" d="M511.988 288.9c-.478 17.43-15.217 31.1-32.653 31.1H424v16c0 21.864-4.882 42.584-13.6 61.145l60.228 60.228c12.496 12.497 12.496 32.758 0 45.255-12.498 12.497-32.759 12.496-45.256 0l-54.736-54.736C345.886 467.965 314.351 480 280 480V236c0-6.627-5.373-12-12-12h-24c-6.627 0-12 5.373-12 12v244c-34.351 0-65.886-12.035-90.636-32.108l-54.736 54.736c-12.498 12.497-32.759 12.496-45.256 0-12.496-12.497-12.496-32.758 0-45.255l60.228-60.228C92.882 378.584 88 357.864 88 336v-16H32.666C15.23 320 .491 306.33.013 288.9-.484 270.816 14.028 256 32 256h56v-58.745l-46.628-46.628c-12.496-12.497-12.496-32.758 0-45.255 12.498-12.497 32.758-12.497 45.256 0L141.255 160h229.489l54.627-54.627c12.498-12.497 32.758-12.497 45.256 0 12.496 12.497 12.496 32.758 0 45.255L424 197.255V256h56c17.972 0 32.484 14.816 31.988 32.9zM257 0c-61.856 0-112 50.144-112 112h224C369 50.144 318.856 0 257 0z" class=""></path></svg>
        </a>
        <a href="https://plugins.areama.net/ar-contactus/">
            <img src="<?php echo AR_CONTACTUS_PLUGIN_URL . 'res/img/logo-big.png' ?>" alt="logo" />
        </a>
        <p class="hero"><?php echo __('All-in-one contact us button', 'ar-contactus') ?></p>
        <p class="muted">Ver. <?php echo AR_CONTACTUS_VERSION ?></p>
        <p>
            <?php echo __('This plugin displays contact button with customizable menu on every page. So your customers will able to contact you easily.', 'ar-contactus') ?>
        </p>
        
        <div class="arcu-activation">
            <div>
                <?php if (!isset($activated['success']) || !$activated['success']){?>
                <div>
                    <label><?php echo __('Purchase code', 'ar-contactus') ?></label>
                </div>
                <div>
                    <div class="field">
                        <input placeholder="" id="arcontactus_pcode" autocomplete="off" value="<?php echo get_option('AR_CONTACTUS_PURCHASE_CODE', '') ?>" data-default="" data-serializable="true" name="pcode" type="text">
                        <div class="errors" id="arcontactus_activation_error"></div>
                    </div>
                </div>
                <div class="actions">
                    <button type="button" onclick="arCU.activate();" class="button button-primary button-large"><?php echo __('Activate', 'ar-contactus') ?></button>
                </div>
                <?php } elseif($activated['success']) { ?>
                    <div><?php echo __('Purchase code: ', 'ar-contactus') ?> <b><?php echo get_option('AR_CONTACTUS_PURCHASE_CODE', '') ?></b></div>
                    <div class="errors" id="arcontactus_activation_error"></div>
                    <div class="actions">
                        <button type="button" onclick="arCU.deactivate();" class="button button-primary button-large"><?php echo __('Deactivate for this domain', 'ar-contactus') ?></button>
                    </div>
                    <div class="arcu-update-channel">
                        
                        <form id="arcu_channel_form" method="POST">
                            <?php echo __('Updates channel:', 'ar-contactus') ?>
                            <select name="arcu_channel" onchange="document.getElementById('arcu_channel_form').submit();">
                                <option <?php echo $arcu_channel == 'prod'? 'selected=""' : '' ?> value="prod"><?php echo __('Production', 'ar-contactus') ?></option>
                                <option <?php echo $arcu_channel == 'beta'? 'selected=""' : '' ?> value="beta"><?php echo __('Beta', 'ar-contactus') ?></option>
                            </select>
                        </form>
                        
                    </div>
                <?php } ?>
            </div>
        </div>

        <p>
            <?php echo sprintf(__('We hope you would find this plugin useful and would have 1 minute to %s, this encourage our support and developers.', 'ar-contactus'), '<a href="https://codecanyon.net/downloads" target="_blank">' . __('give us excellent rating', 'ar-contactus') . '</a>') ?>
        </p>
        <p>
            <?php echo sprintf(__('If you like this plugin please follow us on %s.', 'ar-contactus'), '<a href="https://codecanyon.net/user/areama/follow" target="_blank">codecanyon.net</a>') ?>
        </p>
        <p class="text-center">
            <a href="https://codecanyon.net/downloads" target="_blank">
                <img src="<?php echo AR_CONTACTUS_PLUGIN_URL . 'res/img/5-stars.png' ?>" alt="<?php echo __('5 stars', 'ar-contactus') ?>">
            </a>
        </p>
        <p>
            <?php echo sprintf(__('If you have any questions or suggestions about this plugin, please %s', 'ar-contactus'), '<a href="https://codecanyon.net/user/areama" target="_blank">' . __('contact us', 'ar-contactus') . '</a>') ?>
        </p>
        <h2>
            <?php echo __('Also please checkout our other plugins that can help improve your site!', 'ar-contactus') ?><br>
        </h2>
        <div class="ui grid" id="ar-plugins">
            <div class="row">
                <div class="eight wide column">
                    <a class="ar-plugin" href="https://codecanyon.net/item/all-product-images-or-second-image-rollover-on-hover/23670584?ref=areama" target="_blank">
                        <img src="https://s3.envato.com/files/263719397/logo.png" />
                        <div class="ar-plugin-content">
                            <div class="ar-plugin-title">
                                All product images or second image (rollover) on hover
                            </div>
                            <div class="ar-plugin-desc">
                                This plugin allows to display all product images or display second product image on mouse hover in product list. 
                                <strong>4 desktop modes</strong>, <strong>mobile friendly</strong>. Mobile swipe functionality supported! Highly customizable!
                            </div>
                        </div>
                    </a>
                </div>
                <div class="eight wide column">
                    <a class="ar-plugin" href="https://codecanyon.net/item/live-sales-popup-product-sold-notification/23600180?ref=areama" target="_blank">
                        <img src="https://s3.envato.com/files/263379131/logo.png" />
                        <div class="ar-plugin-content">
                            <div class="ar-plugin-title">
                                Live Sales Popup: product sold notification
                            </div>
                            <div class="ar-plugin-desc">
                                <strong>Boost your sales</strong> with the lightweight ‘influential marketing’ tool for customer enagagement and motivation. 
                                Share <strong>recent orders</strong> with your website visitors to proove your sales, 
                                show notifications when someone <strong>adds product to cart</strong>, 
                                show notification with <strong>viewers count of current product</strong> to push visitor to purchase.
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <p>
            <a target="_blank" href="https://codecanyon.net/user/areama/portfolio?ref=areama"><?php echo __('View all our plugins &gt;&gt;&gt;', 'ar-contactus') ?></a>
        </p>
    </div>
</div>