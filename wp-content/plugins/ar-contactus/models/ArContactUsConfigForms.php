<?php
ArContactUsLoader::loadModel('ArContactUsForm');

class ArContactUsConfigForms {
    protected $forms;
    
    protected static $unremovableField = array(
        'callback' => array(
            'phone'
        ),
        'email' => array(
            'email',
            'message'
        )
    );


    public function __construct()
    {
        $this->loadForms();
    }
    
    public static function getUnremovebleFields($formId)
    {
        return isset(self::$unremovableField[$formId])? self::$unremovableField[$formId] : array();
    }
    
    public function loadForms()
    {
        if ($json = get_option('arcuforms')) {
            $config = json_decode($json, true);

            foreach($config as $formId => $formData) {
                $this->forms[$formId] = new ArContactUsForm($formId, $formData);
                $this->forms[$formId]->setUnremovableFields($this->getUnremovebleFields($formId));
            }
        }
    }
    
    public function setForms($forms)
    {
        $this->forms = $forms;
    }
    
    public function setForm($id, $form)
    {
        $this->forms[$id] = $form;
    }
    
    public function getForms()
    {
        return $this->forms;
    }
    
    /**
     * 
     * @param string $id
     * @return ArContactUsForm
     */
    public function getForm($id)
    {
        return isset($this->forms[$id])? $this->forms[$id] : null;
    }
    
    public function save()
    {
        update_option('arcuforms', json_encode($this->forms));
    }
    
    public function buildDefaultForms($popupConfigData = array())
    {
        if (empty($popupConfigData)) {
            $popupConfig = get_option('arcup');
            if ($popupConfig) {
                $popupConfigData = json_decode($popupConfig, true);
            } else {
                $popupConfigData = array();
            }
        }
        $forms = array(
            'callback' => $this->buildDefaultCallbackForm($popupConfigData),
            'email' => $this->buildDefaultEmailForm($popupConfigData)
        );
        $this->setForms($forms);
        $this->save();
    }
    
