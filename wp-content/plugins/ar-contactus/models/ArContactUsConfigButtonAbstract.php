<?php
ArContactUsLoader::loadModel('ArContactUsConfigModel');

abstract class ArContactUsConfigButtonAbstract extends ArContactUsConfigModel
{
    public $mode;
    public $button_icon_type;
    public $button_icon;
    public $button_icon_img;
    public $online_badge;
    public $button_color;
    public $button_size;
    public $button_icon_size;
    public $position;
    public $storefront_pos;
    public $animation;
    public $x_offset;
    public $y_offset;
    public $pulsate_speed;
    public $icon_speed;
    public $icon_animation_pause;
    public $text;
    public $title;
    public $description;
    public $label;
    public $drag;
    
    public function getFormTitle()
    {
        return __('Button settings', 'ar-contactus');
    }
    
    public function attributeDefaults()
    {
        return array(
            'mode' => 'regular',
            'online_badge' => 1,
            'button_icon_type' => 'built-in',
            'button_icon' => 'hangouts',
            'button_size' => 'large',
            'button_icon_size' => '24',
            'button_color' => '008749',
            'position' => 'right',
            'animation' => 'flipInY',
            'storefront_pos' => 4,
            'x_offset' => '20',
            'y_offset' => '20',
            'pulsate_speed' => 2000,
            'icon_speed' => 600,
            'icon_animation_pause' => 2000,
            'text' => __('Contact us', 'ar-contactus'),
            'drag' => 0,
        );
    }
    
    public function multiLangFields()
    {
        return array(
            'text' => true,
            'title' => true,
            'description' => true,
            'label' => true
        );
    }
}
