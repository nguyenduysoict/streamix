<?php
abstract class ArContactUsFormEntity
{
    protected $configErrors = array();
    
    public function overrideUnsafeAttributes()
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
    
    public function isAttributeUnsafe($attribute)
    {
        $attrs = $this->overrideUnsafeAttributes();
        return (in_array($attribute, $attrs));
    }
    
    public function isAttributeSafe($attribute)
    {
        if ($this->isAttributeUnsafe($attribute)){
            return false;
        }
        $rules = $this->rules();
        foreach ($rules as $rule) {
            if (isset($rule[0]) && isset($rule[1]) && in_array($attribute, $rule[0]) && $rule[1] != 'unsafe') {
                return true;
            }
        }
        return false;
    }
    
    public function isAttributeRequired($attribute)
    {
        $rules = $this->rules();
        foreach ($rules as $rule) {
            if (isset($rule[0]) && isset($rule[1]) && in_array($attribute, $rule[0]) && $rule[1] == 'validateRequired') {
                return true;
            }
        }
        return false;
    }
    
    public function getAttributes()
    {
        $attributes = array();
        foreach ($this as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $attributes[$attribute] = $value;
            }
        }
        return $attributes;
    }
    
    public function beforeSave()
    {
        return true;
    }
    
    protected function getMessage($message, $attribute, $value)
    {
        return is_null($message) ? '' : strtr($message, array(
            '{attribute}' => $attribute,
            '{label}' => $this->getAttributeLabel($attribute),
            '{value}' => $value
        ));
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
    
    public function filter()
    {
        foreach ($this->getAttributes() as $attr => $value) {
            if ($filters = $this->getAttributeFilters($attr)) {
                foreach ($filters as $filter) {
                    $method = $filter['filter'];
                    if ($this->isLangAttribute($attr) && is_array($value)) {
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
    
    public function addError($attribute, $error)
    {
        if (isset($this->configErrors[$attribute])) {
            $this->configErrors[$attribute][] = $error;
        } else {
            $this->configErrors[$attribute] = array($error);
        }
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
    
    public function validateConfig($addErrors = true)
    {
        if ($addErrors) {
            $this->errors = array();
        }
        $this->filter();
        $valid = true;
        foreach ($this->getAttributes() as $attr => $value) {
            if ($validators = $this->getAttributeValidators($attr)) {
                foreach ($validators as $validator) {
                    $method = 'validate' . ucfirst($validator['validator']);
                    $params = isset($validator['params'])? $validator['params'] : array();
                    if ((isset($validator['on']) && $validator['on']) || (!isset($validator['on']) || $validator['on'] === null)) {
                        if (method_exists($this, $method)) {
                            if ($this->isLangAttribute($attr) && is_array($value)) {
                                foreach ($value as $lang => $v) {
                                    if ($this->$method($v, $params)) {
                                        $valid = $valid && $this->$method($v, $params);
                                    } else {
                                        if ($addErrors) {
                                            if (isset($validator['message'])) {
                                                $this->addError($attr, $this->getMessage($validator['message'], $attr, $value));
                                            } else {
                                                $this->addError($attr . '_' . $lang, sprintf(__('Incorrect "%s" value for "%s" language', 'ar-contactus'), $this->getAttributeLabel($attr), $lang));
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
                        }
                    } else {
                        $valid = $valid && true;
                    }
                }
            }
        }
        return $valid;
    }
    
    public function getConfigErrors()
    {
        return $this->configErrors;
    }
    
    public function validateEmail($value)
    {
        return !empty($value) && preg_match('/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+(?:[.]?[_a-z\p{L}0-9-])*\.[a-z\p{L}0-9]+$/ui', $value);
    }
    
    public function validateEmailList($value, $params = array())
    {
        $values = explode(PHP_EOL, $value);
        foreach ($values as $email) {
            if (!$this->validateEmail($email)) {
                return false;
            }
        }
        return true;
    }
    
    public function validateIsInt($value, $params = array())
    {
        return ((string)(int)$value === (string)$value || $value === false);
    }
    
    public function validateRequired($value, $params = array())
    {
        return !empty($value);
    }
    
    public function validateIsId($value, $params = array())
    {
        return preg_match('{^[a-z0-9]+$}s', $value);
    }
    
    public function __construct($id, $fieldData)
    {
        $this->id = $id;
        foreach($fieldData as $key => $value) {
            if ($this->isAttributeSafe($key)) {
                if ($this->isLangAttribute($key)) {
                    if (ArContactUsTools::isMultilang()) {
                        if (is_object($value) || is_array($value)) {
                            $this->$key = $value;
                        } else {
                            $values = array();
                            foreach (ArContactUsTools::getLanguages() as $lang){
                                $values[$lang['language_code']] = $value;
                            }
                            $this->$key = $values;
                        }
                    } else {
                        $currentLang = ArContactUsTools::getCurrentLanguage();
                        if (is_object($value) || is_array($value)) {
                            $this->$key = $value;
                        } else {
                            $this->$key = array(
                                $currentLang => $value
                            );
                        }
                    }
                } else {
                    $this->$key = $value;
                }
            }
        }
    }
    
    public function getLangValue($prop, $lang)
    {
        if (!property_exists($this, $prop)) {
            return null;
        }
        $v = $this->$prop;
        if (is_object($v)) {
            return isset($v->$lang)? $v->$lang : null;
        } elseif (is_array($v)) {
            return isset($v[$lang])? $v[$lang] : null;
        }
        return null;
    }
    
    public function langAttributes()
    {
        return array();
    }
    
    public function isLangAttribute($attr)
    {
        $attrs = $this->langAttributes();
        
        return isset($attrs[$attr])? $attrs[$attr] : false;
    }
}
