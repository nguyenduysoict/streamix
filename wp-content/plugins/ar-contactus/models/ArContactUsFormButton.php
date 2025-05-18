<?php
ArContactUsLoader::loadModel('ArContactUsFormEntity');

class ArContactUsFormButton extends ArContactUsFormEntity
{
    public $id;
    public $type;
    public $label;
    public $url;
    public $new_window;
    public $class_name;
    
    public function rules()
    {
        return array(
            array(
                array(
                    'id',
                    'label',
                    'type'
                ), 'required'
            ),
            array(
                array(
                    'id'
                ), 'isId', 'message' => __('ID should contains only letters, numbers and symbols "_"', 'ar-contactus')
            ),
            array(
                array(
                    'url',
                ), 'required', 'on' => $this->type == 'link'
            ),
            array(
                array(
                    'id',
                    'type',
                    'label',
                    'url',
                    'new_window',
                    'class_name'
                ), 'safe'
            )
        );
    }
    
    public function langAttributes()
    {
        return array(
            'label' => true
        );
    }
}
