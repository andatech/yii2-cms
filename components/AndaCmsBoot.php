<?php
namespace anda\cms\components;

use Yii;
use yii\base\BootstrapInterface;
use anda\cms\models\Setting;

/*
/* The base class that you use to retrieve the settings from the database
*/

class AndaCmsBoot implements BootstrapInterface {

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * Loads all the settings into the Yii::$app->params array
     * @param Application $app the application currently running
     */

    public function __construct()
    {
        Yii::$app->params['ANDACMS'] = [];
    }

    public function bootstrap($app) {
        $settingsModel = Setting::find()->all();

        foreach ($settingsModel as $key => $setting){
            $app->params['ANDACMS'][$setting->type][$setting->name] = $setting->value;
        }
        
        if(isset($app->params['ANDACMS']['general'])){
            $this->setGeneral($app);
        }

    }

    private function setGeneral($app)
    {
        $settings = $app->params['ANDACMS']['general'];
        $app->name = $settings['title'];
        $app->language = $settings['language'];
        $app->timezone = $settings['timezone'];
        $app->formatter->dateFormat = $settings['dateformat'];
        $app->formatter->timeFormat = $settings['timeformat'];
        $app->view->title = $settings['title'];
        $seos = [
            'description' => $settings['description'],
            'keywords' => $settings['keywords'],
            'content-language' => $settings['language'],
            'content-type' => 'text/html; charset='.$app->charset,
        ];
        foreach ($seos as $key => $seo){
            $app->view->registerMetaTag(['name' => $key, 'content' => $seo], $key);
        }
    }

}