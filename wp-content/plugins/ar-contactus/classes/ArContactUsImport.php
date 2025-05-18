<?php

/**
 * @property ArContactUsAdmin $owner
 */
class ArContactUsImport
{
    public $owner;
    public $error;
    
    public function __construct($owner)
    {
        $this->owner = $owner;
    }
    
    public function import()
    {
        if (!isset($_POST['confirm']) || empty($_POST['confirm'])){
            $this->error = __('You must agree data loss alert', 'ar-contactus');
            return false;
        }
        if (empty($_FILES) || !isset($_FILES['import'])){
            $this->error = __('Import error! No file selected.', 'ar-contactus');
            return false;
        }
        $file = $_FILES['import'];
        if ($file['size'] == 0){
            $this->error = __('Import error! No file selected.', 'ar-contactus');
            return false;
        }
        if ($file['type'] != 'application/json'){
            $this->error = __('Import error! Wrong file type.', 'ar-contactus');
            return false;
        }
        $filename = uniqid('import_') . '.json';
        if (!move_uploaded_file($file['tmp_name'], AR_CONTACTUS_PLUGIN_DIR . 'uploads/' . $filename)){
            $this->error = __('Import error! Error uploading file!', 'ar-contactus');
            return false;
        }
        $data = file_get_contents(AR_CONTACTUS_PLUGIN_DIR . 'uploads/' . $filename);
        $json = json_decode($data, true);
        if (empty($json)){
            $this->error = __('Import error! File is empty or read error!', 'ar-contactus');
            return false;
        }
        if (!isset($json['general']) && !isset($json['button']) && !isset($json['menu']) && !isset($json['popup']) && !isset($json['prompt']) && !isset($json['menuItems']) && !isset($json['callbackItems']) && !isset($json['promptItems'])){
            $this->error = __('Import error! File is empty or wrong format!', 'ar-contactus');
            return false;
        }
        if (!isset($json['mobileButton'])){
            $json->mobileButton = $json['button'];
        }
        if (!isset($json['mobileMenu'])){
            $json->mobileMenu = $json['menu'];
        }
        
        if (!isset($json['mobilePrompt'])){
            $json->mobilePrompt = $json['prompt'];
        }
        
        foreach ($json as $k => $data){
            $methodName = 'importData' . ucfirst($k);
            if (method_exists($this, $methodName) && $data){
                $this->{$methodName}($data, $json);
            }
        }
        if (!isset($json['forms'])) {
            $this->importDataForms(array(), $json);
        }
        $this->owner->compileCSS();
        $this->owner->compileCSS(true);
        return true;
    }
    
    public function getError()
    {
        return $this->error;
    }
    
    public function importDataGeneral($data, $json)
    {
        ArContactUsLoader::loadModel('ArContactUsConfigGeneral');
        $model = new ArContactUsConfigGeneral('arcug_');
        $this->importDataConfigModel($model, $data);
    }
    
    public function importDataButton($data, $json)
    {
        ArContactUsLoader::loadModel('ArContactUsConfigButton');
        $model = new ArContactUsConfigButton('arcub_');
        $this->importDataConfigModel($model, $data);
    }
    
    public function importDataMobileButton($data, $json)
    {
        if (ArContactUsLoader::isModelExists('ArContactUsConfigMobileButton')){
            ArContactUsLoader::loadModel('ArContactUsConfigMobileButton');
            $model = new ArContactUsConfigMobileButton('arcumb_');
            $this->importDataConfigModel($model, $data);
        }
    }
    
    public function importDataMenu($data, $json)
    {
        ArContactUsLoader::loadModel('ArContactUsConfigMenu');
        $model = new ArContactUsConfigMenu('arcum_');
        $this->importDataConfigModel($model, $data);
    }
    
    public function importDataMobileMenu($data, $json)
    {
        if (ArContactUsLoader::isModelExists('ArContactUsConfigMobileMenu')){
            ArContactUsLoader::loadModel('ArContactUsConfigMobileMenu');
            $model = new ArContactUsConfigMobileMenu('arcumm_');
            $this->importDataConfigModel($model, $data);
        }
    }
    
    public function importDataPopup($data, $json)
    {
        ArContactUsLoader::loadModel('ArContactUsConfigPopup');
        $model = new ArContactUsConfigPopup('arcup_');
        $this->importDataConfigModel($model, $data);
    }
    
