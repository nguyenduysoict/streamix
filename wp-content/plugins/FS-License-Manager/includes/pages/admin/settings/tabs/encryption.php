<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<form action="<?php echo admin_url('admin.php?action=save_encryption_setting') ?>" method="post">

    <h3><?php echo __('Encryption Settings', 'fslm'); ?>:</h3>

    <?php

    $upload_directory = wp_upload_dir();
    $target_file = $upload_directory['basedir'] . '/fslm_files/encryption_key.php';

    if (!@include_once($target_file)) {
        set_encryption_key('5RdRDCmG89DooltnMlUG', '2Ve2W2g9ANKpvQNXuP3w');
        @include_once($target_file);
    }

    ?>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Data Encryption Key', 'fslm'); ?></span>
        </div>
        <div class="input">

            <input class="input-field" type="text" name="fslm_encryption_key" value="<?php echo ENCRYPTION_KEY; ?>">
            <div class="helper">?
                <div class="tip">
                    <?php echo __('The key used to encrypt/decrypt license keys in the database', 'fslm'); ?>
                </div>
            </div>
        </div>

    </div>

    <div class="input-box">
        <div class="label">
            <span><?php echo __('Data Encryption VI', 'fslm'); ?></span>
        </div>
        <div class="input">

            <input class="input-field" type="text" name="fslm_encryption_vi" value="<?php echo ENCRYPTION_VI; ?>">
            <div class="helper">?
                <div class="tip">
                    <?php echo __('The VI used to encrypt/decrypt license keys in the database', 'fslm'); ?>
                </div>
            </div>
        </div>

    </div>

    <?php if (!extension_loaded('openssl')) { ?>
        <p class="no_openssl error"><?php echo __('Open SSL is not installed on this server license keys will be stored without encryption', 'fslm') ?>.</p>
    <?php } ?>

    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
    </p>

</form>