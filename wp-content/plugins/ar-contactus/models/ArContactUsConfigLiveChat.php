<?php
ArContactUsLoader::loadModel('ArContactUsConfigModel');

class ArContactUsConfigLiveChat extends ArContactUsConfigModel
{
    public $tawk_to_head;
    public $tawk_to_on;
    public $tawk_to_site_id;
    public $tawk_to_widget;
    public $tawk_to_userinfo;
    public $hr1;
    
    public $crisp_head;
    public $crisp_on;
    public $crisp_site_id;
    public $hr2;
    
    public $intercom_head;
    public $intercom_on;
    public $intercom_app_id;
    public $hr3;
    
    public $fb_head;
    public $fb_alert;
    public $fb_on;
    public $fb_page_id;
    public $fb_init;
    public $fb_lang;
    public $fb_color;
    public $hr4;
    
    public $vk_head;
    public $vk_on;
    public $vk_page_id;
    public $hr5;
    
    public $zopim_head;
    public $zopim_on;
    public $zopim_id;
    public $zopim_userinfo;
    public $hr6;
    
    /* 
     * Skype is removed
     */
    
    /* public $skype_head;
    public $skype_on;
    public $skype_type;
    public $skype_id;
    public $skype_message_color;
    public $hr7; */
    
    public $zalo_head;
    public $zalo_on;
    public $zalo_id;
    public $zalo_welcome;
    public $zalo_width;
    public $zalo_height;
    public $hr8;
    
    public $lhc_head;
    public $lhc_on;
    public $lhc_uri;
    public $lhc_width;
    public $lhc_height;
    public $lhc_popup_width;
    public $lhc_popup_height;
    public $hr9;
    
    public $ss_head;
    public $ss_on;
    public $ss_key;
    public $ss_userinfo;
    public $hr10;
    
    public $lc_head;
    public $lc_on;
    public $lc_key;
    public $lc_userinfo;
    public $hr11;
    
    public $lcp_head;
    public $lcp_on;
    public $lcp_uri;
    public $hr12;
    
    public $lz_head;
    public $lz_on;
    public $lz_id;
    public $hr13;
    
    public $tidio_head;
    public $tidio_on;
    public $tidio_key;
    public $tidio_userinfo;
    public $hr14;
    
    public $jivosite_head;
    public $jivosite_on;
    public $jivosite_id;
    public $jivosite_userinfo;
    public $hr15;
    
    public $zoho_head;
    public $zoho_on;
    public $zoho_id;
    public $zoho_host;
    public $hr16;
    
    public $fc_head;
    public $fc_on;
    public $fc_token;
    public $fc_host;
    public $fc_userinfo;
    public $hr17;
    
    public $phplive_head;
    public $phplive_on;
    public $phplive_src;
    public $phplive_userinfo;
    public $hr18;
    
    public $paldesk_head;
    public $paldesk_on;
    public $paldesk_key;
    public $paldesk_userinfo;
    public $hr19;
    
    public function getJsonConfigKey()
    {
        return 'arcul';
    }
    
    public function getIntegrations()
    {
        $integrations = array();
        if ($this->isTawkToIntegrated()) {
            $integrations['tawkto'] = __('Tawk.to', 'ar-contactus');
        }
        if ($this->isCrispIntegrated()) {
            $integrations['crisp'] = __('Crisp', 'ar-contactus');
        }
        if ($this->isIntercomIntegrated()) {
            $integrations['intercom'] = __('Intercom', 'ar-contactus');
        }
        if ($this->isFacebookChatIntegrated()) {
            $integrations['facebook'] = __('Facebook customer chat', 'ar-contactus');
        }
        if ($this->isVkIntegrated()) {
            $integrations['vk'] = __('VK community messages', 'ar-contactus');
        }
        if ($this->isZopimIntegrated()) {
            $integrations['zopim'] = __('Zendesk chat', 'ar-contactus');
        }
        /*if ($this->isSkypeIntegrated()) {
            $integrations['skype'] = __('Skype web control', 'ar-contactus');
        }*/
        if ($this->isZaloIntegrated()) {
            $integrations['zalo'] = __('Zalo chat widget', 'ar-contactus');
        }
        if ($this->isLhcIntegrated()) {
            $integrations['lhc'] = __('Live helper chat', 'ar-contactus');
        }
        if ($this->isSmartsuppIntegrated()) {
            $integrations['smartsupp'] = __('Smartsupp', 'ar-contactus');
        }
        if ($this->isLiveChatIntegrated()) {
            $integrations['livechat'] = __('LiveChat', 'ar-contactus');
        }
        if ($this->isLiveChatProIntegrated()) {
            $integrations['livechatpro'] = __('LiveChatPro', 'ar-contactus');
        }
        if ($this->isLiveZillaIntegrated()) {
            $integrations['livezilla'] = __('LiveZilla', 'ar-contactus');
        }
        if ($this->isTidioIntegrated()) {
            $integrations['tidio'] = __('Tidio', 'ar-contactus');
        }
        if ($this->isJivositeIntegrated()) {
            $integrations['jivosite'] = __('Jivosite', 'ar-contactus');
        }
        if ($this->isZohoIntegrated()) {
            $integrations['zoho'] = __('Zoho SalesIQ', 'ar-contactus');
        }
        if ($this->isFreshChatIntegrated()) {
            $integrations['fc'] = __('FreshChat', 'ar-contactus');
        }
        if ($this->isPhpLiveIntegrated()) {
            $integrations['phplive'] = __('PhpLive', 'ar-contactus');
        }
        if ($this->isPaldeskIntegrated()) {
            $integrations['paldesk'] = __('Paldesk', 'ar-contactus');
        }
        
        return $integrations;
    }
    
