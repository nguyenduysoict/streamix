<?php
ArContactUsLoader::loadModel('ArContactUsFormEntity');

class ArContactUsFormField extends ArContactUsFormEntity
{
    public $id;
    public $type;
    public $label;
    public $placeholder;
    public $value;
    public $mask_on;
    public $mask;
    public $required;
    public $values;
    public $validation;
    public $preg;
    public $report;
    public $report_label;
    
    protected $errors = array();
    
    public function rules()
    {
        return array(
            array(
                array(
                    'id'
                ), 'required'
            ),
            array(
                array(
                    'id'
                ), 'isId', 'message' => __('ID should contains only letters, numbers and symbols "_"', 'ar-contactus')
            ),
            array(
                array(
                    'mask'
                ), 'required', 'on' => $this->mask_on && in_array($this->type, array('text', 'tel', 'email'))
            ),
            array(
                array(
                    'preg'
                ), 'required', 'on' => $this->validation == 'advanced'
            ),
            array(
                array(
                    'values'
                ), 'required', 'on' => in_array($this->type, array('select'))
            ),
            array(
                array(
                    'report_label'
                ), 'required', 'on' => $this->report
            ),
            array(
                array(
                    'id',
                    'type',
                    'label',
                    'placeholder',
                    'value',
                    'mask_on',
                    'mask',
                    'required',
                    'values',
                    'validation',
                    'preg',
                    'report',
                    'report_label',
                ), 'safe'
            )
        );
    }
    
    public function __construct($id, $fieldData)
    {
        parent::__construct($id, $fieldData);
        foreach ($this->label as $k => $v) {
            $this->label[$k] = stripslashes($v);
        }
        $this->preg = preg_replace('{\\\+}is', "\\", $this->preg);
    }
    
    public function beforeSave()
    {
        $this->preg = stripslashes($this->preg);
        return parent::beforeSave();
    }
    
    public function validate($value)
    {
        if ($this->required && empty($value)) {
            $this->addValidationError(__('Field is required!', 'ar-contactus'));
            return false;
        }
        if (!empty($this->validation) && $this->validation != 'advanced') {
            $validation = 'validate' . ucfirst($this->validation);
            if (method_exists($this, $validation)) {
                if (!$this->required && empty($value)) {
                    return true;
                }
                if (!$this->$validation($value)) {
                    $this->addValidationError(__('Value is not valid', 'ar-contactus'));
                    return false;
                }
            }
        }
        if (!empty($this->validation) && $this->validation == 'advanced') {
            $preg = '{' . $this->preg . '}is';
            if (!preg_match($preg, $value)) {
                $this->addValidationError(__('Value is not valid', 'ar-contactus'));
                return false;
            }
        }
        
        return true;
    }
    
    public function validateLetters($value)
    {
        if (!$this->required && empty($value)) {
            return true;
        }
        return preg_match('/^[\p{Latin}\p{Cyrillic}\p{Armenian}\p{Hebrew}\p{Arabic}\p{Syriac}\p{Thaana}\p{Devanagari}\p{Bengali}\p{Gurmukhi}\p{Gujarati}\p{Oriya}\p{Tamil}\p{Telugu}\p{Kannada}\p{Malayalam}\p{Sinhala}\p{Thai}\p{Lao}\p{Tibetan}\p{Myanmar}\p{Georgian}\p{Ethiopic}\p{Cherokee}\p{Ogham}\p{Runic}\p{Tagalog}\p{Hanunoo}\p{Buhid}\p{Tagbanwa}\p{Khmer}\p{Mongolian}\p{Limbu}\p{Tai_Le}\p{Hiragana}\p{Katakana}\p{Bopomofo}\p{Greek}\sA-Za-zА-Яа-я]+$/iu', $value);
    }
    
    public function validateNumbers($value)
    {
        if (!$this->required && empty($value)) {
            return true;
        }
        return preg_match('/^[0-9\s]+$/iu', $value);
    }
    
    public function validateLettersNumbers($value)
    {
        if (!$this->required && empty($value)) {
            return true;
        }
        return preg_match('/^[\p{Latin}\p{Cyrillic}\p{Armenian}\p{Hebrew}\p{Arabic}\p{Syriac}\p{Thaana}\p{Devanagari}\p{Bengali}\p{Gurmukhi}\p{Gujarati}\p{Oriya}\p{Tamil}\p{Telugu}\p{Kannada}\p{Malayalam}\p{Sinhala}\p{Thai}\p{Lao}\p{Tibetan}\p{Myanmar}\p{Georgian}\p{Ethiopic}\p{Cherokee}\p{Ogham}\p{Runic}\p{Tagalog}\p{Hanunoo}\p{Buhid}\p{Tagbanwa}\p{Khmer}\p{Mongolian}\p{Limbu}\p{Tai_Le}\p{Hiragana}\p{Katakana}\p{Bopomofo}\p{Greek}\s0-9A-Za-zА-Яа-я]+$/iu', $value);
    }
    
    public function addValidationError($error)
    {
        $this->errors[] = $error;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public static function renderConfigField($type, $formId, $name, $params = array())
    {
        switch ($type) {
            case 'text':
            case 'tel':
            case 'email':
                $view = 'text';
                break;
            default:
                $view = $type;
        }
        $params['formId'] = $formId;
        $params['name'] = $name;
        $params['type'] = $type;
        if (!isset($params['default'])) {
            $params['default'] = 0;
        }
        if (!isset($params['required'])) {
            $params['required'] = false;
        }
        if (!isset($params['lang'])) {
            $params['lang'] = true;
        }
        if (!isset($params['value'])) {
            $params['value'] = null;
        }
        if (!isset($params['hint'])) {
            $params['hint'] = null;
        }
        ob_start();
        extract($params);
	include AR_CONTACTUS_PLUGIN_DIR . 'views/admin/partials/fields/' . $view . '.php';
	$output = ob_get_clean();
	return $output;
    }
    
    public function langAttributes()
    {
        return array(
            'label' => true,
            'placeholder' => true,
            'value' => true,
            'mask' => true,
            'values' => true,
            'report_label' => true
        );
    }
    
    public function getValue($currentLang)
    {
        $value = $this->getLangValue('value', $currentLang);
        return $this->composeValue($value);
    }
    
    public function getValues($lang)
    {
        $res = array();
        $values = $this->getLangValue('values', $lang);
        if (empty($values)) {
            return array();
        }
        $data = explode(PHP_EOL, $values);
        foreach ($data as $value) {
            if (strpos($value, '::')) {
                $valueData = explode('::', $value);
                $res[] = array(
                    'value' => $valueData[0],
                    'label' => $valueData[1]
                );
            } else {
                $res[] = array(
                    'value' => $value,
                    'label' => $value
                );
            }
        }
        return $res;
    }
    
    public function composeValue($value)
    {
        return is_null($value) ? '' : strtr($value, array(
            '{site}' => parse_url(AR_CONTACTUS_PLUGIN_URL, PHP_URL_HOST),
            '{url}' => ArContactUsTools::getCurrentUrl()
        ));
    }
}
