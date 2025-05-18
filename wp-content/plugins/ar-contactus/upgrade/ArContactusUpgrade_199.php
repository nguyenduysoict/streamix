<?php
ArContactUsLoader::loadClass('ArContactusUpgradeAbstract');
ArContactUsLoader::loadModel('ArContactUsModel');

class ArContactusUpgrade_199 extends ArContactusUpgradeAbstract
{
    public function upgrade()
    {
        ArContactUsModel::getDb()->query("ALTER TABLE `" . ArContactUsModel::tableName() . "`
            ADD COLUMN `language` VARCHAR(10) NULL DEFAULT NULL AFTER `position`,
            ADD INDEX `language` (`language`);");
        
        return true;
    }
    
    public function getVersion()
    {
        return '1.9.9';
    }
}
