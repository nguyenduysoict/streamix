<?php
ArContactUsLoader::loadClass('ArContactUsAbstract');
ArContactUsLoader::loadClass('ArContactUsImport');
ArContactUsLoader::loadClass('ArContactUsMigrate');
ArContactUsLoader::loadClass('ArContactUsListTable');
ArContactUsLoader::loadClass('ArContactUsTwilio');
ArContactUsLoader::loadClass('ArContactUsUpdater');
ArContactUsLoader::loadController('ArContactUsMenuController');
ArContactUsLoader::loadController('ArContactUsPromptController');
ArContactUsLoader::loadController('ArContactUsRequestsController');
ArContactUsLoader::loadController('ArContactUsFormController');

class ArContactUsAdmin extends ArContactUsAbstract
{
    const NONCE = 'arcontactus-update-key';
    
    private static $initiated = false;

    protected $errors = array();
    protected $success = null;

    protected $importSuccess = 0;
    
    public $generalConfig = null;
    public $buttonConfig = null;
    public $mobileButtonConfig = null;
    public $menuConfig = null;
    public $mobileMenuConfig = null;
    public $popupConfig = null;
    
    public $promptConfig = null;
    public $mobilePromptConfig = null;
    public $welcomeConfig = null;
    public $liveChatsConfig = null;

    public $formsConfig = null;
    
    public $emailConfig = null;

    protected $json;
    
    public function init()
    {
        if (isset($_GET['debug'])) {
            update_option('ARCU_DEBUG', (int)$_GET['debug']);
            wp_redirect(admin_url('options-general.php?page=ar-contactus-key-config'));
        }
        $this->generalConfig = new ArContactUsConfigGeneral('arcug_');
        $this->buttonConfig = new ArContactUsConfigButton('arcub_');
        $this->mobileButtonConfig = new ArContactUsConfigMobileButton('arcumb_');
        $this->menuConfig = new ArContactUsConfigMenu('arcum_');
        $this->mobileMenuConfig = new ArContactUsConfigMobileMenu('arcumm_');
        $this->popupConfig = new ArContactUsConfigPopup('arcup_');
        $this->promptConfig = new ArContactUsConfigPrompt('arcupr_');
        $this->mobilePromptConfig = new ArContactUsConfigMobilePrompt('arcumpr_');
        $this->welcomeConfig = new ArContactUsConfigWelcome('arcuw_');
        $this->liveChatsConfig = new ArContactUsConfigLiveChat('arcul_');
        $this->formsConfig = new ArContactUsConfigForms();
        $this->emailConfig = new ArContactUsConfigEmails('arcue_');
        
        if (get_option('arcu_recompile_css')) {
            $this->compileCSS();
            $this->compileCSS(true);
            update_option('arcu_recompile_css', 0);
        }
        
        if (!self::$initiated){
            $this->initHooks();
        }
    }
    
    public function migrateSettingsSubmitted()
    {
        return self::isSubmit('migrateSettingsSubmit');
    }
    
    public function importSubmitted()
    {
        return self::isSubmit('importDataSubmit');
    }
    
    public function channelSubmitted()
    {
        return self::isSubmit('arcu_channel');
    }
    
    public function validate()
    {
        $nonce = $_REQUEST['_wpnonce'];
        if (!wp_verify_nonce($nonce, ArContactUsAdmin::NONCE)) {
            $this->errors['nonce'] = array(__('Invalid security token. Please try again', 'ar-contactus'));
            return false;
        }
        foreach ($this->getForms() as $model) {
            if (self::isSubmit(get_class($model))) {
                $model->loadFromConfig();
                $model->populate();
                if (!$model->validate()) {
                    $this->errors = $model->getErrors();
                    return false;
                }
                return true;
            }
        }
    }
    
    public function getSubmit()
    {
        foreach ($this->getForms() as $model) {
            if (self::isSubmit(get_class($model))) {
                return get_class($model);
            }
        }
        if (self::isSubmit('importDataSubmit')){
            return 'importDataSubmit';
        }
        
        if (self::isSubmit('migrateSettingsSubmit')){
            return 'migrateSettingsSubmit';
        }
    }
    
    public function isSubmitted()
    {
        foreach ($this->getAllowedSubmits() as $submit) {
            if ($this->isSubmit($submit)) {
                return true;
            }
        }
    }
    
    public function getAllowedSubmits()
    {
        $submits = array();
        foreach ($this->getForms() as $model) {
            $submits[] = get_class($model);
        }
        return $submits;
    }
    
