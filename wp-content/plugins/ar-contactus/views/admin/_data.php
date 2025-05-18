<div class="ui segment arconfig-panel <?php echo ($activeSubmit == 'importDataSubmit' || $activeSubmit == 'migrateSettingsSubmit')? '' : 'hidden' ?>" id="arcontactus-data">
    <form style="margin-bottom: 20px" action="<?php echo admin_url('admin-ajax.php?action=arcontactus_export_data&ajax=true') ?>" class="ui form" method="post">
        <?php wp_nonce_field('arcu_config') ?>
        <h3 class="section-head"><?php echo __('Export data', 'ar-contactus') ?></h3>
        <div class="field field_settings">
            <div class="ui toggle checkbox">
                <input id="arcu_settings_off" name="settings" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                <input id="arcu_settings" checked="" name="settings" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
                <label for="arcu_settings"><?php echo __('Export settings', 'ar-contactus') ?></label>
            </div>
        </div>
        <div class="field field_menu">
            <div class="ui toggle checkbox">
                <input id="arcu_menu_off" name="menu" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                <input id="arcu_menu" checked="" name="menu" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
                <label for="arcu_menu"><?php echo __('Export menu items', 'ar-contactus') ?></label>
            </div>
        </div>
        <div class="field field_prompts">
            <div class="ui toggle checkbox">
                <input id="arcu_prompts_off" name="prompts" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                <input id="arcu_prompts" checked="" name="prompts" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
                <label for="arcu_prompts"><?php echo __('Export prompt items', 'ar-contactus') ?></label>
            </div>
        </div>
        <div class="field field_callbacks">
            <div class="ui toggle checkbox">
                <input id="arcu_callbacks_off" name="callbacks" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                <input id="arcu_callbacks" checked="" name="callbacks" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
                <label for="arcu_callbacks"><?php echo __('Export callback requests', 'ar-contactus') ?></label>
            </div>
        </div>
        <div class="field field_submit">
            <button class="button button-primary button-large" type="submit"><?php echo __('Export data', 'ar-contactus') ?></button>
        </div>
    </form>
    <hr/>
    <form style="margin-top: 20px; margin-bottom: 20px" enctype="multipart/form-data" faction="<?php echo admin_url('admin-ajax.php?action=arcontactus_import_data&ajax=true') ?>" class="ui form" method="post">
        <?php wp_nonce_field('arcu_config') ?>
        <h3 class="section-head"><?php echo __('Import data', 'ar-contactus') ?></h3>
        <div class="ui error message" style="display: block">
            <div class="header"><?php echo __('Warning!', 'ar-contactus') ?></div>
            <p><?php echo __('All existing data will be loss!', 'ar-contactus') ?></p>
        </div>
        <div class="field field_file">
            <label for="arcu-import-file"><?php echo __('Import file (export.json)', 'ar-contactus') ?></label>
            <input type="file" name="import" id="arcu-import-file"/>
        </div>
        <div class="field field_confirm">
            <div class="ui toggle checkbox">
                <input id="arcu_confirm_off" checked="" name="confirm" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                <input id="arcu_confirm" name="confirm" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
                <label for="arcu_confirm"><?php echo __('I accept that all existing data will be loss', 'ar-contactus') ?></label>
            </div>
        </div>
        <div class="field field_submit">
            <button name="importDataSubmit" class="button button-primary button-large" type="submit"><?php echo __('Import data', 'ar-contactus') ?></button>
        </div>
    </form>
    
    <hr/>
    <form style="margin-top: 20px" enctype="multipart/form-data" faction="<?php echo admin_url('admin-ajax.php?action=arcontactus_migrate_settings&ajax=true') ?>" class="ui form" method="post">
        <?php wp_nonce_field('arcu_config') ?>
        <h3 class="section-head"><?php echo __('Migrate settings data to new storage format', 'ar-contactus') ?></h3>
        <div class="ui error message" style="display: block">
            <div class="header"><?php echo __('Warning!', 'ar-contactus') ?></div>
            <p><?php echo __('All existing settings will be replaced!', 'ar-contactus') ?></p>
        </div>
        <div class="field field_confirm">
            <div class="ui toggle checkbox">
                <input id="arcu_confirm_off" checked="" name="confirm" value="0" tabindex="0" autocomplete="off" class="hidden" type="hidden">
                <input id="arcu_confirm" name="confirm" value="1" tabindex="0" autocomplete="off" class="hidden" type="checkbox">
                <label for="arcu_confirm"><?php echo __('I accept that all existing settings will be replaced', 'ar-contactus') ?></label>
            </div>
        </div>
        <div class="field field_submit">
            <button name="migrateSettingsSubmit" class="button button-primary button-large" type="submit"><?php echo __('Migrate', 'ar-contactus') ?></button>
        </div>
    </form>
</div>