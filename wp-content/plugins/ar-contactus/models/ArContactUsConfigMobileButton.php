<?php
ArContactUsLoader::loadModel('ArContactUsConfigButtonAbstract');

class ArContactUsConfigMobileButton extends ArContactUsConfigButtonAbstract
{
    public function getJsonConfigKey()
    {
        return 'arcumb';
    }
    
    public function positionSelectOptions()
    {
        $array = array(
            'left' => __('Left', 'ar-contactus'),
            'right' => __('Right', 'ar-contactus')
        );
        if (function_exists('storefront_handheld_footer_bar')) {
            $array['storefront'] = __('StoreFront theme footer');
        }
        return $array;
    }
    
    public function attributeDefaults()
    {
        return array(
            'mode' => 'regular',
            'online_badge' => 1,
            'button_icon_type' => 'built-in',
            'button_icon' => 'hangouts',
            'button_size' => 'small',
            'button_icon_size' => '24',
            'button_color' => '008749',
            'position' => 'right',
            'animation' => 'flipInY',
            'storefront_pos' => 4,
            'x_offset' => '10',
            'y_offset' => '10',
            'pulsate_speed' => 2000,
            'icon_speed' => 600,
            'icon_animation_pause' => 2000,
            'text' => '',
            'drag' => 0,
        );
    }
}