    public function getForms()
    {
        return array(
            $this->generalConfig,
            $this->buttonConfig,
            $this->mobileButtonConfig,
            $this->menuConfig,
            $this->mobileMenuConfig,
            $this->popupConfig,
            $this->promptConfig,
            $this->mobilePromptConfig,
            $this->welcomeConfig,
            $this->liveChatsConfig,
            $this->emailConfig
        );
    }
    
    public function save()
    {
        if (!wp_verify_nonce($_POST['_wpnonce'], self::NONCE)){
            return false;
        }
        $this->errors = array();
        foreach ($this->getForms() as $model) {
            if (self::isSubmit(get_class($model))) {
                $model->populate();
                if ($model->saveToConfig()) {
                    $this->compileCSS();
                    $this->compileCSS(true);
                    return true;
                } else {
                    $this->errors = $model->getErrors();
                }
            }
        }
        return false;
    }
    
    public function compileCSS($mobile = false)
    {
        if ($mobile) {
            if (!$this->mobileMenuConfig->isLoaded()){
                $this->mobileMenuConfig->loadFromConfig();
            }
            if (!$this->mobileButtonConfig->isLoaded()){
                $this->mobileButtonConfig->loadFromConfig();
            }
        } else {
            if (!$this->menuConfig->isLoaded()){
                $this->menuConfig->loadFromConfig();
            }
            if (!$this->buttonConfig->isLoaded()){
                $this->buttonConfig->loadFromConfig();
            }
        }
        if (!$this->popupConfig->isLoaded()){
            $this->popupConfig->loadFromConfig();
        }
        if (!$this->generalConfig->isLoaded()){
            $this->generalConfig->loadFromConfig();
        }
        $content = self::render('front/styles.php', array(
            'menuConfig' => $mobile? $this->mobileMenuConfig : $this->menuConfig,
            'popupConfig' => $this->popupConfig,
            'buttonConfig' => $mobile? $this->mobileButtonConfig : $this->buttonConfig,
            'formsConfig' => $this->formsConfig,
            'generalConfig' => $this->generalConfig,
            'isMobile' => $mobile
        ));
        $content = ArContactUsTools::minifyStyles($content);
        if ($mobile) {
            $filename = 'generated-mobile.css';
        } else {
            $filename = 'generated-desktop.css';
        }
        if (is_writable(AR_CONTACTUS_PLUGIN_DIR . 'res/css/' . $filename)) {
            file_put_contents(AR_CONTACTUS_PLUGIN_DIR . 'res/css/' . $filename, $content);
            update_option('arcu_css_generated', time());
        }
    }
    
    public function initHooks()
    {
        self::$initiated = true;
        $updater = new ArContactUsUpdater($this);
        add_action('admin_init', array($this, 'adminInit'));
        add_action('admin_menu', array($this, 'adminMenu'), 5);
        add_action('admin_enqueue_scripts', array($this, 'loadResources'));
        add_filter('plugin_action_links', array($this, 'adminPluginSettings'), 10, 2);
        add_action('admin_head', array($this, 'renderOnesignal'));
        add_filter('all_plugins', array($updater, 'autoCheckUpdate'));
        add_action('upgrader_process_complete', array($updater, 'migrate'), 9, 0);
        $promptController = new ArContactUsPromptController();
        $promptController->init($this);
        
        $menuController = new ArContactUsMenuController();
        $menuController->init($this);
        
        $requestsController = new ArContactUsRequestsController();
        $requestsController->init($this);
        
        $formController = new ArContactUsFormController();
        $formController->init($this);
    }
    
    public function renderOnesignal()
    {
        if (!$this->popupConfig->isLoaded()){
            $this->popupConfig->loadFromConfig();
        }
        if (!$this->popupConfig->onesignal){
            return false;
        }
        $manifest = AR_CONTACTUS_PLUGIN_URL . 'manifest.json';
        echo self::render('/admin/_onesignal.php', array(
            'manifest' => $manifest,
            'popupConfig' => $this->popupConfig,
            'host' => parse_url(AR_CONTACTUS_PLUGIN_URL, PHP_URL_HOST),
            'scheme' => parse_url(AR_CONTACTUS_PLUGIN_URL, PHP_URL_SCHEME),
        ));
    }
    
    public function adminPluginSettings($links, $file)
    {
        if ($file == plugin_basename(AR_CONTACTUS_PLUGIN_DIR . '/ar-contactus.php')){
            $links[] = '<a href="' . esc_url(admin_url('options-general.php?page=ar-contactus-key-config')) . '">'.esc_html__('Settings', 'ar-contactus').'</a>';
        }

        return $links;
    }
            
    public function callbackRequests(){
        echo self::render('/admin/callback-requests.php', array(
            'callbackList' => new ArContactUsListTable(),
            'activeSubmit' => 'arcontactus-requests'
        ));
    }
    
