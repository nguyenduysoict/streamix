<?php
ArContactUsLoader::loadController('ArContractUsControllerAbstract');
ArContactUsLoader::loadModel('ArContactUsModel');
ArContactUsLoader::loadClass('ArContactUsTelegram');
ArContactUsLoader::loadClass('ArContactUsOnesignal');
ArContactUsLoader::loadClass('ArContactUsPerfex');
ArContactUsLoader::loadClass('ArContactUsWebhook');
        
class ArContactUsRequestsController extends ArContractUsControllerAbstract
{
    protected $errors = array();
    protected $popupConfig = null;
    protected $formsConfig = null;
    protected $json;

    protected function ajaxActions()
    {
        return array(
            'arcontactus_request_callback' => 'requestCallback',
            'arcontactus_request_email' => 'requestEmail',
            'arcontactus_callback_count' => 'callbackCount',
            'arcontactus_switch_callback' => 'switchCallback',
            'arcontactus_reload_callback' => 'reload',
            'arcontactus_export_callback' => 'export',
            'arcontactus_edit_comment' => 'editComment',
            'arcontactus_save_comment' => 'saveComment',
        );
    }
    
    protected function ajaxNoprivActions()
    {
        return array(
            'arcontactus_request_callback' => 'requestCallback',
            'arcontactus_request_email' => 'requestEmail'
        );
    }
    
    public function setMailContentType()
    {
        return "text/html";
    }
    
    public function saveComment()
    {
        $this->assertAccess($this->getCapability());
        
        $id = $_POST['id'];
        $comment = $_POST['comment'];
        $model = ArContactUsCallbackModel::findOne($id);
        $model->comment = $comment;
        $model->save();
        wp_die($this->returnJson(array(
            'success' => 1,
            'model' => $model,
            'content' => ArContactUsAdmin::render('admin/partials/comment.php', array(
                'item' => array(
                    'comment' => $model->comment,
                    'id' => $model->id
                )
            ))
        )));
    }
    
    public function editComment()
    {
        $this->assertAccess($this->getCapability());
        
        $id = $_GET['id'];
        $model = ArContactUsCallbackModel::findOne($id);
        $model->params = json_decode($model->params, true);
        $form = null;
        if (isset($model->params['formId'])) {
            $formConfig = new ArContactUsConfigForms();
            $form = $formConfig->getForm($model->params['formId']);
        }
        wp_die($this->returnJson(array(
            'success' => 1,
            'model' => $model,
            'header' => ArContactUsAdmin::render('admin/partials/request-item-header.php', array(
                'model' => $model,
                'form' => $form,
                'currentLang' => ArContactUsTools::getCurrentLanguage()
            ))
        )));
    }
    
    /**
     * 
     * @return ArContactUsConfigForms
     */
    public function getFormsConfig()
    {
        if (!$this->formsConfig) {
            $this->formsConfig = new ArContactUsConfigForms();
        }
        return $this->formsConfig;
    }
    
    public function processRequest($form)
    {
        $this->popupConfig = new ArContactUsConfigPopup('arcup_');
        $this->popupConfig->loadFromConfig();
        
        
        $data = $_POST;
        
        $reCaptchaValid = $this->isValidRecaptcha();
        
        if ($form->validate($data) && $reCaptchaValid) {
            $referer = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER'] : null;
            
            $email = $this->sendEmail($form, $data);
            $twilio = $this->sendTwilioSMS($form, $data);
            $tg = $this->sendTelegram($form, $data);
            $push = $this->sendPush($form, $data);
            $perfex = $this->sendPerfex($form, $data);
            $webhook = $this->sendWebhook($form, $data);
            ArContactUsLoader::loadModel('ArContactUsCallbackModel');
            $model = new ArContactUsCallbackModel();
            $model->created_at = date('Y-m-d H:i:s');
            // $model->phone = $phone;
            $model->referer = $referer;
            $model->status = ArContactUsCallbackModel::STATUS_NEW;
            $model->id_user = get_current_user_id();
            // $model->email = $mail;
            // $model->name = $name;
            $model->params = json_encode($data);
            $model->type = ArContactUsCallbackModel::getTypeByFormId($form->id);
            
            foreach ($form->fields as $field) {
                $fieldId = $field->id;
                if (property_exists($model, $fieldId) && $model->attributeCanBeAssigned($fieldId) && isset($data[$fieldId])) {
                    $model->$fieldId = $data[$fieldId];
                }
            }
            
            $model->save();
            wp_die($this->returnJson(array(
                'success' => 1,
                'email' => $email,
                'recaptcha' => AR_CONTACTUS_DEBUG? $this->json : null,
                'twilio' => AR_CONTACTUS_DEBUG? $twilio : null,
                'tg' => AR_CONTACTUS_DEBUG? $tg : null,
                'perfex' => AR_CONTACTUS_DEBUG? $perfex : null,
                'push' => $push,
                'webhook' => AR_CONTACTUS_DEBUG? $webhook : null
            )));
        }elseif (!$reCaptchaValid){
            wp_die($this->returnJson(array(
                'success' => 0,
                'errors' => $form->getErrors(),
                'message' => $this->popupConfig->recaptcha_error,
                'recaptcha' => AR_CONTACTUS_DEBUG? $this->json : null
            )));
        }else{
            wp_die($this->returnJson(array(
                'success' => 0,
                'errors' => $form->getErrors(),
                'recaptcha' => AR_CONTACTUS_DEBUG? $this->json : null
            )));
        }
    }
    
