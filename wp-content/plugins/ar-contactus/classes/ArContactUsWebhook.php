<?php
class ArContactUsWebhook
{
    protected $url;
    
    public function __construct($url)
    {
        $this->url = $url;
    }
    
    public function sendData($form, $formData)
    {
        $url = $this->url;
        
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
        );
        $headers = implode(PHP_EOL, $headers) . PHP_EOL;
        
        $context = stream_context_create(array(
            'http' => array(
                'ignore_errors' => true,
                'method' => 'POST',
                'header' => $headers,
                'content' => http_build_query($formData)
            ),
        ));
        return file_get_contents($url, false, $context);
    }
}
