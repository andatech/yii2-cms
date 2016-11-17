<?php

namespace anda\cms\components;

use Yii;
use yii\base\Object;

class API extends Object
{
//    /** @var  array */
//    static $classes;
//
//
//    public static function __callStatic($method, $params)
//    {
//        $name = (new \ReflectionClass(self::className()))->getShortName();
//        if (!isset(self::$classes[$name])) {
//            self::$classes[$name] = new static();
//        }
//        return call_user_func_array([self::$classes[$name], 'api_' . $method], $params);
//    }

    public function getChildModule($name=null)
    {
        if(!is_null($name)){
            $className = 'anda\cms\modules\\' . $name . '\api\\'.ucfirst($name);
            if (class_exists($className)) {
                $this->registerTranslations($name);
                $moduleApi = new $className();
                $moduleApi->moduleId = $name;
                return $moduleApi;
            }
        }

        return 'error';
    }

    public function registerTranslations($name)
    {
        $messagesDir = dirname(dirname((new \ReflectionClass($this))->getFileName())) . DIRECTORY_SEPARATOR . 'modules/' .$name . '/messages';
        Yii::$app->i18n->translations['andacms*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => $messagesDir,
            'fileMap' => [
                'andacms/module' => 'andacms.php',
            ]
        ];
    }
}