    public function requestEmail()
    {
        $form = $this->getFormsConfig()->getForm('email');
        $this->processRequest($form);
    }
    
    public function requestCallback()
    {
        $form = $this->getFormsConfig()->getForm('callback');
        $this->processRequest($form);
    }
    
    protected function getFormData($form, $data, $braces = true)
    {
        $res = array();
        foreach ($form->fields as $field) {
            if (isset($data[$field->id])) {
                if ($braces) {
                    $res['{' . $field->id . '}'] = $data[$field->id];
                } else {
                    $res[$field->id] = $data[$field->id];
                }
            }
        }
        if ($braces) {
            $res['{site}'] = parse_url(AR_CONTACTUS_PLUGIN_URL, PHP_URL_HOST);
            $res['{referer}'] = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER'] : null;
        } else {
            $res['site'] = parse_url(AR_CONTACTUS_PLUGIN_URL, PHP_URL_HOST);
            $res['referer'] = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER'] : null;
        }
        return $res;
    }
    
    protected function sendWebhook($form, $data)
    {
        if (!$form->webhook_on || empty($form->webhook_url)) {
            return null;
        }
        $api = new ArContactUsWebhook($form->webhook_url);
        return $api->sendData($form, $this->getFormData($form, $data, false));
    }

    protected function sendPerfex($form, $data)
    {
        if (!$this->popupConfig->perfex
                || !$this->popupConfig->perfex_url
                || !$this->popupConfig->perfex_token
                || !$form->perfex_on
                || !$form->perfex_status
                || !$form->perfex_source
                || !$form->perfex_name) {
            return false;
        }
        $api = new ArContactUsPerfex($this->popupConfig->perfex_url, $this->popupConfig->perfex_token);
        return $api->newLead($form, $data);
    }

    protected function sendPush($form, $data)
    {
        $currentLang = ArContactUsTools::getCurrentLanguage();
        
        if (!$this->popupConfig->onesignal 
                || !$this->popupConfig->onesignal_api_key 
                || !$this->popupConfig->onesignal_app_id 
                || !$form->onesignal_on 
                || !$form->getLangValue('onesignal_title', $currentLang)
                || !$form->getLangValue('onesignal_message', $currentLang)){
            return false;
        }
        $onesignal = new ArContactUsOnesignal($this->popupConfig);
        $osMessage = $form->getLangValue('onesignal_message', $currentLang);
        $osTitle = $form->getLangValue('onesignal_title', $currentLang);
        $message = is_null($osMessage) ? '' : strtr($osMessage, $this->getFormData($form, $data));
        $title = is_null($osTitle) ? '' : strtr($osTitle, $this->getFormData($form, $data));
        return $onesignal->sendMessage(array(
            'en' => $message
        ), array(
            'en' => $title
        ), '/');
    }


    protected function sendTelegram($form, $data)
    {
        $currentLang = ArContactUsTools::getCurrentLanguage();
        if (!$form->tg_on ||
                !$form->tg_chat_id || 
                !$this->popupConfig->tg_token || 
                !$form->getLangValue('tg_message', $currentLang) ||
                !$this->popupConfig->tg){
            return false;
        }
        $return = array();
        if (strpos($form->tg_chat_id, ',') !== false) {
            $chatIds = explode(',', $form->tg_chat_id);
            foreach ($chatIds as $chatId) {
                $return[] = $this->sendTelegramMessage($form, $data, $chatId);
            }
            return $return;
        }
        return $this->sendTelegramMessage($form, $data, $form->tg_chat_id);
    }
    
