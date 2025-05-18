<?php
ArContactUsLoader::loadClass('ArContactusUpgradeAbstract');
ArContactUsLoader::loadModel('ArContactUsModel');

class ArContactusUpgrade_180 extends ArContactusUpgradeAbstract
{
    public function upgrade()
    {
        update_option('ARCUG_CALLBACK_ACCESS', 'administrator');
        return true;
    }
    
    public function getVersion()
    {
        return '1.8.0';
    }
}
