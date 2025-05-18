<?php
ArContactUsLoader::loadController('ArContractUsControllerAbstract');
ArContactUsLoader::loadModel('ArContactUsPromptModel');

class ArContactUsPromptController extends ArContractUsControllerAbstract
{
    protected function ajaxActions()
    {
        return array(
            'arcontactus_save_prompt_item' => 'saveItem',
            'arcontactus_reload_prompt_items' => 'reloadItems',
            'arcontactus_reorder_prompt_items' => 'reorderItems',
            'arcontactus_switch_prompt_item' => 'switchItem',
            'arcontactus_edit_prompt_item' => 'editItem',
            'arcontactus_delete_prompt_item' => 'deleteItem'
        );
    }
    
    public function saveItem()
    {
        $this->assertAccess();
        
        $id = $_POST['id'];
        if (!$id){
            $model = new ArContactUsPromptModel();
            $model->status = 1;
            $model->position = ArContactUsPromptModel::getLastPostion() + 1;
        }else{
            $model = ArContactUsPromptModel::findOne($id);
        }
        
        $model->load($_POST['data']);
        //print_r($model->getAttributes());die();
        if($model->validate()){
            wp_die($this->returnJson(array(
                'success' => $model->save()
            )));
        }else{
            wp_die($this->returnJson(array(
                'success' => 0,
                'errors' => $model->getErrors()
            )));
        }
    }
    
    public function reloadItems()
    {
        $this->assertAccess();
        
        if (ArContactUsTools::isMultilang()) {
            $lang = ArContactUsTools::getCurrentLanguage();
            if ($lang == 'all' || $lang === false) {
                $lang = ArContactUsTools::getDefaultLanguage();
            }
            $items = ArContactUsPromptModel::find()->join(ArContactUsPromptModel::langTableName() . ' `_lang`', 'id_item = id')->where(array('lang' => $lang))->orderBy('`position` ASC')->all();
        } else {
            $items = ArContactUsPromptModel::find()->orderBy('`position` ASC')->all();
        }
        
        wp_die($this->returnJson(array(
            'success' => 1,
            'content' => $this->render('/admin/_prompt_table.php', array(
                'items' => $items
            ))
        )));
    }
    
    public function reorderItems()
    {
        $this->assertAccess();
        
        $data = $_POST['data'];
        foreach ($data as $item) {
            $k = explode('_', $item);
            ArContactUsPromptModel::updateAll(array(
                'position' => (int)$k[1]
            ), array(
                'id'  => (int)$k[0]
            ));
        }
        wp_die($this->returnJson(array()));
    }
    
    public function switchItem()
    {
        $this->assertAccess();
        
        $id = $_POST['id'];
        $model = ArContactUsPromptModel::find()->where(array('id' => $id))->one();
        $model->status = $model->status? 0 : 1;
        $model->save();
        wp_die($this->returnJson(array(
            'success' => 1
        )));
    }
    
    public function editItem()
    {
        $this->assertAccess();
        
        $id = $_GET['id'];
        $model = ArContactUsPromptModel::find()->where(array('id' => $id))->one();
        if (ArContactUsTools::isMultilang()) {
            $model->loadLangData();
        }
        
        wp_die($this->returnJson($model));
    }
    
    public function deleteItem()
    {
        $this->assertAccess();
        
        $id = $_POST['id'];
        $model = ArContactUsPromptModel::find()->where(array('id' => $id))->one();
        wp_die($this->returnJson(array(
            'success' => $model->delete()
        )));
    }
}