    protected function sendTelegramMessage($form, $data, $chatId)
    {
        $telegram = new ArContactUsTelegram($this->popupConfig->tg_token, trim($chatId));
        $currentLang = ArContactUsTools::getCurrentLanguage();
        $tgMessage = $form->getLangValue('tg_message', $currentLang);
        $message = is_null($tgMessage) ? '' : strtr($tgMessage, $this->getFormData($form, $data));
        return $telegram->send($message);
    }


    protected function sendTwilioSMS($form, $data)
    {
        $currentLang = ArContactUsTools::getCurrentLanguage();
        
        if (!$this->popupConfig->twilio ||
                !$this->popupConfig->twilio_api_key ||
                !$this->popupConfig->twilio_auth_token ||
                !$form->twilio_on ||
                !$form->getLangValue('twilio_message', $currentLang) ||
                !$form->twilio_phone ||
                !$this->popupConfig->twilio_phone
            ){
            return false;
        }
        $twilio = new ArContactUsTwilio($this->popupConfig->twilio_api_key, $this->popupConfig->twilio_auth_token);
        $fromPhone = $this->popupConfig->twilio_phone;
        $toPhone = $form->twilio_phone;
        $twMessage = $form->getLangValue('twilio_message', $currentLang);
        $message = is_null($twMessage) ? '' : strtr($twMessage, $this->getFormData($form, $data));
        
        $res = $twilio->sendSMS($message, $fromPhone, $toPhone);
        return $res;
    }


    public function isValidPhone($phone)
    {
        if (empty($phone)){
            $this->errors[] = __('Phone is incorrect!', 'ar-contactus');
            return false;
        }
        return true;
    }
    
    public function callbackCount()
    {
        $this->assertAccess($this->getCapability());
        
        wp_die($this->returnJson(array(
            'count' => ArContactUsCallbackModel::newCount(),
            'emailCount' => ArContactUsCallbackModel::newCount(ArContactUsCallbackModel::TYPE_EMAIL),
        )));
    }
    
    public function switchCallback()
    {
        $this->assertAccess($this->getCapability());
        
        $id = $_POST['id'];
        $status = $_POST['status'];
        $model = ArContactUsCallbackModel::findOne($id);
        $model->status = $status;
        $model->updated_at = date('Y-m-d H:i:s');
        $model->save();
        wp_die($this->returnJson(array(
            'success' => 1
        )));
    }
    
    public function export()
    {
        $this->assertAccess($this->getCapability());
        
        $type = (int)$_REQUEST['type'];
        
        $models = ArContactUsCallbackModel::find()->where(array('type' => $type))->all();
        
        $formID = ArContactUsCallbackModel::getFormIdByType($type);
        
        $formsConfig = new ArContactUsConfigForms();
        $form = $formsConfig->getForm($formID);
        $currentLang = ArContactUsTools::getCurrentLanguage();
        
        $csvHeaders = array(
            'ID'
        );
        
        foreach($form->fields as $field) {
            if ($field->report) {
                $csvHeaders[] = $field->getLangValue('report_label', $currentLang);
            }
        }
        
        $csvHeaders[] = 'Page';
        $csvHeaders[] = 'Request date';
        $csvHeaders[] = 'Status';
        $csvHeaders[] = 'Comment';
        
        $csvLines = array(implode(';', $csvHeaders));
        foreach ($models as $model) {
            switch ($model->status){
                case ArContactUsCallbackModel::STATUS_NEW:
                    $status = __('New', 'ar-contactus');
                    break;
                case ArContactUsCallbackModel::STATUS_DONE:
                    $status = __('Done', 'ar-contactus');
                    break;
                case ArContactUsCallbackModel::STATUS_IGNORE:
                    $status = __('Ignore', 'ar-contactus');
                    break;
            }
            $csvData = array(
                $model->id
            );
            
            $params = $model->getParams();
            
            foreach($form->fields as $field) {
                if ($field->report) {
                    $fieldId = $field->id;
                    if (isset($model->$fieldId)){
                        if ($fieldId == 'phone'){
                            $csvData[] = "=\"{$model->phone}\"";
                        } else {
                            $csvData[] = $model->$fieldId;
                        }
                    } else {
                        $csvData[] = isset($params[$fieldId])? $params[$fieldId] : '';
                    }
                    $csvHeaders[] = $field->getLangValue('report_label', $currentLang);
                }
            }
            
            //"=\"{$model->phone}\"",
            $csvData[] = $model->referer;
            $csvData[] = $model->created_at;
            $csvData[] = $status;
            $csvData[] = str_replace(PHP_EOL, ' ', $model->comment);
                
            $csvLines[] = implode(';', $csvData);
        }
        $content = implode(PHP_EOL, $csvLines);
        file_put_contents(AR_CONTACTUS_PLUGIN_DIR . '/uploads/export.csv', "\xEF\xBB\xBF" . $content);
        wp_die($this->returnJson(array(
            'success' => 1,
            'file' => AR_CONTACTUS_PLUGIN_URL . '/uploads/export.csv'
        )));
    }
    