    protected function buildDefaultCallbackForm($popupConfigData)
    {
        $formData = array(
            'id' => 'callback',
            'layout' => 2,
            'header' => __('Leave your phone number. We will call you back soon!', 'ar-contactus'),
            'button_icon_type' => 'svg',
            'button_icon_svg' => 'phone',
            'successContent' => __('Callback request sent! We will contact you soon.', 'ar-contactus'),
            'failContent' => __('Error sending callback request! Please try again!', 'ar-contactus'),
            'desktopWidth' => 300
        );
        if (isset($popupConfigData['ARCUP_SUCCESS_MESSAGE'])) {
            $formData['successContent'] = $popupConfigData['ARCUP_SUCCESS_MESSAGE'];
        }
        if (isset($popupConfigData['ARCUP_FAIL_MESSAGE'])) {
            $formData['failContent'] = $popupConfigData['ARCUP_FAIL_MESSAGE'];
        }
        if (isset($popupConfigData['ARCUP_MESSAGE'])) {
            $formData['header'] = $popupConfigData['ARCUP_MESSAGE'];
        }
        if (isset($popupConfigData['ARCUP_POPUP_WIDTH'])) {
            $formData['desktopWidth'] = $popupConfigData['ARCUP_POPUP_WIDTH'];
        }
        
        if (isset($popupConfigData['ARCUP_EMAIL']) && isset($popupConfigData['ARCUP_EMAIL_LIST'])) {
            if ($popupConfigData['ARCUP_EMAIL'] && $popupConfigData['ARCUP_EMAIL_LIST']) {
                $formData['email_on'] = 1;
                $formData['email_list'] = $popupConfigData['ARCUP_EMAIL_LIST'];
            }
        } else {
            $formData['email_on'] = 1;
            $formData['email_list'] = get_option('admin_email');
        }
        
        if (isset($popupConfigData['ARCUP_TWILIO']) && $popupConfigData['ARCUP_TWILIO'] 
                && isset($popupConfigData['ARCUP_TWILIO_API_KEY']) && $popupConfigData['ARCUP_TWILIO_API_KEY']
                && isset($popupConfigData['ARCUP_TWILIO_AUTH_TOKEN']) && $popupConfigData['ARCUP_TWILIO_AUTH_TOKEN']
                && isset($popupConfigData['ARCUP_TWILIO_PHONE']) && $popupConfigData['ARCUP_TWILIO_PHONE']
                && isset($popupConfigData['ARCUP_TWILIO_TOPHONE']) && $popupConfigData['ARCUP_TWILIO_TOPHONE']
                && isset($popupConfigData['ARCUP_TWILIO_MESSAGE']) && $popupConfigData['ARCUP_TWILIO_MESSAGE']) {
            $formData['twilio_on'] = 1;
            $formData['twilio_phone'] = $popupConfigData['ARCUP_TWILIO_TOPHONE'];
            $formData['twilio_message'] = $popupConfigData['ARCUP_TWILIO_MESSAGE'];
        }
        
        if (isset($popupConfigData['ARCUP_TG']) && $popupConfigData['ARCUP_TG'] 
                && isset($popupConfigData['ARCUP_TG_TOKEN']) && $popupConfigData['ARCUP_TG_TOKEN']
                && isset($popupConfigData['ARCUP_TG_CHAT_ID']) && $popupConfigData['ARCUP_TG_CHAT_ID'] 
                && isset($popupConfigData['ARCUP_TG_TEXT']) && $popupConfigData['ARCUP_TG_TEXT']) {
            $formData['tg_on'] = 1;
            $formData['tg_chat_id'] = $popupConfigData['ARCUP_TG_CHAT_ID'];
            $formData['tg_message'] = $popupConfigData['ARCUP_TG_TEXT'];
        }
        
        if (isset($popupConfigData['ARCUP_ONESIGNAL']) && $popupConfigData['ARCUP_ONESIGNAL'] 
                && isset($popupConfigData['ARCUP_ONESIGNAL_APP_ID']) && $popupConfigData['ARCUP_ONESIGNAL_APP_ID']
                && isset($popupConfigData['ARCUP_ONESIGNAL_API_KEY']) && $popupConfigData['ARCUP_ONESIGNAL_API_KEY'] 
                && isset($popupConfigData['ARCUP_ONESIGNAL_TITLE']) && $popupConfigData['ARCUP_ONESIGNAL_TITLE']
                && isset($popupConfigData['ARCUP_ONESIGNAL_MESSAGE']) && $popupConfigData['ARCUP_ONESIGNAL_MESSAGE']){
            $formData['onesignal_on'] = 1;
            $formData['onesignal_title'] = $popupConfigData['ARCUP_ONESIGNAL_TITLE'];
            $formData['onesignal_message'] = $popupConfigData['ARCUP_ONESIGNAL_MESSAGE'];
        }
        
        $fields = array();
        
        if (isset($popupConfigData['ARCUP_NAME'])) {
            if ($popupConfigData['ARCUP_NAME']) {
                $fields['name'] = array(
                    'id' => 'name',
                    'type' => 'text',
                    'label' => isset($popupConfigData['ARCUP_NAME_TITLE'])? $popupConfigData['ARCUP_NAME_TITLE'] : __('Your name', 'ar-contactus'),
                    'placeholder' => isset($popupConfigData['ARCUP_NAME_PLACEHOLDER'])? $popupConfigData['ARCUP_NAME_PLACEHOLDER'] : __('Enter your name', 'ar-contactus'),
                    'validation' => 'letters',
                    'required' => isset($popupConfigData['ARCUP_NAME_REQUIRED'])? $popupConfigData['ARCUP_NAME_REQUIRED'] : 0,
                    'report' => 1,
                    'report_label' => __('Name', 'ar-contactus'),
                );
            }
        } else {
            $fields['name'] = array(
                'id' => 'name',
                'type' => 'text',
                'label' => __('Your name', 'ar-contactus'),
                'placeholder' => __('Enter your name', 'ar-contactus'),
                'validation' => 'letters',
                'required' => 0,
                'report' => 1,
                'report_label' => __('Name', 'ar-contactus'),
            );
        }
        
        $fields['phone'] = array(
            'id' => 'phone',
            'type' => 'tel',
            'label' => __('Your phone number', 'ar-contactus'),
            'placeholder' => isset($popupConfigData['ARCUP_PHONE_PLACEHOLDER'])? $popupConfigData['ARCUP_PHONE_PLACEHOLDER'] : __('Enter your phone number', 'ar-contactus'),
            'validation' => 'advanced',
            'preg' => '^[0-9-+)(\s]+$',
            'required' => 1,
            'report' => 1,
            'report_label' => __('Phone', 'ar-contactus'),
            'mask_on' => isset($popupConfigData['ARCUP_PHONE_MASK_ON'])? $popupConfigData['ARCUP_PHONE_MASK_ON'] : 0,
            'mask' => isset($popupConfigData['ARCUP_PHONE_MASK'])? $popupConfigData['ARCUP_PHONE_MASK'] : '',
        );
        
        if (isset($popupConfigData['ARCUP_EMAIL_FIELD'])) {
            if ($popupConfigData['ARCUP_EMAIL_FIELD']) {
                $fields['email'] = array(
                    'id' => 'email',
                    'type' => 'email',
                    'label' => isset($popupConfigData['ARCUP_EMAIL_TITLE'])? $popupConfigData['ARCUP_EMAIL_TITLE'] : __('Your email', 'ar-contactus'),
                    'placeholder' => isset($popupConfigData['ARCUP_EMAIL_PLACEHOLDER'])? $popupConfigData['ARCUP_EMAIL_PLACEHOLDER'] : __('Enter your email', 'ar-contactus'),
                    'validation' => 'email',
                    'required' => isset($popupConfigData['ARCUP_EMAIL_REQUIRED'])? $popupConfigData['ARCUP_EMAIL_REQUIRED'] : 0,
                    'report' => 1,
                    'report_label' => __('Email', 'ar-contactus'),
                );
            }
        }
        
        if (isset($popupConfigData['ARCUP_GDPR'])) {
            if ($popupConfigData['ARCUP_GDPR']) {
                $fields['gdpr'] = array(
                    'id' => 'gdpr',
                    'type' => 'checkbox',
                    'label' => isset($popupConfigData['ARCUP_GDPR_TITLE'])? $popupConfigData['ARCUP_GDPR_TITLE'] : __('I accept GDPR rules', 'ar-contactus'),
                    'value' => 1,
                    'required' => 1,
                    'report' => 0
                );
            }
        } else {
            $fields['gdpr'] = array(
                'id' => 'gdpr',
                'type' => 'checkbox',
                'label' => __('I accept GDPR rules', 'ar-contactus'),
                'value' => 1,
                'required' => 1,
                'report' => 0
            );
        }
        
        $formData['fields'] = $fields;
        
        $formData['buttons'] = array(
            'submit' => array(
                'id' => 'submit',
                'type' => 'submit',
                'label' => isset($popupConfigData['ARCUP_BTN_TITLE'])? $popupConfigData['ARCUP_BTN_TITLE'] : __('Submit', 'ar-contactus')
            )
        );
        
        $form = new ArContactUsForm('callback', $formData);
        
        return $form;
    }
    
