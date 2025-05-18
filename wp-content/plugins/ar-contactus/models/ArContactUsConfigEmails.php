<?php
ArContactUsLoader::loadModel('ArContactUsConfigModel');

class ArContactUsConfigEmails extends ArContactUsConfigModel
{
    public $email_img;
    
    public $callback_email_subject;
    public $callback_email_body;
    
    public $email_email_subject;
    public $email_email_body;
    
    public function getJsonConfigKey()
    {
        return 'arcue';
    }
    
    public function beforeSave()
    {
        if (is_array($this->callback_email_body) || is_object($this->callback_email_body)) {
            $callback_email_body = array();
            foreach ($this->callback_email_body as $lang => $value) {
                $callback_email_body[$lang] = stripslashes($value);
                $callback_email_body[$lang] = preg_replace('{(\r\n)+}is', "\r\n", $callback_email_body[$lang]);
            }
            $this->callback_email_body = $callback_email_body;
        } else {
            $this->callback_email_body = stripslashes($this->callback_email_body);
            $this->callback_email_body = preg_replace('{(\r\n)+}is', "\r\n", $this->callback_email_body);
        }
        
        if (is_array($this->email_email_body) || is_object($this->email_email_body)) {
            $email_email_body = array();
            foreach ($this->email_email_body as $lang => $value) {
                $email_email_body[$lang] = stripslashes($value);
                $email_email_body[$lang] = preg_replace('{(\r\n)+}is', "\r\n", $email_email_body[$lang]);
            }
            $this->email_email_body = $email_email_body;
        } else {
            $this->email_email_body = stripslashes($this->email_email_body);
            $this->email_email_body = preg_replace('{(\r\n)+}is', "\r\n", $this->email_email_body);
        }
        
        return parent::beforeSave();
    }
    
    public function multiLangFields()
    {
        return array(
            'callback_email_subject' => true,
            'callback_email_body' => true,
            'email_email_subject' => true,
            'email_email_body' => true,
        );
    }
    
    public function attributeDefaults()
    {
        return array(
            'callback_email_subject' => 'Callback request from {phone}',
            'callback_email_body' => "<ul>\r\n \t<li><strong>Phone<\/strong>: {phone}<\/li>\r\n \t<li><em>Email<\/em>: {email}<\/li>\r\n \t<li><span style=\"color: #00ff00;\">Name<\/span>: {name}<\/li>\r\n \t<li><span style=\"color: #ff0000;\">From<\/span>: {referer}<\/li>\r\n \t<li><del>Site<\/del>: {site}<\/li>\r\n<\/ul>",
            'email_email_subject' => 'Direct email request',
            'email_email_body' => '<em>Name: {name}<\/em>\r\nEmail: {email}\r\nMessage: {message}'
        );
    }
}
