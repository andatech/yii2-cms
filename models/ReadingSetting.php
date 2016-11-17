<?php

namespace anda\cms\models;

use Yii;
use yii\helpers\ArrayHelper;

class ReadingSetting extends \yii\base\Model
{
    public $page_size;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [['page_size'], 'integer'],
                [['page_size'], 'safe'],
            ]);
    }

    public function attributeLabels()
    {
        return [
            'page_size' => Yii::t('app', 'Page Size'),
        ];
    }
}