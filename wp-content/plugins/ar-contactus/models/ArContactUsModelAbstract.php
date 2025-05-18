<?php
ArContactUsLoader::loadModel('ArContactUsQuery');

abstract class ArContactUsModelAbstract
{
    const FIELD_INT = 'int';
    const FIELD_STRING = 'string';
    
    protected $errors = array();
    protected $isNewRecord = true;
    
    /**
     * @return wpdb
     */
    public static function getDb()
    {
        global $wpdb;
        return $wpdb;
    }
    
    public function langFields()
    {
        return array();
    }
    
    public function isLangField($attr)
    {
        $attrs = $this->langFields();
        return isset($attrs[$attr]) && $attrs[$attr];
    }
    
    public static function dbPrefix()
    {
        return self::getDb()->prefix;
    }
    
    public static function primaryKey()
    {
        return 'id';
    }
    
    public static function tableName()
    {
        return '';
    }
    
    public static function langTableName()
    {
        return '';
    }
    
    public static function createTable()
    {
        return '';
    }
    
    public static function dropTable()
    {
        return '';
    }
    
    public function scheme()
    {
        return array();
    }
    
    public function rules()
    {
        return array();
    }
    
    public function filters()
    {
        return array();
    }
    
    public function save()
    {
        $class = get_called_class();
        $data = $this->getAttributes();
        if ($this->isNewRecord){
            if ($res = $this->insert($data)){
                $pk = $class::primaryKey();
                $this->$pk = self::getDb()->insert_id;
                $this->isNewRecord = false;
            }
            return $res;
        }
        if ($res = $this->update($data)){
            $this->isNewRecord = false;
        }
        return $res === false? false : true;
    }
    
    public static function updateAll($attributes, $condition)
    {
        $class = get_called_class();
        $tableName = $class::tableName();
        self::getDb()->update($tableName, $attributes, $condition);
    }
    
    public static function deleteAll($condition)
    {
        $class = get_called_class();
        $tableName = $class::tableName();
        self::getDb()->delete($tableName, $condition);
    }
    
    public function loadLangData()
    {
        $pk = self::primaryKey();
        $class = get_called_class();
        if ($this->langFields()) {
            $sql = "SELECT * FROM `" . $class::langTableName() . "` WHERE id_item = " . $this->$pk;
            if ($data = self::getDb()->get_results($sql, ARRAY_A)) {
                foreach ($data as $row) {
                    foreach ($this->langFields() as $attr => $isLang) {
                        if (!is_array($this->$attr)) {
                            $this->$attr = array();
                        }
                        $this->$attr[$row['lang']] = $row[$attr];
                    }
                }
            }
            
            return true;
        }
        return false;
    }
    
    public function update($data)
    {
        $pk = self::primaryKey();
        $class = get_called_class();
        $tableName = $class::tableName();
        
        $langData = array();
        foreach ($data as $attr => $value) {
            if ($this->isLangField($attr) && is_array($value)) {
                foreach ($value as $lang => $v) {
                    if (!isset($langData[$lang])) {
                        $langData[$lang] = array();
                    }
                    $langData[$lang][$attr] = $v;
                    $langData[$lang]['lang'] = $lang;
                }
                $data[$attr] = reset($value);
            }
        }
        $update = self::getDb()->update($tableName, $data, array($pk => $this->$pk));
        if (!empty($langData)) {
            foreach ($langData as $lang => $data){
                if (self::getDb()->query('SELECT * FROM `' . $class::langTableName() . '` WHERE `id_item` = ' . (int)$this->$pk . ' AND lang = "' . $lang . '"')) {
                    self::getDb()->update($class::langTableName(), $data, array('id_item' => $this->$pk, 'lang' => $lang));
                } else {
                    $data['id_item'] = $this->$pk;
                    self::getDb()->insert($class::langTableName(), $data);
                }
            }
        }
        
        return $update;
    }
    
