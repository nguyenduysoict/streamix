<?php
ArContactUsLoader::loadModel('ArContactUsConfigModel');

class ArContactUsConfigGeneral extends ArContactUsConfigModel
{
    public $mobile;
    public $sandbox;
    public $allowed_ips;
    public $timezone;
    public $allowed_pages;
    public $pages;
    public $fa_css;
    public $minify;
    public $disable_init;
    public $hide_on_load;
    public $disable_jquery;
    public $delay_init;
    public $ga_account;
    public $ga_script;
    public $ga_tracker;
    public $disable_callback_menu;
    public $disable_email_menu;
    public $callback_access;
    public $font;
    public $custom_css;
    
    public function beforeSave()
    {
        $this->font = stripslashes($this->font);
        $this->font = str_replace('"', "'", $this->font);
        return parent::beforeSave();
    }
    
    public function getJsonConfigKey()
    {
        return 'arcug';
    }
    
    public function attributeDefaults()
    {
        return array(
            'mobile' => 1,
            'sandbox' => 0,
            'allowed_ips' => $this->getCurrentIP(),
            'timezone' => 'auto',
            'fa_css' => 1,
            'minify' => 0,
            'disable_init' => 0,
            'hide_on_load' => 0,
            'disable_jquery' => 0,
            'delay_init' => 0,
            'ga_account' => '',
            'ga_script' => 1,
            'ga_tracker' => 1,
            'disable_callback_menu' => 0,
            'disable_email_menu' => 0,
            'callback_access' => array('administrator')
        );
    }
    
    public function getFormTitle()
    {
        return __('General settings', 'ar-contactus');
    }
}
