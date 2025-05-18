<?php
ArContactUsLoader::loadModel('ArContactUsModel');
ArContactUsLoader::loadModel('ArContactUsConfigGeneral');
ArContactUsLoader::loadModel('ArContactUsConfigButton');
ArContactUsLoader::loadModel('ArContactUsConfigMobileButton');
ArContactUsLoader::loadModel('ArContactUsConfigMenu');
ArContactUsLoader::loadModel('ArContactUsConfigMobileMenu');
ArContactUsLoader::loadModel('ArContactUsConfigPopup');
ArContactUsLoader::loadModel('ArContactUsConfigPrompt');
ArContactUsLoader::loadModel('ArContactUsConfigWelcome');
ArContactUsLoader::loadModel('ArContactUsConfigMobilePrompt');
ArContactUsLoader::loadModel('ArContactUsConfigLiveChat');
ArContactUsLoader::loadModel('ArContactUsConfigForms');
ArContactUsLoader::loadModel('ArContactUsConfigEmails');
ArContactUsLoader::loadModel('ArContactUsForm');
ArContactUsLoader::loadModel('ArContactUsFormField');
ArContactUsLoader::loadModel('ArContactUsFormButton');

abstract class ArContactUsAbstract
{
    public $generalConfig;
    public $mobileButtonConfig;
    
    /**
     * 
     * @return ArContactUsConfigGeneral
     */
    public function getGeneralConfig()
    {
        if (!$this->generalConfig) {
            $this->generalConfig = new ArContactUsConfigGeneral('arcug_');
        }
        if (!$this->generalConfig->isLoaded()) {
            $this->generalConfig->loadFromConfig();
        }
        return $this->generalConfig;
    }
    
    /**
     * 
     * @return ArContactUsConfigMobileButton
     */
    public function getMobileButtonConfig()
    {
        if (!$this->mobileButtonConfig) {
            $this->mobileButtonConfig = new ArContactUsConfigMobileButton('arcumb_');
        }
        if (!$this->mobileButtonConfig->isLoaded()) {
            $this->mobileButtonConfig->loadFromConfig();
        }
        return $this->mobileButtonConfig;
    }
    
    abstract public function init();
    
    public function activate()
    {
        return true;
    }
    
    public function deactivate()
    {
        wp_clear_scheduled_hook('arcontactus_check_event');
        return true;
    }

    public function css()
    {
        return array();
    }
    
    public function js()
    {
        return array();
    }
    
    public function registerJs()
    {
        $deps = array();
        
        foreach ($this->js() as $key => $file){
            if (is_array($file)) {
                $src = $file['src'];
                wp_enqueue_script($key, AR_CONTACTUS_PLUGIN_URL . $src, $deps, AR_CONTACTUS_VERSION);
                if (isset($file['localization']) && isset($file['localization']['varName']) && isset($file['localization']['l10n'])) {
                    wp_localize_script($key, $file['localization']['varName'], $file['localization']['l10n']);
                }
            } else {
                if ($file){
                    wp_enqueue_script($key, AR_CONTACTUS_PLUGIN_URL . $file, $deps, AR_CONTACTUS_VERSION);
                } else {
                    wp_enqueue_script($key);
                }
            }
        }
    }
    
    public function registerCss()
    {
        foreach ($this->css() as $key => $file){
            if (preg_match('/https?:/is', $file)){
                $url = $file;
            } else {
                $url = AR_CONTACTUS_PLUGIN_URL . $file;
            }
            if (strpos($key, 'generated') !== false) {
                wp_enqueue_style($key, $url, array(), get_option('arcu_css_generated'));
            }else{
                wp_enqueue_style($key, $url, array(), AR_CONTACTUS_VERSION);
            }
        }
    }
    
    public static function render($view, $viewData = array())
    {
        ob_start();
        extract($viewData);
	include AR_CONTACTUS_PLUGIN_DIR . 'views/' . $view;
	$output = ob_get_clean();
	return $output;
    }
    
    public static function isSubmit($submit)
    {
        return (
            isset($_POST[$submit]) || isset($_POST[$submit.'_x']) || isset($_POST[$submit.'_y'])
            || isset($_GET[$submit]) || isset($_GET[$submit.'_x']) || isset($_GET[$submit.'_y'])
        );
    }
    
    public function isMobile()
    {
        return ArContactUsTools::isMobile();
    }
}
