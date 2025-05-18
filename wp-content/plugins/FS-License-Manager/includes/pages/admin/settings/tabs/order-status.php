<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<form method="post" action="options.php">

    <?php
    settings_fields('fslm_order_status_option_group');
    do_settings_sections('fslm_order_status_option_group');

    ?>

    <h3><?php echo __('License Delivery Settings', 'fslm'); ?>:</h3>

    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <td><strong><?php echo __('Order Status', 'fslm') ?></strong></td>
            <td><strong><?php echo __('Send', 'fslm') ?></strong></td>
            <td>
                <strong><?php echo __('Revoke', 'fslm') ?></strong>
                <div class="helper">?
                    <div class="tip">
                        <?php echo __('Remove the assigned license key and change its status to returned', 'fslm'); ?>
                    </div>
                </div>
            </td>
            <td>
                <strong><?php echo __('Hide', 'fslm') ?></strong>
                <div class="helper">?
                    <div class="tip">
                        <?php echo __('Hide the assigned license key from the buyer in the order history and emails',
                            'fslm'); ?>
                    </div>
                </div>
            </td>
        </tr>
        </thead>

        <tbody>

        <?php

        $on_status_send = array('completed', 'processing');
        $on_status_revoke = array('refunded');
        $on_status_hide = array();


        /////////////////////////


        $order_statuses = (array)FS_WC_licenses_Manager::get_terms('shop_order_status',
            array('hide_empty' => 0, 'orderby' => 'id'));

        if ($order_statuses && !is_wp_error($order_statuses)) {
        foreach ($order_statuses as $s) {

            if (defined("WOOCOMMERCE_VERSION") && version_compare(WOOCOMMERCE_VERSION, '2.2', '>=')) {

                $s->slug = str_replace('wc-', '', $s->slug);

            }

            $default_send = 'off';
            $default_revoke = 'off';
            $default_hide = 'off';

            if (in_array($s->slug, $on_status_send)) {
                $default_send = 'on';
            }

            if (in_array($s->slug, $on_status_revoke)) {
                $default_revoke = 'on';
            }

            if (in_array($s->slug, $on_status_hide)) {
                $default_hide = 'on';
            }

            ?>

            <tr>

                <td><strong><?php echo $s->name ?></strong></td>
                <td>
                    <input type="checkbox" name="fslm_send_when_<?php echo $s->slug ?>"
                        <?php echo esc_attr(get_option('fslm_send_when_' . $s->slug,
                            $default_send)) == 'on' ? 'checked' : ''; ?>>
                </td>
                <td>
                    <input type="checkbox" name="fslm_revoke_when_<?php echo $s->slug ?>"
                        <?php echo esc_attr(get_option('fslm_revoke_when_' . $s->slug,
                            $default_revoke)) == 'on' ? 'checked' : ''; ?>>
                </td>
                <td>
                    <input type="checkbox" name="fslm_hide_when_<?php echo $s->slug ?>"
                        <?php echo esc_attr(get_option('fslm_hide_when_' . $s->slug,
                            $default_hide)) == 'on' ? 'checked' : ''; ?>>
                </td>

            </tr>

        <?php } ?>

        </tbody>
    </table>

<?php

}

submit_button(); ?>

</form>