    protected function buildDefaultEmailForm($popupConfigData)
    {
        $formData = array(
            'id' => 'email',
            'layout' => 2,
            'header' => __('Write a email to us!', 'ar-contactus'),
            'button_icon_type' => 'svg',
            'button_icon_svg' => 'envelope',
            'successContent' => __('Email sent! We will contact you soon.', 'ar-contactus'),
            'failContent' => __('Error sending email! Please try again!', 'ar-contactus'),
            'desktopWidth' => 300
        );
        if (isset($popupConfigData['ARCUP_EMAIL']) && isset($popupConfigData['ARCUP_EMAIL_LIST'])) {
            if ($popupConfigData['ARCUP_EMAIL'] && $popupConfigData['ARCUP_EMAIL_LIST']) {
                $formData['email_on'] = 1;
                $formData['email_list'] = $popupConfigData['ARCUP_EMAIL_LIST'];
            }
        } else {
            $formData['email_on'] = 1;
            $formData['email_list'] = get_option('admin_email');
        }
        $formData['fields'] = array(
            'name' => array(
                'id' => 'name',
                'type' => 'text',
                'label' => __('Your name', 'ar-contactus'),
                'placeholder' => __('Enter your name', 'ar-contactus'),
                'validation' => 'letters',
                'report' => 1,
                'report_label' => __('Name', 'ar-contactus'),
            ),
            'email' => array(
                'id' => 'email',
                'type' => 'email',
                'label' => __('Your email', 'ar-contactus'),
                'placeholder' => __('Enter your email', 'ar-contactus'),
                'validation' => 'email',
                'required' => 1,
                'report' => 1,
                'report_label' => __('Email', 'ar-contactus'),
            ),
            'message' => array(
                'id' => 'message',
                'type' => 'textarea',
                'label' => __('Your message', 'ar-contactus'),
                'placeholder' => __('Enter your message', 'ar-contactus'),
                'validation' => 'letters_numbers',
                'required' => 1,
                'report' => 1,
                'report_label' => __('Message', 'ar-contactus'),
            ),
            'gdpr' => array(
                'id' => 'gdpr',
                'type' => 'checkbox',
                'label' => __('I accept GDPR rules', 'ar-contactus'),
                'value' => 1,
                'required' => 1,
                'report' => 0
            )
        );
        $formData['buttons'] = array(
            'submit' => array(
                'id' => 'submit',
                'type' => 'submit',
                'label' => __('Submit', 'ar-contactus')
            )
        );
        $form = new ArContactUsForm('email', $formData);
        return $form;
    }
}
