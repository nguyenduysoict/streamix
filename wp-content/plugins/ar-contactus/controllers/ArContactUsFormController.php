<?php
ArContactUsLoader::loadController('ArContractUsControllerAbstract');

class ArContactUsFormController extends ArContractUsControllerAbstract
{
    protected function ajaxActions()
    {
        return array(
            'arcontactus_form_edit' => 'formEdit',
            'arcontactus_form_save' => 'formSave',
            
            'arcontactus_field_edit' => 'fieldEdit',
            'arcontactus_field_save' => 'fieldSave',
            'arcontactus_field_remove' => 'fieldRemove',
            'arcontactus_field_reorder' => 'fieldReorder',
            
            'arcontactus_button_edit' => 'buttonEdit',
            'arcontactus_button_save' => 'buttonSave',
            'arcontactus_button_remove' => 'buttonRemove',
            'arcontactus_button_reorder' => 'buttonReorder',
            
            'arcontactus_reload_forms' => 'reloadForms',
            'arcontactus_reset_forms' => 'resetForms'
        );
    }
    
    public function fieldReorder()
    {
        $formId = $_POST['formId'];
        
        $fieldsOrder = $_POST['fields'];
        $formsConfig = new ArContactUsConfigForms();
        $fields = array();
        
        $form = $formsConfig->getForm($formId);
        
        foreach ($fieldsOrder as $fieldId) {
            $fields[$fieldId] = $form->getField($fieldId);
        }
        $form->fields = $fields;
        $formsConfig->setForm($formId, $form);
        $formsConfig->save();
        wp_die($this->returnJson($form));
    }
    
    public function buttonReorder()
    {
        $formId = $_POST['formId'];
        
        $buttonsOrder = $_POST['buttons'];
        $formsConfig = new ArContactUsConfigForms();
        $buttons = array();
        
        $form = $formsConfig->getForm($formId);
        
        foreach ($buttonsOrder as $buttonId) {
            $buttons[$buttonId] = $form->getButton($buttonId);
        }
        $form->buttons = $buttons;
        $formsConfig->setForm($formId, $form);
        $formsConfig->save();
        wp_die($this->returnJson($form));
    }
    
    public function resetForms()
    {
        $formsConfig = new ArContactUsConfigForms();
        $formsConfig->buildDefaultForms();
        $this->reloadForms();
    }
    
    public function reloadForms()
    {
        $formsConfig = new ArContactUsConfigForms();
        $content = ArContactUsAdmin::render('admin/_forms.php', array(
            'activeSubmit' => 'ArContactUsConfigForms',
            'formsConfig' => $formsConfig,
            'isWPML' => ArContactUsTools::isMultilang(),
            'defaultLang' => ArContactUsTools::getDefaultLanguage(),
            'languages' => ArContactUsTools::getLanguages()
        ));
        wp_die($this->returnJson(array(
            'success' => 1,
            'content' => $content
        )));
    }
    
    public function formEdit()
    {
        $this->assertAccess();
        $id = $_GET['id'];
        $formsConfig = new ArContactUsConfigForms();
        $form = $formsConfig->getForm($id);
        wp_die($this->returnJson($form));
    }
    
    public function fieldEdit()
    {
        $id = $_GET['id'];
        $formId = $_GET['formId'];
        
        $formsConfig = new ArContactUsConfigForms();
        $form = $formsConfig->getForm($formId);
        $field = $form->getField($id);
        wp_die($this->returnJson($field));
    }
    
    public function buttonEdit()
    {
        $id = $_GET['id'];
        $formId = $_GET['formId'];
        
        $formsConfig = new ArContactUsConfigForms();
        $form = $formsConfig->getForm($formId);
        $field = $form->getButton($id);
        wp_die($this->returnJson($field));
    }
    
    public function buttonSave()
    {
        $id = $_POST['id'];
        $formId = $_POST['formId'];
        $data = $_POST['data'];
        $formsConfig = new ArContactUsConfigForms();
        
        $form = $formsConfig->getForm($formId);
        
        $button = new ArContactUsFormButton($id, $data);
        if ($button->validateConfig()) {
            $form->buttons[$id] = $button;

            $formsConfig->setForm($formId, $form);
            $formsConfig->save();
            wp_die($this->returnJson(array(
                'success' => 1,
                'errors' => null,
                'button' => $button
            ))); 
        } else {
            wp_die($this->returnJson(array(
                'success' => 0,
                'errors' => $button->getConfigErrors()
            ))); 
        }
    }
    
    public function buttonRemove()
    {
        $id = $_POST['id'];
        $formId = $_POST['formId'];
        $data = $_POST['data'];
        $formsConfig = new ArContactUsConfigForms();
        
        $forms = $formsConfig->getForms();
        $form = $formsConfig->getForm($formId);
        if (isset($form->buttons[$id])) {
            unset($form->buttons[$id]);
        }
        $forms[$formId] = $form;
        $formsConfig->setForms($forms);
        $formsConfig->save();
        wp_die($this->returnJson($forms));
    }
    
    public function fieldSave()
    {
        $id = $_POST['id'];
        $formId = $_POST['formId'];
        $data = $_POST['data'];
        $formsConfig = new ArContactUsConfigForms();
        
        $form = $formsConfig->getForm($formId);
        
        $field = new ArContactUsFormField($id, $data);
        if ($field->validateConfig()) {
            $form->fields[$id] = $field;
            $formsConfig->setForm($formId, $form);
            $formsConfig->save();
            wp_die($this->returnJson(array(
                'success' => 1,
                'errors' => null,
                'field' => $field
            )));
        } else {
            wp_die($this->returnJson(array(
                'success' => 0,
                'errors' => $field->getConfigErrors()
            )));
        }
    }
    
    public function fieldRemove()
    {
        $id = $_POST['id'];
        $formId = $_POST['formId'];
        
        $formsConfig = new ArContactUsConfigForms();
        
        $form = $formsConfig->getForm($formId);
        if ($form->isUnremovableField($id)) {
            wp_die($this->returnJson(array(
                'success' => 0,
                'message' => __('This field can not be removed', 'ar-contactus')
            )));
        }
        $form->removeField($id);
        $formsConfig->setForm($formId, $form);
        
        $formsConfig->save();
        wp_die($this->returnJson(array(
            'success' => 1,
            'model' => $form
        )));
    }
    
    public function formSave()
    {
        $id = $_POST['id'];
        $data = $_POST['data'];
        $formsConfig = new ArContactUsConfigForms();
        $forms = $formsConfig->getForms();
        $oldForm = $formsConfig->getForm($id);
        
        $data['fields'] = $oldForm->fields;
        $data['buttons'] = $oldForm->buttons;
        
        $form = new ArContactUsForm($id, $data);
        if ($form->validateConfig()) {
            $forms[$id] = $form;
            $formsConfig->setForms($forms);

            $formsConfig->save();

            $this->parent->compileCSS();
            $this->parent->compileCSS(true);

            wp_die($this->returnJson(array(
                'success' => 1,
                'errors' => null,
                'model' => $form
            )));
        } else {
            wp_die($this->returnJson(array(
                'success' => 0,
                'errors' => $form->getConfigErrors()
            )));
        }
        
    }
}
