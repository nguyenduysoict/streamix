<?php
ArContactUsLoader::loadClass('ArContactusUpgradeAbstract');

class ArContactusUpgrade_223 extends ArContactusUpgradeAbstract
{
    public function upgrade()
    {
        update_option('arcu_recompile_css', 1);
        $menuConfig = new ArContactUsConfigMobileMenu('arcumm_');
        $this->migrateMenuConfig($menuConfig);
        
        $menuConfig = new ArContactUsConfigMenu('arcum_');
        $this->migrateMenuConfig($menuConfig);
        return true;
    }
    
    public function migrateMenuConfig($menuConfig)
    {
        $changed = false;
        if ($menuConfig->menu_size == 'large') {
            $changed = true;
            $menuConfig->menu_size = 'normal';
        }
        if ($menuConfig->menu_style == '0') {
            $changed = true;
            $menuConfig->menu_style = 'regular';
        }
        $menuConfig->saveToConfig();
    }
    
    public function getVersion()
    {
        return '2.2.3';
    }
}

