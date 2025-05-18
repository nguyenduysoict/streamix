<?php
class ArContactUsPerfex
{
    protected $endpoint;
    protected $token;
    
    public function __construct($endpoint, $token)
    {
        $this->endpoint = $endpoint;
        $this->token = $token;
    }
    
    public function newLead($form, $formData)
    {
        $url = $this->endpoint . 'leads';
        
        $data = array();
        $data['status'] = $form->perfex_status;
        $data['source'] = $form->perfex_source;
        
        foreach (ArContactUsForm::perfexAssignmentFields() as $field => $label) {
            $key = str_replace('perfex_', '', $field);
            $dataField = $form->$field;
            if (isset($formData[$dataField])) {
                $data[$key] = $formData[$dataField];
            }
        }
        
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'authtoken: ' . $this->token
        );
        $headers = implode(PHP_EOL, $headers) . PHP_EOL;
        
        $context = stream_context_create(array(
            'http' => array(
                'ignore_errors' => true,
                'method' => 'POST',
                'header' => $headers,
                'content' => http_build_query($data)
            ),
        ));
        $res = file_get_contents($url, false, $context);
        
        return array(
            'data' => $data,
            'result' => json_decode($res)
        );
    }
}
