<?php
ArContactUsLoader::loadModel('ArContactUsConfigPromptAbstract');

class ArContactUsConfigMobilePrompt extends ArContactUsConfigPromptAbstract
{
    public function getJsonConfigKey()
    {
        return 'arcumpr';
    }
}