    public function isPhpLiveIntegrated()
    {
        return $this->phplive_on && $this->phplive_src;
    }
    
    public function isPaldeskIntegrated()
    {
        return $this->paldesk_on && $this->paldesk_key;
    }
    
    public function isFreshChatIntegrated()
    {
        return $this->fc_on && $this->fc_token && $this->fc_host;
    }
    
    public function isZohoIntegrated()
    {
        return $this->zoho_on && $this->zoho_id && $this->zoho_host;
    }
    
    public function isJivositeIntegrated()
    {
        return $this->jivosite_on && $this->jivosite_id;
    }
    
    public function isTidioIntegrated()
    {
        return $this->tidio_on && $this->tidio_key;
    }
    
    public function isLiveZillaIntegrated()
    {
        return $this->lz_on && $this->lz_id;
    }
    
    public function isLiveChatProIntegrated()
    {
        return $this->lcp_on && $this->lcp_uri;
    }
    
    public function isLiveChatIntegrated()
    {
        return $this->lc_on && $this->lc_key;
    }
    
    public function isSmartsuppIntegrated()
    {
        return $this->ss_on && $this->ss_key;
    }
    
    public function isLhcIntegrated()
    {
        return $this->lhc_on && $this->lhc_uri;
    }
    
    public function isFacebookChatIntegrated()
    {
        return $this->fb_on && $this->fb_page_id;
    }
    
    public function isTawkToIntegrated()
    {
        return $this->tawk_to_on && $this->tawk_to_site_id && $this->tawk_to_widget;
    }
    
    public function isCrispIntegrated()
    {
        return $this->crisp_on && $this->crisp_site_id;
    }
    
    public function isIntercomIntegrated()
    {
        return $this->intercom_on && $this->intercom_app_id;
    }
    
    public function isVkIntegrated()
    {
        return $this->vk_on && $this->vk_page_id;
    }
    
    public function isZopimIntegrated()
    {
        return $this->zopim_on && $this->zopim_id;
    }
    
    public function isZendeskChat()
    {
        return strpos($this->zopim_id, '-') !== false;
    }
    
    /*public function isSkypeIntegrated()
    {
        return $this->skype_on && $this->skype_id;
    }*/
    
    public function isZaloIntegrated()
    {
        return $this->zalo_on && $this->zalo_id;
    }
    
    public function getFormTitle()
    {
        return __('Live chat integrations', 'ar-contactus');
    }
    
    public function attributeDefaults()
    {
        return array(
            'tawk_to_widget' => 'default',
            'zalo_height' => '420',
            'zalo_width' => '350',
            'lhc_width' => '300',
            'lhc_height' => '190',
            'lhc_popup_height' => '520',
            'lhc_popup_width' => '500',
            'zoho_host' => 'https://salesiq.zoho.eu',
            'fc_host' => 'https://wchat.freshchat.com'
        );
    }
    
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array(
                array(
                    'zalo_height',
                    'zalo_width',
                ), 'isInt', 'on' => $this->zalo_on
            ),
            array(
                array(
                    'lhc_uri'
                ), 'validateRequired', 'on' => $this->lhc_on, 'message' => __('Field "{label}" is required', 'ar-contactus')
            ),
            array(
                array(
                    'zalo_id'
                ), 'validateRequired', 'on' => $this->zalo_on, 'message' => __('Field "{label}" is required', 'ar-contactus')
            ),
            array(
                array(
                    'ss_key'
                ), 'validateRequired', 'on' => $this->ss_on, 'message' => __('Field "{label}" is required', 'ar-contactus')
            ),
            array(
                array(
                    'lc_key'
                ), 'validateRequired', 'on' => $this->lc_on, 'message' => __('Field "{label}" is required', 'ar-contactus')
            ),
            array(
                array(
                    'lz_id'
                ), 'validateRequired', 'on' => $this->lz_on, 'message' => __('Field "{label}" is required', 'ar-contactus')
            ),
            array(
                array(
                    'lhc_width',
                    'lhc_height',
                    'lhc_popup_height',
                    'lhc_popup_width',
                ), 'isInt', 'on' => $this->lhc_on
            ),
            array(
                array(
                    'lcp_uri',
                ), 'validateRequired', 'on' => $this->lcp_on, 'message' => __('Field "{label}" is required', 'ar-contactus')
            ),
            array(
                array(
                    'tidio_key',
                ), 'validateRequired', 'on' => $this->tidio_on, 'message' => __('Field "{label}" is required', 'ar-contactus')
            ),
            array(
                array(
                    'jivosite_id',
                ), 'validateRequired', 'on' => $this->jivosite_on, 'message' => __('Field "{label}" is required', 'ar-contactus')
            ),
            array(
                array(
                    'phplive_src',
                ), 'validateRequired', 'on' => $this->phplive_on, 'message' => __('Field "{label}" is required', 'ar-contactus')
            ),
            array(
                array(
                    'paldesk_key',
                ), 'validateRequired', 'on' => $this->paldesk_on, 'message' => __('Field "{label}" is required', 'ar-contactus')
            )
        ));
    }
    
    public function multiLangFields()
    {
        return array(
            'fb_lang' => true
        );
    }
}
