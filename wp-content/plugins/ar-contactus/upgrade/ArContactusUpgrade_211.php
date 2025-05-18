<?php
ArContactUsLoader::loadClass('ArContactusUpgradeAbstract');

class ArContactusUpgrade_211 extends ArContactusUpgradeAbstract
{
    public function upgrade()
    {
        update_option('arcu_recompile_css', 1);
        return true;
    }
    
    public function getVersion()
    {
        return '2.1.1';
    }
}
