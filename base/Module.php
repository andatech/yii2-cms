<?php

namespace anda\cms\base;

use Yii;
/**
 * module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $settings = [];

    public $masterModule;

    public $icon;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here

        $this->masterModule = Yii::$app->getModule(Yii::$app->params['ANDACMS']['MASTER_MODULE_ID'], false);

        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $messagesDir = dirname((new \ReflectionClass($this))->getFileName()) . DIRECTORY_SEPARATOR . 'messages';
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
