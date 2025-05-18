<?php
ArContactUsLoader::loadModel('ArContactUsConfigMenuAbstract');

class ArContactUsConfigMobileMenu extends ArContactUsConfigMenuAbstract
{   
    public function getJsonConfigKey()
    {
        return 'arcumm';
    }
    
    public function overrideUnsafeAttributes()
    {
        return array(
            'menu_width',
            'menu_style'
        );
    }
}
