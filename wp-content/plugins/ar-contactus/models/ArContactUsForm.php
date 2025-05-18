<?php
ArContactUsLoader::loadModel('ArContactUsFormEntity');
ArContactUsLoader::loadModel('ArContactUsFormButton');
ArContactUsLoader::loadModel('ArContactUsFormField');

class ArContactUsForm extends ArContactUsFormEntity
{
    public $id;
    public $header;
    public $subheader;
    public $layout;
    
    public $icon_type;
    public $icon_img;
    public $icon_img_preview;
    public $icon_svg;
    public $icon_fa5;
    
    public $button_icon_type;
    public $button_icon_img;
    public $button_icon_img_preview;
    public $button_icon_svg;
    public $button_icon_fa5;
    
    public $successContent;
    public $failContent;
    public $desktopWidth = 300;
    public $autoClose = 0;
    
    public $email_on;
    public $email_list;
    
    public $twilio_on;
    public $twilio_phone;
    public $twilio_message;
    
    public $tg_on;
    public $tg_chat_id;
    public $tg_message;
    
    public $onesignal_on;
    public $onesignal_title;
    public $onesignal_message;
    
    public $perfex_on;
    public $perfex_source;
    public $perfex_status;
    public $perfex_name;
    public $perfex_email;
    public $perfex_phonenumber;
    public $perfex_contact;
    public $perfex_title;
    public $perfex_website;
    public $perfex_company;
    public $perfex_address;
    public $perfex_city;
    public $perfex_state;
    public $perfex_country;
    public $perfex_default_language;
    public $perfex_description;
    
    public $webhook_on;
    public $webhook_url;
    
    public $fields = array();
    
    public $buttons = array();
    
    private $layouts = array(
        1 => 'none',
        2 => 'text',
        3 => 'icon-left',
        4 => 'icon-center'
    );

    private $unremovableFields = array();
    
    protected $fieldErrors = array();

    public function rules()
    {
        $popupConfig = new ArContactUsConfigPopup('arcup_');
        $popupConfig->loadFromConfig();
        
        return array(
            array(
                array(
                    'id'
                ), 'required'
            ),
            array(
                array(
                    'id'
                ), 'isId', 'message' => __('ID should contains only letters, numbers and symbols "_"', 'ar-contactus')
            ),
            array(
                array(
                    'header'
                ), 'required', 'on' => $this->layout != 1
            ),
            array(
                array(
                    'icon_type'
                ), 'required', 'on' => in_array($this->layout, array(3, 4))
            ),
            array(
                array(
                    'icon_svg'
                ), 'required', 'on' => $this->icon_type == 'svg' && $this->layout != 1
            ),
            array(
                array(
                    'icon_img'
                ), 'required', 'on' => $this->icon_type == 'image' && $this->layout != 1
            ),
            array(
                array(
                    'icon_fa5'
                ), 'required', 'on' => $this->icon_type == 'fa5' && $this->layout != 1
            ),
            array(
                array(
                    'button_icon_svg'
                ), 'required', 'on' => $this->button_icon_type == 'svg'
            ),
            array(
                array(
                    'button_icon_img'
                ), 'required', 'on' => $this->button_icon_type == 'image'
            ),
            array(
                array(
                    'button_icon_fa5'
                ), 'required', 'on' => $this->button_icon_type == 'fa5'
            ),
            array(
                array(
                    'successContent',
                    'failContent',
                    'desktopWidth',
                ), 'required'
            ),
            array(
                array(
                    'desktopWidth',
                    'autoClose'
                ), 'isInt'
            ),
            array(
                array(
                    'email_list',
                ), 'emailList', 'on' => $this->email_on
            ),
            array(
                array(
                    'twilio_phone',
                    'twilio_message'
                ), 'required', 'on' => $this->twilio_on && $popupConfig->twilio
            ),
            array(
                array(
                    'tg_chat_id',
                    'tg_message'
                ), 'required', 'on' => $this->tg_on && $popupConfig->tg
            ),
            array(
                array(
                    'onesignal_title',
                    'onesignal_message'
                ), 'required', 'on' => $this->onesignal_on && $popupConfig->onesignal
            ),
            array(
                array(
                    'perfex_source',
                    'perfex_status',
                    'perfex_name'
                ), 'required', 'on' => $this->perfex_on && $popupConfig->perfex
            ),
            array(
                array(
                    'header',
                    'subheader',
                    'layout',
                    'icon_type',
                    'icon_img',
                    'icon_svg',
                    'icon_fa5',
                    
                    'button_icon_type',
                    'button_icon_img',
                    'button_icon_svg',
                    'button_icon_fa5',
                    
                    'successContent',
                    'failContent',
                    'desktopWidth',
                    'autoClose',

                    'email_on',
                    'email_list',

                    'twilio_on',
                    'twilio_phone',
                    'twilio_message',

                    'tg_on',
                    'tg_chat_id',
                    'tg_message',

                    'onesignal_on',
                    'onesignal_title',
                    'onesignal_message',

                    'perfex_on',
                    'perfex_source',
                    'perfex_status',
                    'perfex_name',
                    'perfex_email',
                    'perfex_phonenumber',
                    'perfex_contact',
                    'perfex_title',
                    'perfex_website',
                    'perfex_company',
                    'perfex_address',
                    'perfex_city',
                    'perfex_state',
                    'perfex_country',
                    'perfex_default_language',
                    'perfex_description',
                    
                    'webhook_on',
                    'webhook_url',
                    
                    'fields',

                    'buttons'
                ), 'safe'
            )
        );
    }
    
