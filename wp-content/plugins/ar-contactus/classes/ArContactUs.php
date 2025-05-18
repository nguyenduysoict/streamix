<?php
ArContactUsLoader::loadClass('ArContactUsAbstract');
ArContactUsLoader::loadModel('ArContactUsModel');
ArContactUsLoader::loadModel('ArContactUsCallbackModel');
ArContactUsLoader::loadModel('ArContactUsPromptModel');
ArContactUsLoader::loadClass('ArContactUsUpdater');
ArContactUsLoader::loadClass('ArContactUsMinifier');

class ArContactUs extends ArContactUsAbstract
{
    protected $viewParams = [];
    
    public function css()
    {
        $css = array(
            'contactus.css' => 'res/css/contactus.min.css'
        );
        if ($this->isMobile()) {
            if (is_writable(AR_CONTACTUS_PLUGIN_DIR . 'res/css/generated-mobile.css')) {
                $css['contactus.generated.mobile.css'] = 'res/css/generated-mobile.css';
            }
        } else {
            if (is_writable(AR_CONTACTUS_PLUGIN_DIR . 'res/css/generated-desktop.css')) {
                $css['contactus.generated.desktop.css'] = 'res/css/generated-desktop.css';
            }
        }
        if ($this->getGeneralConfig()->fa_css){
            $css['contactus.fa.css'] = 'https://use.fontawesome.com/releases/v5.8.1/css/all.css';
        }
        return $css;
    }
    
    public function js()
    {
        $js = array();
        if (!$this->getGeneralConfig()->disable_jquery) {
            //$js['jquery'] = null;
        }
        $js['contactus'] = array(
            'src' => AR_CONTACTUS_DEBUG? 'res/js/contactus.js' : 'res/js/contactus.min.js',
            'localization' => array(
                'varName' => 'arCUVars',
                'l10n' => array(
                    'url' => admin_url('admin-ajax.php'),
                    'version' => AR_CONTACTUS_VERSION,
                    '_wpnonce' => wp_nonce_field('arcu_callback_form', '_wpnonce', true, false)
                )
            )
        );
        $js['contactus.scripts'] = 'res/js/scripts.js';
        return $js;
    }
    
    public function init()
    {
        load_plugin_textdomain('ar-contactus', false, basename(AR_CONTACTUS_PLUGIN_DIR) . '/languages');
        $this->registerShortcodes();
        add_action('wp_enqueue_scripts', array($this, 'registerAssets'));
        add_action('wp_footer', array($this, 'renderContactUsButton'));
        if ($this->getMobileButtonConfig()->position == 'storefront'){
            add_filter('storefront_handheld_footer_bar_links', array($this, 'storeFrontBottomButton'));
        }
        ArContactUsTools::getDefaultLanguage();
    }
    
    public function registerAssets()
    {
        $this->registerCss();
        $params = $this->getViewParams();
        if ($params) {
            $inlineCss = ArContactUsTools::minifyStyles(self::render('front/inline_styles.php', $params));
            wp_add_inline_style('contactus.css', $inlineCss);
        }
        $this->registerJs();
    }
    
    public function storeFrontBottomButton($links)
    {
        $i = 0;
        $buttonConfig = $this->getMobileButtonConfig();
        $pos = (int)$buttonConfig->storefront_pos;
        $result = array();
        $links['arcontactus'] = array(
            'priority' => 1,
            'callback' => array($this, 'storeFrontBottomButtonLink')
        );
        if ($pos > count($links)){
            $pos = count($links);
        }
        foreach ($links as $k => $link){
            $i ++;
            if ($i == $pos){
                $result['arcontactus'] = $links['arcontactus'];
            }
            if ($k !== 'arcontactus'){
                $result[$k] = $link;
            }
        }
        return $result;
    }
    
    public function storeFrontBottomButtonLink($key, $link)
    {
        $buttonConfig = $this->getMobileButtonConfig();
        echo '<a href="#" id="arcontactus-storefront-btn">' . ArContactUsConfigModel::getIcon($buttonConfig->button_icon) . '</a>';
    }
    
    public function registerShortcodes()
    {
        add_shortcode('contactus_menu_item', array($this, 'contactusMenuItemShortcode'));
    }
    
