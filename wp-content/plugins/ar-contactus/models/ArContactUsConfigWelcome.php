<?php
ArContactUsLoader::loadModel('ArContactUsConfigModel');

class ArContactUsConfigWelcome extends ArContactUsConfigModel
{
    protected $promptConfig;

    public $use_prompt;
    public $first_delay;
    public $typing_time;
    public $message_time;
    public $welcome_messages;
    public $show_type;
    
    public function multiLangFields()
    {
        return array(
            'welcome_messages' => true
        );
    }
    
    /**
     * 
     * @return ArContactUsConfigPromptAbstract
     */
    public function getPromptConfig()
    {
        if ($this->promptConfig == null) {
            if (ArContactUsTools::isMobile()) {
                $this->promptConfig = new ArContactUsConfigPrompt('arcupr_');
            } else {
                $this->promptConfig = new ArContactUsConfigMobilePrompt('arcumpr_');
            }
            $this->promptConfig->loadFromConfig();
        }
        return $this->promptConfig;
    }
    
    public function getJsonConfigKey()
    {
        return 'arcuw';
    }
    
    public function getMessages($lang)
    {
        if ($this->use_prompt) {
            return ArContactUsPromptModel::getMessages();
        }
        if (ArContactUsTools::isMultilang()) {
            return explode(PHP_EOL, $this->welcome_messages[$lang]);
        }
        return explode("\r\n", $this->welcome_messages);
    }
    
    public function getFirstDelay()
    {
        if ($this->use_prompt) {
            return $this->getPromptConfig()->first_delay;
        }
        return $this->first_delay;
    }
    
    public function getMessageTime()
    {
        if ($this->use_prompt) {
            return $this->getPromptConfig()->message_time;
        }
        return $this->message_time;
    }
    
    public function getTypingTime()
    {
        if ($this->use_prompt) {
            return $this->getPromptConfig()->typing_time;
        }
        return $this->typing_time;
    }
    
    public function attributeDefaults()
    {
        return array(
            'use_prompt' => 0,
            'first_delay' => 2000,
            'typing_time' => 2000,
            'message_time' => 4000,
            'welcome_messages' => "Hi!\r\nThere is a second message\r\nAnd this is the third one",
            'show_type' => 'menu_open'
        );
    }
}
