<?php
namespace anda\cms\modules\calendar\controllers;

use Yii;
use anda\cms\base\Controller;
use anda\cms\modules\calendar\models\Setting;

class SettingController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    private function findModel($name)
    {
        $model = Setting::findOne(['name' => $name]);
        return $model;
    }
}