    public function reload()
    {
        $this->assertAccess($this->getCapability());
        
        if (!isset($GLOBALS['hook_suffix'])){
            $GLOBALS['hook_suffix'] = null;
        }
        $list = new ArContactUsListTable();
        $emailList = new ArContactUsListTable();
        $emailList->setType(ArContactUsCallbackModel::TYPE_EMAIL);
        wp_die($this->returnJson(array(
            'success' => 1,
            'content' => self::render('/admin/_requests.php', array(
                'callbackList' => $list,
                'activeSubmit' => 'arcontactus-requests',
                'noSegment' => 1
            )),
            'emailContent' => self::render('/admin/_email_requests.php', array(
                'callbackList' => $emailList,
                'activeSubmit' => 'arcontactus-email-requests',
                'noSegment' => 1
            ))
        )));
    }
    
    protected function sendEmail($form, $data)
    {
        if ($form->email_on && $form->email_list) {
            $emailConfig = new ArContactUsConfigEmails('arcue_');
            $emailConfig->loadFromConfig();
            add_filter('wp_mail_content_type', array($this, 'setMailContentType'));
            $emails = explode(PHP_EOL, $form->email_list);
            $res = true;
            $headers = array();
            
            $replyTo = null;
            $replyToName = null;
            if (isset($data['email'])) {
                $replyTo = $data['email'];
            } elseif (isset($data['mail'])) {
                $replyTo = $data['mail'];
            } elseif (isset($data['from'])) {
                $replyTo = $data['from'];
            }
            
            if (isset($data['name'])) {
                $replyToName = $data['name'];
            }
            
            if (!empty($replyTo)) {
                if (!empty($replyToName)) {
                    $headers[] = 'Reply-To: ' . trim($replyToName) . ' <' . $replyTo . '>';
                } else {
                    $headers[] = 'Reply-To: <' . $replyTo . '>';
                }
            }
            
            foreach ($emails as $email){
                $res = wp_mail($email, $this->getEmailSubject($form, $data), self::render('mail/callback.php', array(
                    'content' => $this->getEmailBody($form, $data),
                    'emailsConfig' => $emailConfig,
                    'subject' => $this->getEmailSubject($form, $data),
                    'pluginUrl' => rtrim(plugin_dir_url( __DIR__ ), '/\\' )
                )), $headers) && $res;
            }
            return $res;
        }
        return null;
    }
    
    public function getEmailSubject($form, $data)
    {
        $currentLang = ArContactUsTools::getCurrentLanguage();
        $emailConfig = new ArContactUsConfigEmails('arcue_');
        $emailConfig->loadFromConfig();
        $subjectField = $form->id . '_email_subject';
        $emailSubject = $emailConfig->getLangValue($subjectField, $currentLang);
        return is_null($emailSubject) ? '' : strtr($emailSubject, $this->getFormData($form, $data));
    }
    
    public function getEmailBody($form, $data)
    {
        $currentLang = ArContactUsTools::getCurrentLanguage();
        $emailConfig = new ArContactUsConfigEmails('arcue_');
        $emailConfig->loadFromConfig();
        $bodyField = $form->id . '_email_body';
        $emailBody = $emailConfig->getLangValue($bodyField, $currentLang);
        $emailBody = is_null($emailBody) ? '' : strtr($emailBody, $this->getFormData($form, $data));
        return str_replace(array("\r\n"), '<br/>', $emailBody);
    }


    /**
     * Check is everything is ok
     * @return boolean
     */
    public function isValid()
    {
        if (!isset($_POST['action'])){
            return false;
        }
        
        $nonce = $_REQUEST['_wpnonce'];
        $nonceValid = true;
        if (!wp_verify_nonce($nonce, 'arcu_callback_form')) {
            $nonceValid = false;
            $this->errors[] = __('You dont have access to perform this action', 'ar-contactus');
        }
        
        if ($this->popupConfig->email_field) {
            $email = $this->filterEmail($_POST['email']);
            $emailValid = $this->isValidEmail($email);
            if (!$emailValid) {
                $this->errors[] = sprintf(__('Entered value "%s" is not a valid email address', 'ar-contactus'), $email);
            }
        } else {
            $emailValid = true;
        }
        
        if ($this->popupConfig->name) {
            $name = $this->filterName($_POST['name']);
            $nameValid = $this->isNameValid($name);
        } else {
            $nameValid = true;
        }
        $action = $_POST['action'];
        return $action == 'arcontactus_request_callback' && $nonceValid && $nameValid && $emailValid && $this->isValidRecaptcha();
    }
    
