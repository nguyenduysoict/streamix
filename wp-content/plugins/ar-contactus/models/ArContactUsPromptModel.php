<?php
ArContactUsLoader::loadModel('ArContactUsModelAbstract');
ArContactUsLoader::loadClass('ArContactUsTools');

class ArContactUsPromptModel extends ArContactUsModelAbstract
{
    public $id;
    public $message;
    public $status;
    public $position;
    
    public function rules()
    {
        return array(
            array(
                array(
                    'message',
                    'status',
                    'position'
                ), 'safe'
            ),
            array(
                array(
                    'message'
                ), 'validateRequired'
            )
        );
    }
    
    public function langFields()
    {
        return array(
            'message' => true
        );
    }
    
    public function scheme()
    {
        return array(
            'id' => self::FIELD_INT,
            'message' => self::FIELD_STRING,
            'position' => self::FIELD_INT,
            'status' => self::FIELD_INT
        );
    }
    
    public static function tableName()
    {
        return self::dbPrefix().'arcontactus_prompt';
    }
    
    public static function langTableName()
    {
        return self::tableName().'_lang';
    }
    
    public static function createLangTable()
    {
        return self::getDb()->query("CREATE TABLE IF NOT EXISTS `" . self::langTableName() . "` (
            `lang` VARCHAR(10) NOT NULL,
            `id_item` INT(10) UNSIGNED NOT NULL,
            `message` TEXT(65535) NULL DEFAULT NULL,
            PRIMARY KEY (`lang`, `id_item`)
        )
        COLLATE='utf8_general_ci'");
    }
    
    public static function createTable()
    {
        return self::getDb()->query("CREATE TABLE IF NOT EXISTS `" . self::tableName() . "` (
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `message` TEXT NOT NULL,
            `status` INT(11) UNSIGNED NOT NULL,
            `position` INT(10) UNSIGNED NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        )
        COLLATE='utf8_general_ci';");
    }
    
    public static function truncate()
    {
        return self::getDb()->query("TRUNCATE `" . self::tableName() . "`");
    }
    
    public static function truncateLangTable()
    {
        return self::getDb()->query("TRUNCATE `" . self::langTableName() . "`");
    }
    
    public static function dropTable()
    {
        return self::getDb()->query("DROP TABLE IF EXISTS `" . self::tableName() . "`");
    }
    
    public static function dropLangTable()
    {
        return self::getDb()->query("DROP TABLE IF EXISTS `" . self::langTableName() . "`");
    }
    
    public static function getLastPostion()
    {
        $model = self::find()->orderBy('`position` DESC')->one();
        return $model? $model->position : 0;
    }
    
    public static function getDefaultItems()
    {
        return array(
            array(
                'message' => 'Hello!',
                'status' => 0
            ),
            array(
                'message' => 'Have a questions?',
                'status' => 0
            ),
            array(
                'message' => "Please use this button\r\nto contact us!",
                'status' => 0
            ),
        );
    }
    
    public static function getMessages()
    {
        $res = array();
        if (ArContactUsTools::isMultilang()) {
            $models = self::find()
                ->join(self::langTableName() . ' `_lang`', "`_lang`.id_item = id")
                ->where(array('status' => 1))
                ->andWhere(array('lang' => ArContactUsTools::getCurrentLanguage()))
                ->orderBy('`position` ASC')
                ->all();
        } else {
            $models = self::find()->where(array('status' => 1))->orderBy('`position` ASC')->all();
        }
        
        foreach ($models as $model){
            $res[] = nl2br($model->message);
        }
        return $res;
    }
    
    
    public static function createDefaultItems()
    {
        $isWPML = ArContactUsTools::isMultilang();
        
        foreach (self::getDefaultItems() as $k => $item){
            if ($isWPML) {
                $message = array();
                foreach (ArContactUsTools::getLanguages() as $lang) {
                    $message[$lang['language_code']] = $item['message'];
                }
            } else {
                if ($currentLang = ArContactUsTools::getCurrentLanguage()){
                    $message = array();
                    $message[$currentLang] = $item['message'];
                } else {
                    $message = $item['message'];
                }
            }
            $model = new self();
            $model->message = $message;
            $model->status = $item['status'];
            $model->position = $k + 1;
            $model->save();
        }
    }
}
