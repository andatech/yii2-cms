<?php

namespace anda\cms\components;


class ModelSetting extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%'.\Yii::$app->params['ANDACMS']['TABLE_PREFIX'].'setting}}';
    }
}