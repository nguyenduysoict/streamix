<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<form method="post" action="options.php">
    <?php
    settings_fields('fslm_api_option_group');
    do_settings_sections('fslm_api_option_group');
    ?>

    <h3><?php echo __('API Version 1 - Settings', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Disable API v1', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_disable_api_v1"
                <?php echo esc_attr(get_option('fslm_disable_api_v1', '')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>

    <h3><?php echo __('API Version 2 - Settings', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Disable API v2', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_disable_api_v2"
                <?php echo esc_attr(get_option('fslm_disable_api_v2', '')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('API Key', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" name="fslm_api_key" id="fslm_api_key" type="text"
                   value="<?php echo esc_attr(get_option('fslm_api_key', '0A9Q5OXT13in3LGjM9F3')); ?>">
        </div>
        <blockquote>
            <p class="description">
                <?php echo __('For "verify", "activate", "deactivate", "details", "extra_data", and "license_status" API calls.',
                    'fslm') ?>
            </p>
        </blockquote>
    </div>

    <br>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Enable Private API', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox"
                   name="fslm_enable_private_api"
                <?php echo esc_attr(get_option('fslm_enable_private_api', '')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Private API Key', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" name="fslm_private_api_key" id="fslm_private_api_key" type="text"
                   value="<?php echo esc_attr(get_option('fslm_private_api_key',
                       '3a5088d8-2aa0-41d2-b151-79eaf845f3ef')); ?>">
        </div>
        <blockquote>
            <p class="description">
                <?php echo __('For private use only, do not include this key in any customer-side API calls.',
                    'fslm') ?>
                <br>
                <?php echo __('Do not keep the default API keys.', 'fslm') ?>
                <br>
                <?php echo __('For "expire" API calls.', 'fslm') ?>
            </p>
        </blockquote>

    </div>

    <h3><?php echo __('API Version 3 - Settings', 'fslm'); ?>:</h3>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Disable API v3', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_disable_api_v3"
                <?php echo esc_attr(get_option('fslm_disable_api_v3', 'on')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>

    <div class="fslm-warning">
        <?php echo __('WARNING: You are giving elevated permissions to non-admin user roles.', 'fslm'); ?>
    </div>

    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <td></td>
            <?php

            global $wp_roles, $fslm_api_v3_endpoints, $fslm_admin_roles, $fslm_all_allowed;
            $all_roles = $wp_roles->roles;

            foreach ($all_roles as $key => $role) { ?>
                <td><strong><?php echo $role['name'] ?></strong></td>
            <?php } ?>
        </tr>
        </thead>

        <tbody>

        <?php foreach ($fslm_api_v3_endpoints as $key => $value) { ?>
            <tr>
                <td><strong><?php echo $value ?></strong></td>
                <?php foreach ($all_roles as $role_slug => $role) { ?>
                    <td><input
                                class="fslm-permission"
                                type="checkbox"
                                name="fslm_api_v3_permission_<?php echo $role_slug . '_' . $key ?>"
                            <?php
                            $default = 'off';
                            $alert = false;
                            if (in_array($role_slug, $fslm_admin_roles) ||
                                in_array($key, $fslm_all_allowed)) {
                                $default = 'on';
                            } else {
                                $alert = true;
                            }
                            $saved_value = get_option('fslm_api_v3_permission_' . $role_slug . '_' . $key, $default);

                            if ($saved_value == 'on') {
                                echo 'checked="checked"';
                            }

                            if ($alert) {
                                echo 'data-warn="1"';
                            }
                            ?>
                        ></td>
                <?php } ?>
            </tr>
        <?php } ?>

        </tbody>
    </table>

    <p>
        *: <?php echo __('Only allows API calls for licenses owned by the currently authenticated user except for Administrator and Shop manager user roles. They can make API calls for any license key. <br>Regular users will get the following message instead "The authenticated user doesn\'t own this license key". (check the API documentation for more details).',
            'fslm') ?></p>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Passphrase', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input class="input-field" name="fslm_api_v3_passphrase" id="fslm_api_v3_passphrase" type="password"
                   value="<?php echo esc_attr(get_option('fslm_api_v3_passphrase', '')); ?>">
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Private Key', 'fslm'); ?></span>
        </div>
        <div class="input">
            <textarea name="fslm_api_v3_pk"><?php echo esc_attr(get_option('fslm_api_v3_pk', '')); ?></textarea><br>
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Algorithm', 'fslm'); ?></span>
        </div>
        <div class="input">
            <select class="input-field" name="fslm_api_v3_algo">

                <?php $fslm_algo = get_option('fslm_api_v3_algo', '7') ?>

                <option <?php echo $fslm_algo == '1' ? 'selected' : '' ?> value="1">OPENSSL_ALGO_SHA1</option>
                <option <?php echo $fslm_algo == '2' ? 'selected' : '' ?> value="2">OPENSSL_ALGO_MD5</option>
                <option <?php echo $fslm_algo == '3' ? 'selected' : '' ?> value="3">OPENSSL_ALGO_MD4</option>
                <option <?php echo $fslm_algo == '4' ? 'selected' : '' ?> value="4">OPENSSL_ALGO_MD2</option>
                <option <?php echo $fslm_algo == '5' ? 'selected' : '' ?> value="5">OPENSSL_ALGO_DSS1</option>
                <option <?php echo $fslm_algo == '6' ? 'selected' : '' ?> value="6">OPENSSL_ALGO_SHA224</option>
                <option <?php echo $fslm_algo == '7' ? 'selected' : '' ?> value="7">OPENSSL_ALGO_SHA256</option>
                <option <?php echo $fslm_algo == '8' ? 'selected' : '' ?> value="8">OPENSSL_ALGO_SHA384</option>
                <option <?php echo $fslm_algo == '9' ? 'selected' : '' ?> value="9">OPENSSL_ALGO_SHA512</option>
                <option <?php echo $fslm_algo == '10' ? 'selected' : '' ?> value="10">OPENSSL_ALGO_RMD160</option>
            </select>
        </div>
    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Base64 Encode Response', 'fslm'); ?></span>
        </div>
        <div class="input">
            <input type="checkbox" name="fslm_api_v3_encode"
                <?php echo esc_attr(get_option('fslm_api_v3_encode', '')) == 'on' ? 'checked' : ''; ?>>
        </div>
    </div>

    <?php submit_button(); ?>

</form>