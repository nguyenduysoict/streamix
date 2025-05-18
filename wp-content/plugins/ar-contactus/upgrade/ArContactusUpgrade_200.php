<?php
ArContactUsLoader::loadClass('ArContactusUpgradeAbstract');

ArContactUsLoader::loadModel('ArContactUsConfigForms');
ArContactUsLoader::loadModel('ArContactUsConfigEmails');
ArContactUsLoader::loadModel('ArContactUsConfigWelcome');

ArContactUsLoader::loadModel('ArContactUsModel');

class ArContactusUpgrade_200 extends ArContactusUpgradeAbstract
{
    public function configsToUpdate()
    {
        return array(
            'arcum' => array(
                'ARCUM_MENU_LAYOUT' => 'default',
                'ARCUM_ICONS_TITLE' => 'Start chat with:',
            ),
            'arcumm' => array(
                'ARCUMM_MENU_LAYOUT' => 'default',
                'ARCUMM_ICONS_TITLE' => 'Start chat with:',
            ),
            'arcup' => array(
                'ARCUP_RECAPTCHA_TRESHOLD' => '0.6',
                'ARCUP_RECAPTCHA_ERROR' => 'ReCaptcha validation error. Please try again.'
            ),
        );
    }
    
    public function upgrade()
    {
        ArContactUsCallbackModel::getDb()->query("ALTER TABLE `" . ArContactUsCallbackModel::tableName() . "`
            ADD COLUMN `type` TINYINT(3) UNSIGNED NULL DEFAULT '0' AFTER `status`,
            ADD COLUMN `message` TEXT NULL DEFAULT NULL AFTER `comment`,
            ADD COLUMN `params` TEXT NULL DEFAULT NULL AFTER `message`");
        
        $configForms = new ArContactUsConfigForms();
        $configForms->buildDefaultForms();
        
        $emailConfigs = new ArContactUsConfigEmails();
        $emailConfigs->loadDefaults();
        $emailConfigs->saveToConfig();
        
        $welcomeConfig = new ArContactUsConfigWelcome();
        $welcomeConfig->loadDefaults();
        $welcomeConfig->saveToConfig();
        
        $models = ArContactUsModel::find()->where(array('type' => 3))->all();
        
        foreach ($models as $model) {
            $sql = 'UPDATE `' . ArContactUsModel::tableName() . "` SET params = '" . json_encode(array('form' => 'callback')) . "' WHERE id = " . (int)$model->id ;
            ArContactUsModel::getDb()->query($sql);
        }
        
        foreach ($this->configsToUpdate() as $key => $params)
        {
            $json = get_option($key);
            $data = json_decode($json, true);
            foreach ($params as $k => $v) {
                $data[$k] = $v;
            }
            update_option($key, json_encode($data));
        }
        
        update_option('arcu_recompile_css', 1);
        
        return true;
    }
    
    public function getVersion()
    {
        return '2.0.0';
    }
}