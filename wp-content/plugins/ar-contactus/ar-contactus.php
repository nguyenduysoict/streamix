<?php
/**
 * @package ArContactUs
 */
/*
Plugin Name: Contact Us all-in-one button
Plugin URI: https://plugins.areama.net/ar-contactus/docs/
Description: Display contact us button with menu on every page. Callback request, reCaptcha V3 protection and many customizations!
Version: 2.2.7
Author: Areama
Author URI: https://plugins.areama.net/
License: GPLv2 or later
Text Domain: ar-contactus
*/

/*
This program is not free software; you can not redistribute it! 
You can modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2018-2022 Areama.
*/

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    die("Hi there!  I'm just a plugin, not much I can do when called directly.");
}

define('AR_CONTACTUS_VERSION', '2.2.7');
define('AR_CONTACTUS_MINIMUM_WP_VERSION', '3.7');
if (get_option('ARCU_DEBUG')) {
    define('AR_CONTACTUS_DEBUG', true);
} else {
    define('AR_CONTACTUS_DEBUG', false);
}
define('AR_CONTACTUS_PLUGIN_FILE', __FILE__);
define('AR_CONTACTUS_PLUGIN_NAME', 'ar-contactus');
define('AR_CONTACTUS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AR_CONTACTUS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AR_CONTACTUS_PLUGIN_DIR_CLASSES', plugin_dir_path(__FILE__) . 'classes/');
define('AR_CONTACTUS_PLUGIN_DIR_MODELS', plugin_dir_path(__FILE__) . 'models/');
define('AR_CONTACTUS_PLUGIN_DIR_CONTROLLERS', plugin_dir_path(__FILE__) . 'controllers/');

require_once(AR_CONTACTUS_PLUGIN_DIR_CLASSES . 'ArContactUsLoader.php');
require_once(AR_CONTACTUS_PLUGIN_DIR . 'functions.php');

ArContactUsLoader::loadClass('ArContactUs');
ArContactUsLoader::loadClass('ArContactUsTools');
$arContactUs = new ArContactUs();

register_activation_hook(__FILE__, array($arContactUs, 'activate'));
register_deactivation_hook(__FILE__, array($arContactUs, 'deactivate'));
register_uninstall_hook(__FILE__, 'arContactUsUninstall');

add_action('plugins_loaded', array($arContactUs, 'init'));

if (is_admin() || (defined('WP_CLI') && WP_CLI)){
    ArContactUsLoader::loadClass('ArContactUsAdmin');
    $arContactUsAdmin = new ArContactUsAdmin();
    add_action('init', array($arContactUsAdmin, 'init'));
}

function arContactUsUninstall()
{
    ArContactUsLoader::loadModel('ArContactUsConfigGeneral');
    ArContactUsLoader::loadModel('ArContactUsConfigEmails');
    ArContactUsLoader::loadModel('ArContactUsConfigButton');
    ArContactUsLoader::loadModel('ArContactUsConfigMobileButton');
    ArContactUsLoader::loadModel('ArContactUsConfigMenu');
    ArContactUsLoader::loadModel('ArContactUsConfigMobileMenu');
    ArContactUsLoader::loadModel('ArContactUsConfigPopup');
    ArContactUsLoader::loadModel('ArContactUsConfigPrompt');
    ArContactUsLoader::loadModel('ArContactUsConfigMobilePrompt');
    ArContactUsLoader::loadModel('ArContactUsConfigWelcome');
    ArContactUsLoader::loadModel('ArContactUsConfigLiveChat');
    ArContactUsLoader::loadModel('ArContactUsModel');
    ArContactUsLoader::loadModel('ArContactUsCallbackModel');
    ArContactUsLoader::loadModel('ArContactUsPromptModel');

    $generalConfig = new ArContactUsConfigGeneral('arcug_');
    $buttonConfig = new ArContactUsConfigButton('arcub_');
    $mobileButtonConfig = new ArContactUsConfigMobileButton('arcumb_');
    $menuConfig = new ArContactUsConfigMenu('arcum_');
    $mobileMenuConfig = new ArContactUsConfigMobileMenu('arcumm_');
    $popupConfig = new ArContactUsConfigPopup('arcup_');
    $promptConfig = new ArContactUsConfigPrompt('arcupr_');
    $mobilePromptConfig = new ArContactUsConfigMobilePrompt('arcumpr_');
    $integrationConfig = new ArContactUsConfigLiveChat('arcul_');
    $emailsConfig = new ArContactUsConfigEmails('arcue_');
    $welcomeConfig = new ArContactUsConfigWelcome('arcuw_');
    
    $generalConfig->clearConfig();
    $buttonConfig->clearConfig();
    $mobileButtonConfig->clearConfig();
    $menuConfig->clearConfig();
    $mobileMenuConfig->clearConfig();
    $popupConfig->clearConfig();
    $promptConfig->clearConfig();
    $mobilePromptConfig->clearConfig();
    $integrationConfig->clearConfig();
    $emailsConfig->clearConfig();
    $welcomeConfig->clearConfig();
    
    delete_option('arcu_installed');
    delete_option('arcuforms');
    delete_option('ARCU_DEBUG');
    ArContactUsModel::dropTable();
    ArContactUsModel::dropLangTable();
    ArContactUsCallbackModel::dropTable();
    ArContactUsPromptModel::dropTable();
    ArContactUsPromptModel::dropLangTable();
}