    public function delete()
    {
        $class = get_called_class();
        $pk = self::primaryKey();
        if (!empty($this->langFields())) {
            self::getDb()->delete($class::langTableName(), array('id_item' => $this->$pk));
        }
        return self::getDb()->delete($class::tableName(), array($pk => $this->$pk));
    }
    
    protected function insert($data)
    {
        $class = get_called_class();
        $tableName = $class::tableName();
        $langData = array();
        foreach ($data as $attr => $value) {
            if ($this->isLangField($attr)) {
                if (is_array($value)) {
                    foreach ($value as $lang => $v) {
                        if (!isset($langData[$lang])) {
                            $langData[$lang] = array();
                        }
                        $langData[$lang][$attr] = $v;
                        $langData[$lang]['lang'] = $lang;
                    }
                    $data[$attr] = reset($value);
                } else {
                    $langs = ArContactUsTools::getLanguages();
                    foreach ($langs as $lang) {
                        $langData[$lang['language_code']][$attr] = $value;
                        $langData[$lang['language_code']]['lang'] = $lang['language_code'];
                    }
                    $data[$attr] = $value;
                }
            }
        }
        
        $insert = self::getDb()->insert($tableName, $data);
        $id_item = self::getDb()->insert_id;
        if (!empty($langData)) {
            foreach ($langData as $lang => $data){
                $data['id_item'] = $id_item;
                self::getDb()->insert($class::langTableName(), $data);
            }
        }
        return $insert;
    }
    
    public function attributeLabels()
    {
        return array();
    }
    
    public function getAttributeLabel($attribute)
    {
        $labels = $this->attributeLabels();
        if (isset($labels[$attribute])) {
            return $labels[$attribute];
        }
        return $attribute;
    }


    /**
     * 
     * @return \ArContactUsQuery
     */
    public static function find()
    {
        $class = get_called_class();
        return new ArContactUsQuery(new $class);
    }
    
    public static function findOne($condition)
    {
        $class = get_called_class();
        $query = new ArContactUsQuery(new $class);
        return $query->where(array($class::primaryKey() => $condition))->one();
    }
    
    public function getTableName()
    {
        $class = get_called_class();
        return $class::tableName();
    }
    
    public function getAttributes()
    {
        $data = array();
        $className = get_called_class();
        $pk = $className::primaryKey();
        foreach ($this as $k => $v){
            if ($this->isAttributeSafe($k) || $k === $pk){
                $data[$k] = $v;
            }
        }
        return $data;
    }
    
    public function getAttributeType($attribute)
    {
        $scheme = $this->scheme();
        if (isset($scheme[$attribute])) {
            return $scheme[$attribute];
        }
        return self::FIELD_STRING;
    }
    
    public function isAttributeSafe($attribute)
    {
        $rules = $this->rules();
        foreach ($rules as $rule) {
            if (isset($rule[0]) && isset($rule[1]) && in_array($attribute, $rule[0]) && $rule[1] != 'unsafe') {
                return true;
            }
        }
        return false;
    }
    
    public function setAttribute($attribute, $value)
    {
        if (property_exists($this, $attribute)){
            if (!is_array($value)) {
                $this->$attribute = is_null($value)? $value : stripslashes($value);
            } else {
                foreach ($value as $k => $v) {
                    $value[$k] = is_null($v)? $v : stripslashes($v);
                }
                $this->$attribute = $value;
            }
        }
    }
    
    public function load($data)
    {
        foreach ($data as $attribute => $value){
            if ($this->isAttributeSafe($attribute)){
                $this->setAttribute($attribute, $value);
            }
        }
    }
    
    public function isNewRecord()
    {
        return $this->isNewRecord;
    }
    
    public function setIsNewRecord($bool)
    {
        $this->isNewRecord = $bool;
    }
    
    public function validate($addErrors = true)
    {
        if ($addErrors) {
            $this->errors = array();
        }
        $this->filter();
        $valid = true;
        foreach ($this->getAttributes() as $attr => $value) {
            $valid = $this->validateAttribute($attr, $addErrors) && $valid;
        }
        return $valid;
    }
    
