<?php
ArContactUsLoader::loadClass('ArContactusUpgradeAbstract');
ArContactUsLoader::loadModel('ArContactUsCallbackModel');

class ArContactusUpgrade_157 extends ArContactusUpgradeAbstract
{
    public function upgrade()
    {
        $sql = 'ALTER TABLE `' . ArContactUsCallbackModel::tableName() . '`
            ADD COLUMN `name` VARCHAR(255) NULL DEFAULT NULL AFTER `phone`,
            ADD COLUMN `email` VARCHAR(255) NULL DEFAULT NULL AFTER `name`;';
        ArContactUsCallbackModel::getDb()->query($sql);
        return true;
    }
    
    public function getVersion()
    {
        return '1.5.7';
    }
}
