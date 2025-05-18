<?php
ArContactUsLoader::loadModel('ArContactUsConfigModel');

class ArContactUsConfigMenuAbstract extends ArContactUsConfigModel
{
    public $menu_size;
    public $menu_width;
    public $menu_layout;
    public $icons_title;
    public $menu_popup_style;
    public $popup_animation;
    public $sidebar_animation;
    
    public $menu_style;
    public $item_style;
    public $items_animation;
    public $item_border_style;
    public $item_border_color;
    
    // header options
    public $menu_header_on;
    public $menu_header_layout;
    public $menu_header_icon_type;
    public $menu_header_icon_svg;
    // public $menu_header_icon_fa5;
    public $menu_header_icon_img;
    public $menu_header;
    public $menu_subheader;
    public $header_close;
    public $header_close_bg;
    public $header_close_color;
    
    public $menu_bg;
    public $menu_color;
    public $menu_subtitle_color;
    public $menu_hbg;
    public $menu_hcolor;
    public $menu_subtitle_hcolor;
    public $shadow_size;
    public $shadow_opacity;
    public $backdrop;
    public $auto_open;
    
    public function getHeaderIcon()
    {
        switch ($this->menu_header_icon_type) {
            case 'svg':
                return ArContactUsConfigModel::getIcon($this->menu_header_icon_svg);
            case 'fa5':
                return $this->menu_header_icon_fa5;
            case 'img':
                $image = wp_get_attachment_image_src($this->menu_header_icon_img, 'full', false);
                list($src, $width, $height) = $image;
                return $this->menu_header_icon_img? $src : null;
        }
        return null;
    }
    
    public function multiLangFields()
    {
        return array(
            'menu_header' => true,
            'menu_subheader' => true,
            'icons_title' => true
        );
    }
    
    public function getFormTitle()
    {
        return __('Menu settings', 'ar-contactus');
    }
    
    public function attributeDefaults()
    {
        return array(
            'menu_size' => 'normal',
            'menu_width' => '300',
            'menu_layout' => 'default',
            'icons_title' => 'Start chat with:',
            'item_style' => 'rounded',
            'menu_style' => 'regular',
            'menu_popup_style' => 'popup',
            'popup_animation' => 'fadeindown',
            'sidebar_animation' => 'elastic',
            'items_animation' => 'downtoup',
            'item_border_style' => 'none',
            'item_border_color' => 'dddddd',
            'menu_header_on' => 0,
            'menu_header_layout' => 'noicon',
            'menu_header_icon_type' => 'svg',
            'menu_header' => 'How would you like to contact us?',
            'header_close' => 0,
            'header_close_bg' => '008749',
            'header_close_color' => 'ffffff',
            'menu_bg' => 'ffffff',
            'menu_color' => '3b3b3b',
            'menu_subtitle_color' => '787878',
            'menu_subtitle_hcolor' => '787878',
            'menu_hbg' => 'f0f0f0',
            'menu_hcolor' => '3b3b3b',
            'shadow_size' => '30',
            'shadow_opacity' => '0.2',
            'auto_open' => 0,
            'backdrop' => 1
        );
    }
}