    public function validateAttribute($attr, $addErrors = true)
    {
        $valid = true;
        $value = $this->$attr;
        if ($validators = $this->getAttributeValidators($attr)) {
            foreach ($validators as $validator) {
                $method = $validator['validator'];
                $params = isset($validator['params'])? $validator['params'] : array();
                if ((isset($validator['on']) && $validator['on']) || (!isset($validator['on']) || $validator['on'] === null)) {
                    if (method_exists($this, $method)) {
                        if ($this->isLangField($attr) && is_array($value)) {
                            foreach ($value as $lang => $v) {
                                if ($this->$method($v, $params)) {
                                    $valid = $valid && $this->$method($v, $params);
                                } else {
                                    if ($addErrors) {
                                        if (isset($validator['message'])) {
                                            $this->addError($attr, $this->getMessage($validator['message'], $attr, $v));
                                        } else {
                                            $this->addError($attr, sprintf(__('Incorrect "%s" value for "%s" language', 'ar-contactus'), $this->getAttributeLabel($attr), $lang));
                                        }
                                    }
                                    $valid = false;
                                }
                            }
                        } else {
                            if ($this->$method($value, $params)) {
                                $valid = $valid && $this->$method($value, $params);
                            } else {
                                if ($addErrors) {
                                    if (isset($validator['message'])) {
                                        $this->addError($attr, $this->getMessage($validator['message'], $attr, $value));
                                    } else {
                                        $this->addError($attr, sprintf(__('Incorrect "%s" value', 'ar-contactus'), $this->getAttributeLabel($attr)));
                                    }
                                }
                                $valid = false;
                            }
                        }
                    }else{
                        $valid = $valid && true;
                    }
                } else {
                    $valid = $valid && true;
                }
            }
        }
        return $valid;
    }
    
    public function getAttributeValidators($attribute)
    {
        $rules = $this->rules();
        $validators = array();
        foreach ($rules as $rule) {
            if (isset($rule[0]) && isset($rule[1]) && in_array($attribute, $rule[0]) && $rule[1] != 'unsafe') {
                $validator = array(
                    'validator' => $rule[1],
                    'params' => isset($rule['params'])? $rule['params'] : array(),
                    'message' => isset($rule['message'])? $rule['message'] : null,
                );
                if (isset($rule['on'])) {
                    $validator['on'] = $rule['on'];
                }
                $validators[] = $validator;
            }
        }
        return $validators;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function addError($attribute, $error)
    {
        if (isset($this->errors[$attribute])) {
            $this->errors[$attribute][] = $error;
        } else {
            $this->errors[$attribute] = array($error);
        }
    }
    
    public function filter()
    {
        foreach ($this->getAttributes() as $attr => $value) {
            if ($filters = $this->getAttributeFilters($attr)) {
                foreach ($filters as $filter) {
                    $method = $filter['filter'];
                    if ($this->getMultiLangAttribute($attr) && is_array($value)) {
                        foreach ($value as $k => $v) {
                            if (method_exists($this, $method)) {
                                $this->$attr[$k] = $this->$method($v, $filter['params']);
                            }
                        }
                    } else {
                        if (method_exists($this, $method)) {
                            $this->$attr = $this->$method($value, $filter['params']);
                        }
                    }
                }
            }
        }
    }
    
    public function getAttributeFilters($attribute)
    {
        $rules = $this->filters();
        $filters = array();
        foreach ($rules as $rule) {
            if (isset($rule[0]) && isset($rule[1]) && in_array($attribute, $rule[0])) {
                $filter = array(
                    'filter' => $rule[1],
                    'params' => isset($rule['params'])? $rule['params'] : array()
                );
                if (isset($rule['on'])) {
                    $filter['on'] = $rule['on'];
                }
                $filters[] = $filter;
            }
        }
        return $filters;
    }
    
    public function validateRequired($value, $params = array())
    {
        if (empty($value)){
            return false;
        }
        return true;
    }
}
