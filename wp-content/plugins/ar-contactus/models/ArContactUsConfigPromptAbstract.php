<?php
ArContactUsLoader::loadModel('ArContactUsConfigModel');

class ArContactUsConfigPromptAbstract extends ArContactUsConfigModel
{
    public $enable_prompt;
    public $prompt_position;
    public $first_delay;
    public $loop;
    public $close_last;
    public $typing_time;
    public $message_time;
    public $show_after_close;
    
    
    public function getFormTitle()
    {
        return __('Prompt settings', 'ar-contactus');
    }
    
    public function attributeDefaults()
    {
        return array(
            'enable_prompt' => 1,
            'prompt_position' => 'top',
            'first_delay' => '2000',
            'loop' => 0,
            'close_last' => 0,
            'typing_time' => '2000',
            'message_time' => '4000',
            'show_after_close' => '0'
        );
    }
}
