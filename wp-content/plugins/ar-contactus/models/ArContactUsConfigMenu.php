<?php
ArContactUsLoader::loadModel('ArContactUsConfigMenuAbstract');

class ArContactUsConfigMenu extends ArContactUsConfigMenuAbstract
{
    public function getJsonConfigKey()
    {
        return 'arcum';
    }
}
