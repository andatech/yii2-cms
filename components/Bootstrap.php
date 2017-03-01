<?php

namespace anda\cms\components;


use anda\cms\models\Setting;
use anda\cms\helpers\Data;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
//        $model = Setting::find()->all();
//        foreach ($model as $section){
//            $settings[$section->type][$section->name] = $section->value;
//        }

        $settings = Data::cache(Setting::CACHE_KEY, 3600, function() {
            $model = Setting::find()->all();
            $settings = [];
            foreach ($model as $section){
                $settings[$section->type][$section->name] = $section->value;
            }

            return $settings;
        });

        $general = $settings['general'];
        $app->name = $general['title'];
        $app->language = $general['language'];
        $app->timezone = $general['timezone'];
        $app->formatter->dateFormat = $general['dateformat'];
        $app->formatter->timeFormat = $general['timeformat'];
        $app->view->title = $general['title'];
        $seos = [
            'description' => $general['description'],
            'keywords' => $general['keywords'],
            'content-language' => $general['language'],
            'content-type' => 'text/html; charset='.$app->charset,
        ];
        foreach ($seos as $key => $seo){
            $app->view->registerMetaTag(['name' => $key, 'content' => $seo], $key);
        }
    }
}