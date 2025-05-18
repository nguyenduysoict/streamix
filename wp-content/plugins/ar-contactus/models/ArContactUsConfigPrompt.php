<?php
ArContactUsLoader::loadModel('ArContactUsConfigPromptAbstract');

class ArContactUsConfigPrompt extends ArContactUsConfigPromptAbstract
{
    public function getJsonConfigKey()
    {
        return 'arcupr';
    }
}