    public function contactusMenuItemShortcode($params)
    {
        if (empty($params) || !isset($params['id'])){
            return '';
        }
        $id = $params['id'];
        
        if ($model = ArContactUsModel::find()->where(array('id' => $id))->one()){
            if ($model->display == 1 || ($model->display == 2 && !$this->isMobile()) || ($model->display == 3 && $this->isMobile())) {
                if (isset($params['title']) && !empty($params['title'])){
                    $model->title = $params['title'];
                }
                if (isset($model->params) && !empty($model->params)){
                    $model->params = is_null($model->params) ? new stdClass() : json_decode($model->params);
                }
                
                return $this->render('front/shortcodes/menuItem.php', array(
                    'model' => $model,
                    'params' => $params
                ));
            }
        }
        return '';
    }
    
    public function isNeedToDispay($item)
    {
        $params = $item['params'];
        if ((isset($params->always) && $params->always == 1) || !isset($params->always)) {
            return true;
        }
        
        if ($this->getGeneralConfig()->timezone == 'auto' || empty($this->getGeneralConfig()->timezone)) {
            $tz = date_default_timezone_get();
        } else {
            $tz = $this->getGeneralConfig()->timezone;
        }
        
        $ts = time();
        $d = new DateTime("now", new DateTimeZone($tz));
        $d->setTimestamp($ts);
        
        $day = strtolower($d->format('D'));
        if (isset($params->$day) && $params->$day == 0){
            return false;
        }
        $tf = $day . '_time_from';
        $tt = $day . '_time_to';
        
        $timeFrom = explode(':', $params->$tf);
        $timeTo = explode(':', $params->$tt);
        
        $currentTime = explode(':', $d->format('G:i'));
        
        $timeFromValue = ((int)$timeFrom[0] * 60) + (int)$timeFrom[1];
        $timeToValue = ((int)$timeTo[0] * 60) + (int)$timeTo[1];
        
        $currentTimeValue = ((int)$currentTime[0] * 60) + (int)$currentTime[1];
        
        if (($currentTimeValue >= $timeFromValue) && ($currentTimeValue <= $timeToValue)) {
            return true;
        }
        
        return false;
    }
    
    public function getRegexpFromUrl($page)
    {
        if ((strpos($page, 'http://') !== false) || (strpos($page, 'https://') !== false)) {
            // absolute url should be replaced by relative
            $page = preg_replace('{(https?://.*?)/}is', '/', $page);
        }
        if (strpos($page, 'r::') === 0) {
            $page = str_replace('r::', '', $page);
        } else {
            $page = str_replace('*', '.+', $page) . '$';
        }
        return '^' . str_replace(array("\n\r", "\r\n", "\r", "\n"), '', $page);
    }
    
    public function getExcludePages()
    {
        $result = array();
        if ($excludePages = explode(PHP_EOL, $this->getGeneralConfig()->pages)){
            
            foreach ($excludePages as $page) {
                $result[] = array(
                    'orig' => $page,
                    'regexp' => $this->getRegexpFromUrl($page)
                );
            }
        }
        return $result;
    }
    
    public function getAllowedPages()
    {
        $result = array();
        if ($allowedPages = explode(PHP_EOL, $this->getGeneralConfig()->allowed_pages)){
            
            foreach ($allowedPages as $page) {
                $result[] = array(
                    'orig' => $page,
                    'regexp' => $this->getRegexpFromUrl($page)
                );
            }
        }
        return $result;
    }
    
    public function renderContactUsButton()
    {
        $params = $this->getViewParams();
        if (empty($params)) {
            return null;
        }
        if ($this->getGeneralConfig()->minify) {
            $params['mainJs'] = ArContactUsMinifier::minify(self::render('front/main_js.php', $params));
        } else {
            $params['mainJs'] = self::render('front/main_js.php', $params);
        }
        echo self::render('front/button.php', $params);
    }

