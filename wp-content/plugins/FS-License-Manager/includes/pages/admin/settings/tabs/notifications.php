<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<form method="post" action="options.php">

    <?php
    settings_fields('fslm_notifications_option_group');

    $notif_min_licenses_nb = esc_attr(get_option('fslm_notif_min_licenses_nb', '10'));
    $notif_mail = esc_attr(get_option('fslm_notif_mail', 'off')) == 'on' ? 'checked' : '';
    $notif_mail_to = esc_attr(get_option('fslm_notif_mail_to', ''));

    ?>

    <h3><?php echo __('Show notifications in the admin panel when', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Number of available License Keys for licensable products is under', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" name="fslm_notif_min_licenses_nb" id="fslm_notif_min_licenses_nb" type="number"
                   min="0" value="<?php echo $notif_min_licenses_nb ?>">
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Send E-mail', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_notif_mail" <?php echo $notif_mail; ?>>
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('To', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" name="fslm_notif_mail_to" id="fslm_notif_mail_to" type="email"
                   value="<?php echo $notif_mail_to ?>" placeholder="<?php _e('E-mail Address', 'fslm'); ?>">
        </div>
    </div>

    <?php submit_button(); ?>

</form>