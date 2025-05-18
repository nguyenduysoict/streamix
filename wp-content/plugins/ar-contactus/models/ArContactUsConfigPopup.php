<?php
ArContactUsLoader::loadModel('ArContactUsConfigModel');

class ArContactUsConfigPopup extends ArContactUsConfigModel
{
    public $popup_width;
    
    public $timeout;
    public $message;
    public $phone_placeholder;
    public $phone_mask_on;
    public $maskedinput;
    public $phone_mask;
    public $proccess_message;
    public $success_message;
    public $fail_message;
    public $close_timeout;
    public $btn_title;
    
    public $hhr1;
    
    public $name;
    public $name_required;
    public $name_title;
    public $name_placeholder;
    public $name_validation;
    public $name_max_len;
    public $name_filter_laters;
    
    public $hhr2;
    
    public $email_field;
    public $email_required;
    public $email_title;
    public $email_placeholder;
    
    public $hhr3;
    
    public $gdpr;
    public $gdpr_title;
    
    public $hhr4;
    
    public $email;
    public $email_list;
    
    public $hr1;
    public $twilio;
    public $twilio_api_key;
    public $twilio_auth_token;
    public $twilio_phone;
    public $twilio_tophone;
    public $twilio_message;
    
    public $hr2;
    public $tg;
    public $tg_token;
    public $tg_chat_id;
    public $tg_text;
    
    public $hr3;
    public $onesignal;
    public $onesignal_alert;
    public $onesignal_app_id;
    public $onesignal_api_key;
    public $onesignal_title;
    public $onesignal_message;
    
    public $hr4;
    public $recaptcha;
    public $key;
    public $secret;
    public $recaptcha_init;
    public $hide_recaptcha;
    public $recaptcha_treshold;
    public $recaptcha_error;
    
    public $hr5;
    public $perfex;
    public $perfex_alert;
    public $perfex_url;
    public $perfex_token;
    
    public function getJsonConfigKey()
    {
        return 'arcup';
    }
    
    public function getFormTitle()
    {
        return __('Callback popup settings', 'ar-contactus');
    }
    
    public function attributeDefaults()
    {
        return array(
            'phone_mask_on' => 0,
            'name_required' => 0,
            'name_validation' => 0,
            'name_filter_laters' => 0,
            'email_field' => 0,
            'email_required' => 0,
            'twilio' => 0,
            'tg' => 0,
            'recaptcha_init' => 0,
            'popup_width' => '360',
            'timeout' => '0',
            'message' => __("Please enter your phone number\nand we call you back soon", 'ar-contactus'),
            'phone_placeholder' => __("+XXX-XX-XXX-XX-XX", 'ar-contactus'),
            'phone_mask' => '+XXX-XX-XXX-XX-XX',
            'proccess_message' => __("We are calling you to phone", 'ar-contactus'),
            'success_message' => __("Thank you.\nWe are call you back soon.", 'ar-contactus'),
            'close_timeout' => 0,
            'fail_message' => __("Connection error. Please refresh the page and try again.", 'ar-contactus'),
            'btn_title' => __("Waiting for call", 'ar-contactus'),
            'tg_text' => __('New callback request from phone: {phone}', 'ar-contactus'),
            'maskedinput' => 1,
            'name' => 0,
            'name_title' => __('Enter your name', 'ar-contactus'),
            'gdpr' => 0,
            'gdpr_title' => __('I accept GDPR rules', 'ar-contactus'),
            'email' => 1,
            'email_list' => $this->getAdminEmail(),
            'onesignal' => 0,
            'onesignal_title' => __('New callback request', 'ar-contactus'),
            'onesignal_message' => __('New callback request received from {site}. Please call to {phone}.', 'ar-contactus'),
            'recaptcha' => 0,
            'hide_recaptcha' => 1,
            'twilio_message' => __("New callback request received from {phone}", 'ar-contactus'),
            'recaptcha_error' => __("ReCaptcha validation error. Please try again.", 'ar-contactus'),
            'recaptcha_treshold' => 0.6,
            'perfex' => 0
        );
    }
    
    public function getAdminEmail()
    {
        return get_option('admin_email');
    }
    
    public function multiLangFields()
    {
        return array(
            'message' => true,
            'phone_placeholder' => true,
            'phone_mask' => true,
            'proccess_message' => true,
            'success_message' => true,
            'fail_message' => true,
            'btn_title' => true,
            'name_title' => true,
            'name_placeholder' => true,
            'gdpr_title' => true,
            'email_title' => true,
            'email_placeholder' => true,
            'recaptcha_error' => true
        );
    }
}