    public function importDataPrompt($data, $json)
    {
        ArContactUsLoader::loadModel('ArContactUsConfigPrompt');
        $model = new ArContactUsConfigPrompt('arcupr_');
        $this->importDataConfigModel($model, $data);
    }
    
    public function importDataMobilePrompt($data, $json)
    {
        if (ArContactUsLoader::isModelExists('ArContactUsConfigMobilePrompt')){
            ArContactUsLoader::loadModel('ArContactUsConfigMobilePrompt');
            $model = new ArContactUsConfigMobilePrompt('arcumpr_');
            $this->importDataConfigModel($model, $data);
        }
    }
    
    public function importDataWelcome($data, $json)
    {
        if (ArContactUsLoader::isModelExists('ArContactUsConfigWelcome')){
            ArContactUsLoader::loadModel('ArContactUsConfigWelcome');
            $model = new ArContactUsConfigWelcome('arcuw_');
            $this->importDataConfigModel($model, $data);
        }
    }
    
    public function importDataEmails($data, $json)
    {
        if (ArContactUsLoader::isModelExists('ArContactUsConfigEmails')){
            ArContactUsLoader::loadModel('ArContactUsConfigEmails');
            $model = new ArContactUsConfigEmails('arcue_');
            $this->importDataConfigModel($model, $data);
        }
    }
    
    public function importDataForms($data, $json)
    {
        if ($data) {
            update_option('arcuforms', json_encode($data));
        } else {
            if (ArContactUsLoader::isModelExists('ArContactUsConfigForms')){
                ArContactUsLoader::loadModel('ArContactUsConfigForms');
                $model = new ArContactUsConfigForms();
                $popupConfigData = array();
                if (isset($json['popup'])) {
                    foreach ($json['popup'] as $key => $value) {
                        $popupConfigData['ARCUP_' . strtoupper($key)] = $value;
                    }
                }
                $model->buildDefaultForms($popupConfigData);
            }
        }
    }
    
    public function importDataIntegrations($data)
    {
        ArContactUsLoader::loadModel('ArContactUsConfigLiveChat');
        $model = new ArContactUsConfigLiveChat('arcul_');
        $this->importDataConfigModel($model, $data);
    }
    
    public function importDataMenuItems($data)
    {
        ArContactUsLoader::loadModel('ArContactUsModel');
        ArContactUsModel::truncate();
        ArContactUsModel::truncateLangTable();
        foreach ($data as $item) {
            $model = new ArContactUsModel();
            foreach ($item as $attribute => $value){
                if ($attribute != 'id' && property_exists($model, $attribute)){
                    $model->$attribute = $value;
                    if ($attribute == 'type' && $value == ArContactUsModel::TYPE_FORM) {
                        
                    }
                }
            }
            $params = json_decode($model->params, true);
            if ($model->type == ArContactUsModel::TYPE_FORM && !isset($params['form'])) {
                $params['form'] = 'callback';
            }
            $model->params = json_encode($params);
            $model->registered_only = (int)$model->registered_only;
            $model->save();
        }
    }
    
    public function importDataPromptItems($data)
    {
        ArContactUsLoader::loadModel('ArContactUsPromptModel');
        ArContactUsPromptModel::truncate();
        ArContactUsPromptModel::truncateLangTable();
        foreach ($data as $item) {
            $model = new ArContactUsPromptModel();
            foreach ($item as $attribute => $value){
                if ($attribute != 'id' && property_exists($model, $attribute)){
                    $model->$attribute = $value;
                }
            }
            $model->save();
        }
    }
    
    public function importDataCallbackItems($data)
    {
        ArContactUsLoader::loadModel('ArContactUsCallbackModel');
        ArContactUsCallbackModel::truncate();
        foreach ($data as $item) {
            $model = new ArContactUsCallbackModel();
            foreach ($item as $attribute => $value){
                if ($attribute != 'id' && property_exists($model, $attribute)){
                    $model->$attribute = $value;
                }
            }
            $model->save();
        }
    }
    
    protected function importDataConfigModel($model, $data)
    {
        $model->clearConfig();
        $model->loadDefaults();
        $model->setAttributes($data);
        $model->saveToConfig(false);
    }
}
