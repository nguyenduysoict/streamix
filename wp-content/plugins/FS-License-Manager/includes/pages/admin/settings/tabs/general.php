<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<form method="post" action="options.php">
    <?php
    settings_fields('fslm_general_option_group');
    do_settings_sections('fslm_general_option_group');
    ?>

    <h3><?php echo __('UI Settings', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Number of rows per page', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" type="number" name="fslm_nb_rows_by_page" min="1"
                   value="<?php echo esc_attr(get_option('fslm_nb_rows_by_page', '15')); ?>">
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Number of rows for filters', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" type="number" name="fslm_nb_rows_by_page_filter" min="0"
                   value="<?php echo esc_attr(get_option('fslm_nb_rows_by_page_filter', '100')); ?>">

            <div class="helper">?
                <div class="tip">
			        <?php echo __('Set to 0 to disable limit.', 'fslm'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Show Admin bar notifications', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox"
                   name="fslm_show_adminbar_notifs"
                <?php echo esc_attr(get_option('fslm_show_adminbar_notifs', 'on')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>



    <div class="input-box">
        <div class="label">
            <span><?php echo __('Show the purchased license key table at the top of the thank-you page:', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox"
                   name="fslm_show_on_top"
				<?php echo esc_attr(get_option('fslm_show_on_top', 'off')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>


    <div class="input-box">
        <div class="label">
            <span><?php echo __('Allow The Customer to checkout Even If There is no license Keys for a Licensed Product',
                    'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox"
                   name="fslm_enable_cart_validation"
                <?php echo esc_attr(get_option('fslm_enable_cart_validation', '')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Allow Guest Customers To See The License Keys In The Thank-you Page',
                    'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_guest_customer"
                <?php echo esc_attr(get_option('fslm_guest_customer', 'on')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('License Key Generator Characters', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" type="text" name="fslm_generator_chars"
                   value="<?php echo esc_attr(get_option('fslm_generator_chars', '0123456789ABCDEF')); ?>">
            <div class="helper">?
                <div class="tip">
                    <?php echo __('Characters that can be used to generate license keys', 'fslm'); ?>
                </div>
            </div>
        </div>

    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Show Available License Keys Count in the Products Table', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox"
                   name="fslm_show_available_license_keys_column"
                <?php echo esc_attr(get_option('fslm_show_available_license_keys_column',
                    '')) == 'on' ? 'checked' : ''; ?>>
        </div>

    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Show Missing License Keys Count in the Orders Table', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox"
                   name="fslm_show_missing_license_keys_column"
                <?php echo esc_attr(get_option('fslm_show_missing_license_keys_column',
                    '')) == 'on' ? 'checked' : ''; ?>>
        </div>

    </div>


    <div class="input-box">
        <div class="label">
            <span><?php echo __('Hide the license keys on the thank-you and order page', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox"
                   name="fslm_hide_keys_on_site"
                <?php echo esc_attr(get_option('fslm_hide_keys_on_site', '')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>


    <h3><?php echo __('Property Names', 'fslm'); ?>:</h3>
    <div class="input-box">
        <div class="label">
            <span><?php echo __('Meta key name', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" type="text" name="fslm_meta_key_name"
                   value="<?php echo esc_attr(get_option('fslm_meta_key_name', 'License Key')); ?>">
            <div class="helper">?
                <div class="tip">
                    <?php echo __('The values that are already in the database are not going to be changed.<br>This text appears on the emails, order received page, and purchase history.',
                        'fslm'); ?>
                </div>
            </div>
        </div>

    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Meta key name(Plural form)', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" type="text" name="fslm_meta_key_name_plural"
                   value="<?php echo esc_attr(get_option('fslm_meta_key_name_plural', 'License Keys')); ?>">
        </div>

    </div>

    <h3><?php echo __('License Keys Delivery', 'fslm'); ?>:</h3>
    <div class="input-box">
        <div class="label">
            <span><?php echo __('Key Delivery', 'fslm'); ?></span>
        </div>
        <div class="input">
            <select class="input-field" name="fslm_key_delivery">

                <?php

                $delivery = get_option('fslm_key_delivery', 'fifo');

                $fifo = $delivery == 'fifo' ? 'selected' : '';
                $lifo = $delivery == 'lifo' ? 'selected' : '';

                ?>

                <option value="fifo" <?php echo $fifo ?>><?php echo __('First key added sent first',
                        'fslm'); ?></option>
                <option value="lifo" <?php echo $lifo ?>><?php echo __('Last key added sent first', 'fslm'); ?></option>
            </select>
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Show Delivered License Keys In ', 'fslm'); ?></span>
        </div>
        <div class="input">
            <?php
            $show_in = get_option('fslm_show_in', '2');
            ?>
            <select class="input-field" id="fslm_show_in" name="fslm_show_in">
                <option value="2" <?php echo ($show_in == '2') ? 'selected' : '' ?>><?php echo __('E-mail And Website',
                        'fslm') ?></option>
                <option value="0" <?php echo ($show_in == '0') ? 'selected' : '' ?>><?php echo __('E-mail',
                        'fslm') ?></option>
                <option value="1" <?php echo ($show_in == '1') ? 'selected' : '' ?>><?php echo __('Website',
                        'fslm') ?></option>
            </select>
            <div class="helper">?
                <div class="tip">
                    <?php echo __('<strong>E-mail:</strong> The buyer will receive the key in an email<br><strong>Website:</strong> The buyer will be asked to click a link in the email to go to the website to see the license key, so you can collect data such as IP address, location...',
                        'fslm'); ?>
                </div>
            </div>
        </div>

    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Display License Key As', 'fslm'); ?></span>
        </div>
        <div class="input">
            <?php
            $display = get_option('fslm_display', '2');
            ?>
            <select class="input-field" id="fslm_display" name="fslm_display">
                <option value="2" <?php echo ($display == '2') ? 'selected' : '' ?>><?php echo __('Text and Image',
                        'fslm') ?></option>
                <option value="0" <?php echo ($display == '0') ? 'selected' : '' ?>><?php echo __('Text Only',
                        'fslm') ?></option>
                <option value="1" <?php echo ($display == '1') ? 'selected' : '' ?>><?php echo __('Image Only',
                        'fslm') ?></option>
            </select>
            <div class="helper">?
                <div class="tip">
                    <?php echo __('What to show the buyer in the emails and order history', 'fslm'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Deliver different license keys if a key can be sent multiple times', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_different_keys" <?php echo esc_attr(get_option('fslm_different_keys',
                '')) == 'on' ? 'checked' : ''; ?>>
        </div>
        <blockquote>
            <p class="description">
                <?php echo __('Send different license keys if a key can be delivered multiple times and the customer orders more than one.<br><b>If the number of unique keys available is less than the ordered quantity, the same key will be delivered.</b>',
                    'fslm'); ?>
            </p>
        </blockquote>
    </div>


    <div class="input-box">
        <div class="label">
            <span><?php echo __('Enable Queue System', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_queue_system" <?php echo esc_attr(get_option('fslm_queue_system',
                '')) == 'on' ? 'checked' : ''; ?>>

            <div class="helper">?
                <div class="tip">
                    <?php echo __('Prevents the same license key form from getting delivered multiple times on websites that receive many orders simultaneously.',
                        'fslm'); ?>
                </div>
            </div>
        </div>
    </div>

    <h3><?php echo __('Expiration', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Auto expire license keys', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_auto_expire"
                <?php echo esc_attr(get_option('fslm_auto_expire', '')) == 'on' ? 'checked' : ''; ?>>
        </div>
        <blockquote>
            <p class="description">
                <?php echo __('Automatically change license keys status to expired after the expiration date.<br><b>The status updater runs once every 24 hours.</b>',
                    'fslm'); ?>
            </p>
        </blockquote>
    </div>

    <h3><?php echo __('Auto Mark as Redeemed', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Mark as redeemed after(days)', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" type="number" name="fslm_auto_redeem" min="0"
                   value="<?php echo esc_attr(get_option('fslm_auto_redeem', '0')); ?>">
        </div>
        <blockquote>
            <p class="description">
                <?php echo __('Automatically mark sold license keys as redeemed. Works only if the license key status is "sold".<br><b>Set to 0 to disable this feature.</b>',
                    'fslm'); ?>
            </p>
        </blockquote>
    </div>


    <div class="input-box">
        <div class="label">
            <span><?php echo __('Show the customers "Set as redeemed" button in the order page', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_redeem_btn" <?php echo esc_attr(get_option('fslm_redeem_btn',
                '')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>


    <h3><?php echo __('Stock Management', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Sync license keys stock with WooCommerce product stock', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_stock_sync"
                <?php echo esc_attr(get_option('fslm_stock_sync', '')) == 'on' ? 'checked' : ''; ?>>
        </div>
        <blockquote>
            <p class="description">
                <?php echo __('1. Enable this option.', 'fslm'); ?><br>
                <?php echo __('2. Enable stock management at product level for the products that you want to be automatically synced.',
                    'fslm'); ?><br><br>
                <a href="<?php echo admin_url('admin.php?page=license-manager-settings&tab=general&sync_stock=1') ?>"><?php echo __('Sync Now',
                        'fslm'); ?><br></a>
                <?php echo __('The sync now button can be used at anytime to re-sync the license keys stock.',
                    'fslm'); ?>
            </p>
        </blockquote>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Background stock sync process frequency (Seconds)', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" type="number" name="fslm_stock_sync_frequency" min="10"
                   value="<?php echo esc_attr(get_option('fslm_stock_sync_frequency', '300')); ?>">
        </div>
        <blockquote>
            <p class="description">
                <?php echo __('Run stock sync process once every X seconds.', 'fslm'); ?>
            </p>
        </blockquote>
    </div>


    <h3><?php echo __('Delete License keys', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Delete license keys when a product is deleted', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_delete_keys"
                <?php echo esc_attr(get_option('fslm_delete_keys', '')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>

    <h3><?php echo __('Delete Old License keys', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Delete sold license keys after X days', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_delete_keys_after_x_days"
                <?php echo esc_attr(get_option('fslm_delete_keys_after_x_days', '')) == 'on' ? 'checked' : ''; ?>>
        </div>

        <blockquote>
            <p class="description">
                <?php echo __('The process will delete 100 license keys every 15 minutes.', 'fslm'); ?>
            </p>
        </blockquote>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Number of days:', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" type="number" name="fslm_number_of_days" min="1"
                   value="<?php echo esc_attr(get_option('fslm_number_of_days', '365')); ?>">
        </div>
    </div>

    <h3><?php echo __('Duplicate License Keys', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Allow duplicate license keys to be added to the database', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox"
                   name="fslm_duplicate_license"
                <?php echo esc_attr(get_option('fslm_duplicate_license', '')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>


    <h3><?php echo __('Download Links', 'fslm'); ?>:</h3>
    <div class="input-box">
        <div class="label">
            <span><?php echo __('Show license keys download buttons (CSV, TXT)', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox"
                   name="fslm_download_links"
                <?php echo esc_attr(get_option('fslm_download_links', 'on')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>
    
    <?php if(class_exists('WC_Product_Vendors_Utils') && fslm_is_administrator()) { ?>

        <h3><?php echo __('Vendors Settings', 'fslm'); ?>:</h3>
        <div class="input-box">
            <div class="label">
                <span><?php echo __('Allow vendor admins and vendor managers to access the license manager', 'fslm'); ?></span>
            </div>
            <div class="input">
                <input type="checkbox"
                    name="fslm_vendors_can_manager_licenses"
                    <?php echo esc_attr(get_option('fslm_vendors_can_manager_licenses', '')) == 'on' ? 'checked' : ''; ?>>
            </div>
        </div>
    <?php } ?>


    <?php submit_button(); ?>

</form>