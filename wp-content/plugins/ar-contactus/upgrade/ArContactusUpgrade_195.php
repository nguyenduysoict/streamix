<?php
ArContactUsLoader::loadClass('ArContactusUpgradeAbstract');
ArContactUsLoader::loadModel('ArContactUsModel');
ArContactUsLoader::loadModel('ArContactUsPromptModel');

class ArContactusUpgrade_195  extends ArContactusUpgradeAbstract
{
    public function upgrade()
    {
        $this->refactorSettings();
        $this->createTables();
        $this->createMenuItemsLangData();
        $this->createPromptItemsLangData();
        
        return true;
    }
    
    public function createPromptItemsLangData()
    {
        $isWPML = self::isWPML();
        $langs = self::getLanguages();
        $defaultLang = self::getDefaultLanguage();
        
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
    }
    
    public function createMenuItemsLangData()
    {
        $models = ArContactUsModel::find()->all();
        
        $isWPML = self::isWPML();
        $langs = self::getLanguages();
        $defaultLang = self::getDefaultLanguage();
        
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
    }
    
    public function createTables()
    {
        ArContactUsModel::getDb()->query("CREATE TABLE IF NOT EXISTS `" . ArContactUsModel::tableName() . "_lang` (
            `lang` VARCHAR(10) NOT NULL,
            `id_item` INT(10) UNSIGNED NOT NULL,
            `title` VARCHAR(255) NULL DEFAULT NULL,
            `subtitle` VARCHAR(255) NULL DEFAULT NULL,
            `content` TEXT(65535) NULL DEFAULT NULL,
            PRIMARY KEY (`lang`, `id_item`)
        )
        COLLATE='utf8_general_ci'");
        
        ArContactUsPromptModel::getDb()->query("CREATE TABLE IF NOT EXISTS `" . ArContactUsPromptModel::tableName() . "_lang` (
            `lang` VARCHAR(10) NOT NULL,
            `id_item` INT(10) UNSIGNED NOT NULL,
            `message` TEXT(65535) NULL DEFAULT NULL,
            PRIMARY KEY (`lang`, `id_item`)
        )
        COLLATE='utf8_general_ci'");
    }
    
    public function refactorSettings()
    {
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
            $data = array();
            $jsonKey = str_replace('_', '', $configKey);
            
            foreach ($model->getAttributes() as $attr => $value) {
                $a = $model->getConfigAttribueName($attr, false);
                if ($model->getMultipleSelect($attr)) {
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                }
                if ($a == 'ARCUP_GDPR_TITLE') {
                    $value = stripslashes($value);
                }
                $data[$a] = $value;
            }
            
            update_option($jsonKey, json_encode($data));
        }
    }
    
    public static function isWPML()
    {
        return is_plugin_active('sitepress-multilingual-cms/sitepress.php');
    }
    
    public static function getLanguages()
    {
        return apply_filters('wpml_active_languages', null, 'orderby=id&order=desc');
    }
    
    public static function getDefaultLanguage()
    {
        if (self::isWPML()) {
            return apply_filters('wpml_default_language', null);
        } else {
            $locale = get_locale();
            $lang = null;
            if (strpos($locale, '_') !== false) {
                $loc = explode('_', $locale);
                $lang = $loc[0];
            }
            return strtolower($lang);
        }
    }
    
    public static function getCurrentLanguage()
    {
        if (self::isWPML()) {
            return apply_filters('wpml_current_language', null);
        } else {
            $locale = get_locale();
            $lang = null;
            if (strpos($locale, '_') !== false) {
                $loc = explode('_', $locale);
                $lang = $loc[0];
            }
            return strtolower($lang);
        }
    }
    
    public function getVersion()
    {
        return '1.9.5';
    }
}