    public static function perfexAssignmentFields()
    {
        return array(
            'perfex_name' => __('Name', 'ar-contactus'),
            'perfex_email' => __('Email', 'ar-contactus'),
            'perfex_phonenumber' => __('Phonenumber', 'ar-contactus'),
            'perfex_contact' => __('Contact', 'ar-contactus'),
            'perfex_title' => __('Title', 'ar-contactus'),
            'perfex_website' => __('Website', 'ar-contactus'),
            'perfex_company' => __('Company', 'ar-contactus'),
            'perfex_address' => __('Address', 'ar-contactus'),
            'perfex_city' => __('City', 'ar-contactus'),
            'perfex_state' => __('State', 'ar-contactus'),
            'perfex_country' => __('Country', 'ar-contactus'),
            'perfex_description' => __('Description', 'ar-contactus'),
        );
    }
    
    public function __construct($id, $formData)
    {
        $this->id = $id;
        
        foreach($formData as $key => $value) {
            if ($key == 'fields'){
                foreach ($formData['fields'] as $fieldId => $fieldData) {
                    $this->fields[$fieldId] = new ArContactUsFormField($fieldId, $fieldData);
                }
            } elseif ($key == 'buttons'){
                foreach ($formData['buttons'] as $buttonId => $buttonData) {
                    $this->buttons[$buttonId] = new ArContactUsFormButton($buttonId, $buttonData);
                }
            } else {
                if ($this->isAttributeSafe($key)) {
                    if ($this->isLangAttribute($key)) {
                        if (ArContactUsTools::isMultilang()) {
                            if (is_object($value) || is_array($value)) {
                                $this->$key = $value;
                            } else {
                                $values = array();
                                foreach (ArContactUsTools::getLanguages() as $lang){
                                    $values[$lang['language_code']] = $value;
                                }
                                $this->$key = $values;
                            }
                        } else {
                            $currentLang = ArContactUsTools::getCurrentLanguage();
                            if (is_object($value) || is_array($value)) {
                                $this->$key = $value;
                            } else {
                                $this->$key = array(
                                    $currentLang => $value
                                );
                            }
                        }
                    } else {
                        $this->$key = $value;
                    }
                }
            }
        }
        $this->icon_fa5 = stripslashes($this->icon_fa5);
        $this->button_icon_fa5 = stripslashes($this->button_icon_fa5);
        if ($this->icon_img) {
            $this->icon_img_preview = wp_get_attachment_image($this->icon_img, 'full', false);
        }
        if ($this->button_icon_img) {
            $this->button_icon_img_preview = wp_get_attachment_image($this->button_icon_img, 'full', false);
        }
    }
    
    public function isUnremovableField($id)
    {
        return in_array($id, $this->unremovableFields);
    }
    
    public function removeField($id)
    {
        if (isset($this->fields[$id]) && !$this->isUnremovableField($id)) {
            unset($this->fields[$id]);
        }
    }
    
    public function setUnremovableFields($fields)
    {
        $this->unremovableFields = $fields;
    }
    
    public function getUnremovableFields()
    {
        return $this->unremovableFields;
    }
    
    public function getLayout()
    {
        return $this->layouts[$this->layout];
    }
    
    public function getButtonIcon()
    {
        switch ($this->button_icon_type) {
            case 'svg':
                return ArContactUsConfigModel::getIcon($this->button_icon_svg);
            case 'fa5':
                return $this->button_icon_fa5;
            case 'image':
                return $this->button_icon_img_preview;
        }
        return null;
    }
    
    public function getIcon()
    {
        switch ($this->icon_type) {
            case 'svg':
                return ArContactUsConfigModel::getIcon($this->icon_svg);
            case 'fa5':
                return $this->icon_fa5;
            case 'image':
                return $this->icon_img_preview;
        }
    }
    
    public function validate($data)
    {
        $this->fieldErrors = array();
        foreach ($this->fields as $field) {
            $value = isset($data[$field->id])? $data[$field->id] : null;
            if ($field->validate($value)) {
                
            } else {
                $this->fieldErrors[$field->id] = $field->getErrors();
            }
        }
        return empty($this->fieldErrors);
    }
    
    public function getErrors()
    {
        return $this->fieldErrors;
    }
    
    public function langAttributes()
    {
        return array(
            'header' => true,
            'successContent' => true,
            'failContent' => true,
            'twilio_message' => true,
            'tg_message' => true,
            'onesignal_title' => true,
            'onesignal_message' => true
        );
    }
    
    /**
     * 
     * @param string $id
     * @return ArContactUsFormField
     */
    public function getField($id)
    {
        return isset($this->fields[$id])? $this->fields[$id] : null;
    }
    
    /**
     * 
     * @param string $id
     * @return ArContactUsFormButton
     */
    public function getButton($id)
    {
        return isset($this->buttons[$id])? $this->buttons[$id] : null;
    }
}
