<?php

namespace anda\cms\base;

use Yii;

class Model extends \yii\db\ActiveRecord
{

    public static function getTablePrefix()
    {
        return Yii::$app->params['ANDACMS']['TABLE_PREFIX'];
    }

    public function getMasterModule()
    {
        return Yii::$app->getModule(Yii::$app->params['ANDACMS']['MASTER_MODULE_ID'], false);
    }
}