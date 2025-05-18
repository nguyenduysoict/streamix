<?php

defined('ABSPATH') or die('No script kiddies please!');

if (isset($_GET['sync_stock'])) {
    global $fs_wc_licenses_manager;
    $fs_wc_licenses_manager->sync_stock();
}

require_once FSLM_PLUGIN_BASE . "/includes/functions.php";

global $months;
global $status;

$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

?>
<div class="wrap fslm">

    <h1><?php echo __('License Manager Settings', 'fslm'); ?></h1>

    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=license-manager-settings&tab=' . 'general') ?>"
           class="nav-tab <?php echo ($current_tab == 'general' || $current_tab == '') ? 'nav-tab-active' : '' ?>">
            <?php echo __('General', 'fslm'); ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=license-manager-settings&tab=' . 'order-status') ?>"
           class="nav-tab <?php echo $current_tab == 'order-status' ? 'nav-tab-active' : '' ?>">
            <?php echo __('Order Status', 'fslm'); ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=license-manager-settings&tab=' . 'generator') ?>"
           class="nav-tab  <?php echo $current_tab == 'generator' ? 'nav-tab-active' : '' ?>">
            <?php echo __('License Keys Generator', 'fslm'); ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=license-manager-settings&tab=' . 'notifications') ?>"
           class="nav-tab  <?php echo $current_tab == 'notifications' ? 'nav-tab-active' : '' ?>">
            <?php echo __('Notifications', 'fslm'); ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=license-manager-settings&tab=' . 'emails') ?>"
           class="nav-tab  <?php echo $current_tab == 'emails' ? 'nav-tab-active' : '' ?>">
            <?php echo __('Emails', 'fslm'); ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=license-manager-settings&tab=' . 'encryption') ?>"
           class="nav-tab  <?php echo $current_tab == 'encryption' ? 'nav-tab-active' : '' ?>">
            <?php echo __('Encryption', 'fslm'); ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=license-manager-settings&tab=' . 'api') ?>"
           class="nav-tab  <?php echo $current_tab == 'api' ? 'nav-tab-active' : '' ?>">
            <?php echo __('API', 'fslm'); ?>
        </a>
        <a href="<?php echo admin_url('admin.php?page=license-manager-settings&tab=' . 'extra') ?>"
           class="nav-tab  <?php echo $current_tab == 'extra' ? 'nav-tab-active' : '' ?>">
            <?php echo __('Extra Settings', 'fslm'); ?>
        </a>

        <?php if (class_exists('WC_Subscriptions')) { ?>

            <a href="<?php echo admin_url('admin.php?page=license-manager-settings&tab=' . 'subscriptions') ?>"
               class="nav-tab  <?php echo $current_tab == 'subscriptions' ? 'nav-tab-active' : '' ?>">
                <?php echo __('Subscriptions', 'fslm'); ?>
            </a>

        <?php } ?>
    </h2>

    <div class="postbox">
        <div class="inside">
            <?php

            if (in_array($current_tab, [
                "general",
                "order-status",
                "generator",
                "notifications",
                "emails",
                "encryption",
                "api",
                "extra",
                "customizations",
                "subscriptions"
            ])) {
                include "tabs/" . $current_tab . ".php";
            } else {
                echo __("Page not found.", "fslm");
            }

            ?>
        </div>
    </div>
</div>

