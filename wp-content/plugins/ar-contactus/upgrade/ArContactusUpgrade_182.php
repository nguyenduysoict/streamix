<?php
ArContactUsLoader::loadClass('ArContactusUpgradeAbstract');
ArContactUsLoader::loadModel('ArContactUsModel');

class ArContactusUpgrade_182 extends ArContactusUpgradeAbstract
{
    public function upgrade()
    {
        update_option('ARCUP_RECAPTCHA_INIT', 1);
        return true;
    }
    
    public function getVersion()
    {
        return '1.8.2';
    }
}