    public function filterEmail($email)
    {
        $email = trim($email);
        return strip_tags($email);
    }
    
    public function filterName($name)
    {
        return trim($name);
    }
    
    public function isNameValid($name)
    {
        if (!$this->popupConfig->name_validation) {
            return true;
        }
        if ($this->popupConfig->name_max_len) {
            $len = mb_strlen($name, 'utf-8');
            if ($len > $this->popupConfig->name_max_len) {
                $this->errors[] = sprintf(__('Entered value "%s" is longer then %s symbols', 'ar-contactus'), $name, $this->popupConfig->name_max_len);
                return false;
            }
        }
        if ($this->popupConfig->name_filter_laters) {
            if (!preg_match('/^[\p{Latin}\p{Cyrillic}\p{Armenian}\p{Hebrew}\p{Arabic}\p{Syriac}\p{Thaana}\p{Devanagari}\p{Bengali}\p{Gurmukhi}\p{Gujarati}\p{Oriya}\p{Tamil}\p{Telugu}\p{Kannada}\p{Malayalam}\p{Sinhala}\p{Thai}\p{Lao}\p{Tibetan}\p{Myanmar}\p{Georgian}\p{Ethiopic}\p{Cherokee}\p{Ogham}\p{Runic}\p{Tagalog}\p{Hanunoo}\p{Buhid}\p{Tagbanwa}\p{Khmer}\p{Mongolian}\p{Limbu}\p{Tai_Le}\p{Hiragana}\p{Katakana}\p{Bopomofo}\s0-9A-Za-zА-Яа-я]+$/iu', $name)) {
                $this->errors[] = sprintf(__('Entered value "%s" is not correct. Please use leters and numbers only', 'ar-contactus'), $name);
                return false;
            }
        }
        
        return true;
    }
    
    public function isValidEmail($email)
    {
        if ($this->popupConfig->email_required && empty($email)) {
            return false;
        } elseif (!$this->popupConfig->email_required && empty($email)) {
            return true;
        }
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
    
    /**
     * Check is recaptha token valid
     * @return boolean
     */
    public function isValidRecaptcha()
    {
        if (!$this->popupConfig->recaptcha){
            return true;
        }
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
                'content' => http_build_query(array(
                    'secret' => $this->popupConfig->secret,
                    'response' => $_POST['gtoken']
                ))
            ),
        ));
        $data = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $json = json_decode($data, true);
        $this->json = $json;
        if (isset($json['success']) && $json['success']) {
            if (isset($json['score']) && ($json['score'] < $this->popupConfig->recaptcha_treshold)) { // reCaptcha returns score from 0 to 1.
                $this->errors[] = $this->popupConfig->recaptcha_error;
                return false;
            }
        } else {
            $this->addReCaptchaErrors($json['error-codes']);
            return false;
        }
        return true;
    }

    /**
     * Humanize recaptha errors
     * @param type $errors
     */
    public function addReCaptchaErrors($errors)
    {
        $reCaptchaErrors = $this->getReCaptchaErrors();
        if ($errors) {
            foreach ($errors as $error) {
                if (isset($reCaptchaErrors[$error])) {
                    $this->errors[] = $reCaptchaErrors[$error];
                } else {
                    $this->errors[] = $error;
                }
            }
        }
    }

    /**
     * recaptha errors
     * @param type $errors
     */
    public function getReCaptchaErrors()
    {
        return array(
            'missing-input-secret' => __('The secret parameter is missing. Please check your reCaptcha Secret.', 'ar-contactus'),
            'invalid-input-secret' => __('The secret parameter is invalid or malformed. Please check your reCaptcha Secret.', 'ar-contactus'),
            'missing-input-response' => __('Bot activity detected! Empty captcha value.', 'ar-contactus'),
            'invalid-input-response' => __('Bot activity detected! Captcha value is invalid.', 'ar-contactus'),
            'bad-request' => __('The request is invalid or malformed.', 'ar-contactus'),
        );
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getCapability()
    {
        $generalConfig = new ArContactUsConfigGeneral('arcug_');
        $generalConfig->loadFromConfig();
        $user = wp_get_current_user();
        $roles = (array) $user->roles;
        $role = reset($roles);
        if (in_array($role, $generalConfig->callback_access)) {
            return $role;
        }
        return 'manage_options';
    }
}