    public function getViewParams()
    {   
        if (empty($this->viewParams)) {
            if ($this->getGeneralConfig()->allowed_pages){
                $currentPage = $_SERVER['REQUEST_URI'];
                $allowedPages = $this->getAllowedPages();
                $allowToDisplay = false;
                foreach ($allowedPages as $page) {
                    if (preg_match('{' . $page['regexp'] . '}is', $currentPage)) {
                        $allowToDisplay = true;
                    }
                }
                if (!$allowToDisplay) {
                    return null;
                }
            }
            if ($this->getGeneralConfig()->pages){
                $currentPage = $_SERVER['REQUEST_URI'];
                $excludePages = $this->getExcludePages();
                //echo $currentPage;            var_dump($excludePages);die();
                foreach ($excludePages as $page) {
                    if (preg_match('{' . $page['regexp'] . '}is', $currentPage)) {
                        return null;
                    }
                }
            }
            if ($this->getGeneralConfig()->sandbox) {
                $ips = explode("\r\n", $this->getGeneralConfig()->allowed_ips);
                if (!in_array($this->getGeneralConfig()->getCurrentIP(), $ips)) {
                    return null;
                }
            }
            if (!$this->getGeneralConfig()->mobile && $this->isMobile()){
                return null;
            }
            if ($this->isMobile()){
                $buttonConfig = new ArContactUsConfigMobileButton('arcumb_');
                $menuConfig = new ArContactUsConfigMobileMenu('arcumm_');
                $promptConfig = new ArContactUsConfigMobilePrompt('arcumpr_');
            }else{
                $buttonConfig = new ArContactUsConfigButton('arcub_');
                $menuConfig = new ArContactUsConfigMenu('arcum_');
                $promptConfig = new ArContactUsConfigPrompt('arcupr_');
            }

            $popupConfig = new ArContactUsConfigPopup('arcup_');
            $liveChatsConfig = new ArContactUsConfigLiveChat('arcul_');

            $buttonConfig->loadFromConfig();
            $menuConfig->loadFromConfig();
            $popupConfig->loadFromConfig();
            $promptConfig->loadFromConfig();
            $liveChatsConfig->loadFromConfig();
            $formsConfig = new ArContactUsConfigForms();
            if ($menuConfig->menu_layout == 'personal') {
                $welcomeConfig = new ArContactUsConfigWelcome('arcuw_');
                $welcomeConfig->loadFromConfig();
            } else {
                $welcomeConfig = null;
            }

            if (is_user_logged_in()){
                $logged = '(registered_only IN (0, 1) OR registered_only IS NULL)';
            }else{
                $logged = '(registered_only IN (0, 2) OR registered_only IS NULL)';
            }

            $langs = array();
            $defaultLang = null;
            $isWPML = ArContactUsTools::isMultilang();
            $currentLang = ArContactUsTools::getCurrentLanguage();

            if ($isWPML) {
                $langs = ArContactUsTools::getLanguages();
                $defaultLang = ArContactUsTools::getDefaultLanguage();

                $models = ArContactUsModel::find()
                    ->join(ArContactUsModel::langTableName() . ' `_lang`', "`_lang`.id_item = id")
                    ->where(array('status' => 1))
                    ->andWhere($logged)
                    ->andWhere(array('lang' => ArContactUsTools::getCurrentLanguage()))
                    ->andWhere('(`language` IS NULL OR `language` IN ("", "' . $currentLang . '"))')
                    ->orderBy('`position` ASC')
                    ->all();
            }else{
                $models = ArContactUsModel::find()
                    ->where(array('status' => 1))
                    ->andWhere($logged)
                    ->orderBy('`position` ASC')
                    ->all();
            }
            if ($popupConfig->recaptcha && $popupConfig->key && $popupConfig->recaptcha_init){
                $deps = array();
                wp_register_script('arcontactus-google-recaptcha-v3', 'https://www.google.com/recaptcha/api.js?render=' . $popupConfig->key, $deps, AR_CONTACTUS_VERSION);
                wp_enqueue_script('arcontactus-google-recaptcha-v3');
            }
            if ($popupConfig->phone_mask_on && $popupConfig->maskedinput) {
                // moved to template
                //wp_register_script('arcontactus-masked-input', AR_CONTACTUS_PLUGIN_URL . 'res/js/jquery.maskedinput.min.js', array('jquery'), AR_CONTACTUS_VERSION);
                //wp_enqueue_script('arcontactus-masked-input');
            }
            if ($buttonConfig->drag){
                //wp_enqueue_script('jquery-ui-draggable');
            }
            $items = array();
            $tawkTo = false;
            $crisp = false;
            $intercom = false;
            $facebook = false;
            $vkChat = false;
            $skype = false;
            $zopim = false;
            $zalo = false;
            $lhc = false;
            $lc = false;
            $ss = false;
            $lcp = false;
            $liveZilla = false;
            $tidio = false;
            $jivosite = false;
            $zoho = false;
            $freshChat = false;
            $phplive = false;
            $paldesk = false;
            $apple = false;

            foreach ($models as $model){
                $params = is_null($model->params) ? new stdClass() : json_decode($model->params);
                if ($model->display == 1 || ($model->display == 2 && !$this->isMobile()) || ($model->display == 3 && $this->isMobile())) {
                    $item = array(
                        'href' => $model->getLink(),
                        'color' => '#' . $model->color,
                        'title' => $model->title,
                        'subtitle' => $model->subtitle,
                        'content' => is_null($model->content) ? '' : do_shortcode($model->content),
                        'id' => 'msg-item-' . $model->id,
                        'class' => 'msg-item-' . (ArContactUsConfigModel::isFontAwesomeStatic($model->icon)? 'fa' : $model->icon),
                        'type' => $model->type,
                        'integration' => $model->integration,
                        'target' => $model->target,
                        'js' => $model->js,
                        'icon' => ArContactUsConfigModel::getIcon($model->icon),
                        'params' => $params,
                        'online' => isset($params->online_badge) && $params->online_badge? 1 : null
                    );
                    if ((strpos($item['href'], '//bcrw.apple.com/') !== false) && $item['type'] == ArContactUsModel::TYPE_LINK) {
                        $apple = true;
                    }
                    if (!$this->isNeedToDispay($item)){
                        if ($params->inactive == 1) {
                            continue;
                        } else {
                            $item['online'] = 0;
                        }
                    }
                    if ($model->type == ArContactUsModel::TYPE_INTEGRATION){
                        switch ($model->integration){
                            case 'tawkto':
                                $tawkTo = true;
                                break;
                            case 'crisp':
                                $crisp = true;
                                break;
                            case 'intercom':
                                $intercom = true;
                                break;
                            case 'facebook':
                                $facebook = true;
                                break;
                            case 'vk':
                                $vkChat = true;
                                break;
                            case 'zopim':
                                $zopim = true;
                                break;
                            case 'skype':
                                $skype = true;
                                break;
                            case 'zalo':
                                $zalo = true;
                            case 'lhc':
                                $lhc = true;
                                break;
                            case 'smartsupp':
                                $ss = true;
                                break;
                            case 'livechat':
                                $lc = true;
                                break;
                            case 'livechatpro':
                                $lcp = true;
                                break;
                            case 'livezilla':
                                $liveZilla = true;
                                break;
                            case 'tidio':
                                $tidio = true;
                                break;
                            case 'jivosite':
                                $jivosite = true;
                                break;
                            case 'zoho':
                                $zoho = true;
                                break;
                            case 'fc':
                                $freshChat = true;
                                break;
                            case 'phplive':
                                $phplive = true;
                                break;
                            case 'paldesk':
                                $paldesk = true;
                                break;
                        }
                    }
                    $items[] = $item;
                }
            }
            if ($this->isMobile()){
                $generatedCssFileName = AR_CONTACTUS_PLUGIN_DIR . 'res/css/generated-mobile.css';
            }else{
                $generatedCssFileName = AR_CONTACTUS_PLUGIN_DIR . 'res/css/generated-desktop.css';
            }
            $generatedCss = '';
            if (!is_writable($generatedCssFileName)){
                $generatedCss = self::render('front/styles.php', array(
                    'menuConfig' => $menuConfig,
                    'popupConfig' => $popupConfig,
                    'buttonConfig' => $buttonConfig,
                    'formsConfig' => $formsConfig,
                    'isMobile' => $this->isMobile(),
                    'generalConfig' => $this->generalConfig
                ));
                $generatedCss = ArContactUsTools::minifyStyles($generatedCss);
            }

            $this->viewParams = array(
                'generatedCss' => $generatedCss,
                'generalConfig' => $this->getGeneralConfig(),
                'buttonConfig' => $buttonConfig,
                'menuConfig' => $menuConfig,
                'popupConfig' => $popupConfig,
                'promptConfig' => $promptConfig,
                'liveChatsConfig' => $liveChatsConfig,
                'formsConfig' => $formsConfig,
                'welcomeConfig' => $welcomeConfig,
                'buttonIcon' => ArContactUsConfigModel::getIcon($buttonConfig->button_icon),
                'tawkTo' => $liveChatsConfig->isTawkToIntegrated() && $tawkTo,
                'crisp' => $liveChatsConfig->isCrispIntegrated() && $crisp,
                'intercom' => $liveChatsConfig->isIntercomIntegrated() && $intercom,
                'facebook' => $liveChatsConfig->isFacebookChatIntegrated() && $facebook,
                'vkChat' => $liveChatsConfig->isVkIntegrated() && $vkChat,
                'zopim' => $liveChatsConfig->isZopimIntegrated() && $zopim,
                'skype' => false, //$liveChatsConfig->isSkypeIntegrated() && $skype,
                'zalo' => $liveChatsConfig->isZaloIntegrated() && $zalo,
                'lhc' => $liveChatsConfig->isLhcIntegrated() && $lhc,
                'lc' => $liveChatsConfig->isLiveChatIntegrated() && $lc,
                'ss' => $liveChatsConfig->isSmartsuppIntegrated() && $ss,
                'lcp' => $liveChatsConfig->isLiveChatProIntegrated() && $lcp,
                'liveZilla' => $liveChatsConfig->isLiveZillaIntegrated() && $liveZilla,
                'tidio' => $liveChatsConfig->isTidioIntegrated() && $tidio,
                'jivosite' => $liveChatsConfig->isJivositeIntegrated() && $jivosite,
                'zoho' => $liveChatsConfig->isZohoIntegrated() && $zoho,
                'freshChat' => $liveChatsConfig->isFreshChatIntegrated() && $freshChat,
                'phplive' => $liveChatsConfig->isPhpLiveIntegrated() && $phplive,
                'paldesk' => $liveChatsConfig->isPaldeskIntegrated() && $paldesk,
                'apple' => $apple,
                'user' => wp_get_current_user(),
                'messages' => $promptConfig->enable_prompt? ArContactUsPromptModel::getMessages() : array(),
                'items' => $items,
                'isMobile' => $this->isMobile(),

                'wpml' => $isWPML,
                'languages' => $langs,
                'defaultLang' => $defaultLang,
                'currentLang' => $currentLang
            );
        }
        return $this->viewParams;
    }

