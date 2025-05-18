<?php
ArContactUsLoader::loadModel('ArContactUsConfigButtonAbstract');

class ArContactUsConfigButton extends ArContactUsConfigButtonAbstract
{
    public function getJsonConfigKey()
    {
        return 'arcub';
    }
    
    public function overrideUnsafeAttributes()
    {
        return array(
            'storefront_pos'
        );
    }
}
