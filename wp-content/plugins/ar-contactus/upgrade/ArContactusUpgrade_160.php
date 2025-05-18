<?php
ArContactUsLoader::loadClass('ArContactusUpgradeAbstract');
ArContactUsLoader::loadModel('ArContactUsModel');

class ArContactusUpgrade_160 extends ArContactusUpgradeAbstract
{
    public function upgrade()
    {
        $sql = 'ALTER TABLE `' . ArContactUsModel::tableName() . '`
            ADD COLUMN `content` TEXT NULL DEFAULT NULL AFTER `subtitle`,
            ADD COLUMN `params` TEXT NULL DEFAULT NULL AFTER `content`;';
        ArContactUsModel::getDb()->query($sql);
        return true;
    }
    
    public function getVersion()
    {
        return '1.6.0';
    }
}
