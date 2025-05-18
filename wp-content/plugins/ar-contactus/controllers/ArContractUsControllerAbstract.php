<?php

abstract class ArContractUsControllerAbstract
{
    protected $parent;
    
    public function init($parent = null)
    {
        $this->parent = $parent;
        $this->registerAjaxActions();
        $this->registerNoprivAjaxActions();
    }
    
    public function checkAccess($permission = 'manage_options')
    {
        if (!current_user_can($permission)) {
            return false;
        }
        return true;
    }
    
    public function assertAccess($permission = 'manage_options')
    {
        if (!$this->checkAccess($permission)) {
            wp_die($this->returnJson(array(
                'error' => 1,
                'success' => 0,
                'message' => __('You dont have access to perform this action', 'ar-contactus')
            )));
        }
        $nonce = $_REQUEST['_wpnonce'];
        if (!wp_verify_nonce($nonce, 'arcu_config')) {
            wp_die($this->returnJson(array(
                'error' => 1,
                'success' => 0,
                'message' => __('Invalid security token', 'ar-contactus')
            )));
        }
        return true;
    }
    
    protected function registerAjaxActions()
    {
        foreach ($this->ajaxActions() as $action => $method){
            add_action('wp_ajax_' . $action, array($this, $method));
        }
    }
    
    protected function registerNoprivAjaxActions()
    {
        foreach ($this->ajaxNoprivActions() as $action => $method){
            add_action('wp_ajax_nopriv_' . $action, array($this, $method));
        }
    }
    
    protected function ajaxNoprivActions()
    {
        return array();
    }
    
    protected function ajaxActions()
    {
        return array();
    }
    
    public function render($view, $data)
    {
        return ArContactUsAdmin::render($view, $data);
    }
    
    public function returnJson($data)
    {
        return wp_json_encode($data);
    }
}