    public function activate()
    {
        if (!get_option('arcu_installed')){
            ArContactUsModel::createTable();
            ArContactUsModel::createLangTable();
            ArContactUsModel::createDefaultMenuItems();
            ArContactUsCallbackModel::createTable();
            ArContactUsPromptModel::createTable();
            ArContactUsPromptModel::createLangTable();
            ArContactUsPromptModel::createDefaultItems();
            
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
            $formsConfig = new ArContactUsConfigForms();
            
            $generalConfig->loadDefaults();
            $generalConfig->saveToConfig();
            
            $buttonConfig->loadDefaults();
            $buttonConfig->saveToConfig();
            
            $mobileButtonConfig->loadDefaults();
            $mobileButtonConfig->saveToConfig();
            
            $menuConfig->loadDefaults();
            $menuConfig->saveToConfig();
            
            $mobileMenuConfig->loadDefaults();
            $mobileMenuConfig->saveToConfig();
            
            $popupConfig->loadDefaults();
            $popupConfig->saveToConfig();
            
            $promptConfig->loadDefaults();
            $promptConfig->saveToConfig();
            
            $mobilePromptConfig->loadDefaults();
            $mobilePromptConfig->saveToConfig();
            
            $integrationConfig->loadDefaults();
            $integrationConfig->saveToConfig(false);
            
            $emailsConfig->loadDefaults();
            $emailsConfig->saveToConfig();
            
            $welcomeConfig->loadDefaults();
            $welcomeConfig->saveToConfig();
            
            $formsConfig->buildDefaultForms();
            
            wp_schedule_event(time(), 'twicedaily', 'arcontactus_check_event');
            
            $admin = new ArContactUsAdmin();
            $admin->init();
            
            $admin->compileCSS();
            $admin->compileCSS(true);
            
            update_option('arcu_installed', time());
        }
        $updater = new ArContactUsUpdater();
        $updater->migrate();
        return true;
    }
}