    public function emailRequests(){
        $list = new ArContactUsListTable();
        $list->setType(ArContactUsCallbackModel::TYPE_EMAIL);
        echo self::render('/admin/email-requests.php', array(
            'callbackList' => $list,
            'activeSubmit' => 'arcontactus-email-requests'
        ));
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function adminInit() {
        load_plugin_textdomain('ar-contactus', false, basename(AR_CONTACTUS_PLUGIN_DIR) . '/languages');
        if ($this->isSubmitted()){
            if ($this->validate()){
                if ($this->save()){
                    $this->success = __('Settings updated', 'ar-contactus');
                }
            }
        }elseif($this->channelSubmitted()){
            update_option('ARCU_CHANNEL', $_POST['arcu_channel']);
            $updater = new ArContactUsUpdater($this);
            $updater->checkUpdate(true);
        }elseif($this->importSubmitted()){
            $import = new ArContactUsImport($this);
            if ($import->import()){
                $this->success = __('All data imported.', 'ar-contactus');
                $this->importSuccess = 1;
            }else{
                $this->errors[] = array($import->getError());
            }
        }elseif($this->migrateSettingsSubmitted()){
            $migrate = new ArContactUsMigrate();
            if ($migrate->migrate()){
                $this->success = __('All settings migrated.', 'ar-contactus');
            }else{
                $this->errors[] = array($migrate->getError());
            }
        }
    }

    public function adminMenu() {
        $this->loadMenu();
    }
    
    public function loadMenu() {
        $user = wp_get_current_user();
        $roles = (array) $user->roles;
        $role = reset($roles);
        if (!$this->generalConfig->isLoaded()) {
            $this->generalConfig->loadFromConfig();
        }
        
        $hook = add_options_page(__('Contact-us button', 'ar-contactus'), __('Contact-us button', 'ar-contactus'), 'manage_options', 'ar-contactus-key-config', array($this, 'displayConfig'));
        $menuLabel = __('Callbacks', 'ar-contactus');
        $emailMenuLabel = __('Email requests', 'ar-contactus');
        $count = ArContactUsCallbackModel::newCount();
        $countEmail = ArContactUsCallbackModel::newCount(ArContactUsCallbackModel::TYPE_EMAIL);
        $menuLabel .= " <span class='update-plugins count-1' " . ($count? '' : 'style="display: none"') . "' id='arcontactus-cb-count'><span class='update-count'>" . $count . "</span></span>";
        $emailMenuLabel .= " <span class='update-plugins count-1' " . ($countEmail? '' : 'style="display: none"') . "' id='arcontactus-email-count'><span class='update-count'>" . $countEmail . "</span></span>";
        if (is_array($this->generalConfig->callback_access) && in_array($role, $this->generalConfig->callback_access)) {
            if (!$this->generalConfig->disable_callback_menu) {
                add_menu_page(__('Callbacks', 'ar-contactus'), $menuLabel, $role, 'ar-contactus-key-requests', array($this,'callbackRequests'), 'dashicons-phone');
            }
            if (!$this->generalConfig->disable_email_menu) {
                add_menu_page(__('Callbacks', 'ar-contactus'), $emailMenuLabel, $role, 'ar-contactus-email-requests', array($this,'emailRequests'), 'dashicons-email');
            }
        }
    }
    
    public function displayConfig()
    {
        if (!$this->generalConfig->isLoaded()){
            $this->generalConfig->loadFromConfig();
        }
        if (!$this->buttonConfig->isLoaded()){
            $this->buttonConfig->loadFromConfig();
        }
        if (!$this->mobileButtonConfig->isLoaded()){
            $this->mobileButtonConfig->loadFromConfig();
        }
        if (!$this->menuConfig->isLoaded()){
            $this->menuConfig->loadFromConfig();
        }
        if (!$this->mobileMenuConfig->isLoaded()){
            $this->mobileMenuConfig->loadFromConfig();
        }
        if (!$this->popupConfig->isLoaded()){
            $this->popupConfig->loadFromConfig();
        }
        if (!$this->promptConfig->isLoaded()){
            $this->promptConfig->loadFromConfig();
        }
        if (!$this->mobilePromptConfig->isLoaded()){
            $this->mobilePromptConfig->loadFromConfig();
        }
        if (!$this->liveChatsConfig->isLoaded()){
            $this->liveChatsConfig->loadFromConfig();
        }
        if (!$this->emailConfig->isLoaded()) {
            $this->emailConfig->loadFromConfig();
        }
        if (!$this->welcomeConfig->isLoaded()) {
            $this->welcomeConfig->loadFromConfig();
        }
        
        $updater = new ArContactUsUpdater();
        
        if (ArContactUsTools::isMultilang()) {
            $lang = ArContactUsTools::getCurrentLanguage();
            if ($lang == 'all' || $lang === false) {
                $lang = ArContactUsTools::getDefaultLanguage();
            }
            $promptItems = ArContactUsPromptModel::find()->join(ArContactUsPromptModel::langTableName() . ' `_lang`', 'id_item = id')->where(array('lang' => $lang))->orderBy('`position` ASC')->all();
            $items = ArContactUsModel::find()->join(ArContactUsModel::langTableName() . ' `_lang`', 'id_item = id')->where(array('lang' => $lang))->orderBy('`position` ASC')->all();
        } else {
            $promptItems = ArContactUsPromptModel::find()->orderBy('`position` ASC')->all();
            $items = ArContactUsModel::find()->orderBy('`position` ASC')->all();
        }
        
        foreach ($items as $item) {
            $item->params = is_null($item->params) ? new stdClass() : json_decode($item->params);
        }
        
        if ($this->getGeneralConfig()->timezone == 'auto' || empty($this->getGeneralConfig()->timezone)) {
            $tz = date_default_timezone_get();
        } else {
            $tz = $this->getGeneralConfig()->timezone;
        }
        
        $ts = time();
        $d = new DateTime("now", new DateTimeZone($tz));
        $d->setTimestamp($ts);
        
        echo self::render('/admin/config.php', array(
            'generalConfig' => $this->generalConfig,
            'activated' => $updater->isActivated(),
            'buttonConfig' => $this->buttonConfig,
            'mobileButtonConfig' => $this->mobileButtonConfig,
            'menuConfig' => $this->menuConfig,
            'mobileMenuConfig' => $this->mobileMenuConfig,
            'popupConfig' => $this->popupConfig,
            'promptConfig' => $this->promptConfig,
            'mobilePromptConfig' => $this->mobilePromptConfig,
            'welcomeConfig' => $this->welcomeConfig,
            'liveChatsConfig' => $this->liveChatsConfig,
            'integrations' => $this->liveChatsConfig->getIntegrations(),
            'formsConfig' => $this->formsConfig,
            'emailsConfig' => $this->emailConfig,
            'errors' => $this->errors,
            'success' => $this->success,
            'importSuccess' => $this->importSuccess,
            'callbackList' => new ArContactUsListTable(),
            'items' => $items,
            'promptItems' => $promptItems,
            'activeSubmit' => $this->getSubmit(),
            'brandColors' => $this->getBrandColors(),
            'isWPML' => ArContactUsTools::isMultilang(),
            'languages' => ArContactUsTools::getLanguages(),
            'defaultLang' => ArContactUsTools::getDefaultLanguage(),
            'currentLang' => ArContactUsTools::getCurrentLanguage(),
            'currentTime' => $d->format('H:i:s'), // date('H:i:s'),
            'arcu_channel' => get_option('ARCU_CHANNEL', 'prod'),
            'scheduleDays' => array(
                'Mon',
                'Tue',
                'Wed',
                'Thu',
                'Fri',
                'Sat',
                'Sun'
            )
        ));
    }
    
    public function getBrandColors()
    {
        return array(
            'Skype' => '00aff0',
            'Viber' => '8f5db7',
            'Facebook Messenger' => '0084ff',
            'WhatsApp' => '25d366',
            'Telegram' => '0088cc',
            'Facebook' => '3b5998',
            'Android' => 'a4c639',
            'Zendesk' => 'f79a3e',
            'Vkontakte' => '45668e',
            'Twitter' => '1da1f2'
        );
    }
    
    public function registerJs()
    {
        wp_enqueue_media();
        return parent::registerJs();
    }
    
    public function js()
    {
        return array(
            'jquery' => null,
            'jquery-ui-sortable' => null,
            'ace-css' => 'res/js/ace/src-min/ace.js',
            'semantic-ui.js' => 'res/semantic-ui/semantic.min.js',
            'moment.js' => 'res/js/moment.min.js',
            'color.js' => 'res/js/color.js',
            'admin.js' => 'res/js/admin.js'
        );
    }
    
    public function css()
    {
        return array(
            'semantic-ui-combined.css' => 'res/semantic-ui/semantic-combined.min.css',
            'arcontactus-admin.css' => 'res/css/admin.css'
        );
    }
    
    public function loadResources()
    {
        global $hook_suffix;
        if ($hook_suffix == 'settings_page_ar-contactus-key-config' || $hook_suffix == 'toplevel_page_ar-contactus-key-requests' || $hook_suffix == 'toplevel_page_ar-contactus-email-requests') {
            $this->registerCss();
            $this->registerJs();
        }
    }
}
