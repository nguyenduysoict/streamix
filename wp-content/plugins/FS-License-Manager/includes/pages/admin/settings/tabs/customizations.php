<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<form method="post" action="options.php">

    <?php
    settings_fields('fslm_customizations_option_group');
    do_settings_sections('fslm_customizations_option_group');
    ?>

    <h3><?php echo __('Customizations', 'fslm'); ?>:</h3>

    <p><?php __('All customizations made for specific buyers can be enabled from this menu.', 'fslm'); ?><br>
    <p><?php __('They are kept here so I can keep the up-to-date.', 'fslm'); ?><br>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Import prefixes/suffixes in the product page', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox"
                   name="fslm_is_import_prefix_suffix_enabled"
                <?php echo get_option('fslm_is_import_prefix_suffix_enabled', '') == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>


    <?php submit_button(); ?>

</form>