<?php

ArContactUsLoader::loadModel('ArContactUsModel');
ArContactUsLoader::loadModel('ArContactUsPromptModel');

ArContactUsLoader::loadClass('ArContactUsTools');


class ArContactUsMigrate
{
    public $error;
    
    public function migrate($checkPost = true)
    {
        if ($checkPost && (!isset($_POST['confirm']) || empty($_POST['confirm']))){
            $this->error = __('You must agree settings replacement alert', 'ar-contactus');
            return false;
        }
        
        $models = array(
            'ArContactUsConfigButton' => 'arcub_',
            'ArContactUsConfigGeneral' => 'arcug_',
            'ArContactUsConfigLiveChat' => 'arcul_',
            'ArContactUsConfigMenu' => 'arcum_',
            'ArContactUsConfigMobileButton' => 'arcumb_',
            'ArContactUsConfigMobileMenu' => 'arcumm_',
            'ArContactUsConfigMobilePrompt' => 'arcumpr_',
            'ArContactUsConfigPopup' => 'arcup_',
            'ArContactUsConfigPrompt' => 'arcupr_',
        );
        
        $errors = array();
        $success = true;
        
        foreach($models as $className => $configKey) {
            ArContactUsLoader::loadModel($className);
            $model = new $className($configKey);
            $model->loadFromConfig(false);
            if (!$model->saveToConfig(false, array(), true)){
                $errors[$className] = $model->getErrors();
                $success = false;
            }
        }
        
        if (!$success) {
            $this->error = implode(', ', $errors);
        }
        
        ArContactUsModel::createLangTable();
        
        $models = ArContactUsModel::find()->all();
        
        $isWPML = ArContactUsTools::isWPML();
        $langs = ArContactUsTools::getLanguages();
        $defaultLang = ArContactUsTools::getDefaultLanguage();
        
        foreach ($models as $model) {
            if ($isWPML) {
                foreach ($langs as $lang) {
                    $values = array(
                        "'{$lang['language_code']}'",
                        $model->id,
                        "'{$model->title}'",
                        "'{$model->subtitle}'",
                        "'{$model->content}'"
                    );
                    $sql = "INSERT INTO `" . ArContactUsModel::tableName() . "_lang` (`lang`, `id_item`, `title`, `subtitle`, `content`) VALUES (" . implode(',', $values) .")";
                    ArContactUsModel::getDb()->query($sql);
                }
            }else{
                $values = array(
                    "'{$defaultLang}'",
                    $model->id,
                    "'{$model->title}'",
                    "'{$model->subtitle}'",
                    "'{$model->content}'"
                );
                $sql = "INSERT INTO `" . ArContactUsModel::tableName() . "_lang` (`lang`, `id_item`, `title`, `subtitle`, `content`) VALUES (" . implode(',', $values) .")";
                ArContactUsModel::getDb()->query($sql);
            }
        }
        
        ArContactUsPromptModel::createLangTable();
        
        $models = ArContactUsPromptModel::find()->all();
        foreach ($models as $model) {
            if ($isWPML) {
                foreach ($langs as $lang) {
                    $values = array(
                        "'{$lang['language_code']}'",
                        $model->id,
                        "'{$model->message}'"
                    );
                    $sql = "INSERT INTO `" . ArContactUsPromptModel::tableName() . "_lang` (`lang`, `id_item`, `message`) VALUES (" . implode(',', $values) .")";
                    ArContactUsPromptModel::getDb()->query($sql);
                }
            }else{
                $values = array(
                    "'{$defaultLang}'",
                    $model->id,
                    "'{$model->message}'"
                );
                $sql = "INSERT INTO `" . ArContactUsModel::tableName() . "_lang` (`lang`, `id_item`, `message`) VALUES (" . implode(',', $values) .")";
                ArContactUsPromptModel::getDb()->query($sql);
            }
        }
        
        return $success;
    }
    
    public function getError()
    {
        return $this->error;
    }
}
