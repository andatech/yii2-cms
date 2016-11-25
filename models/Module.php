<?php

namespace anda\cms\models;

use Yii;
use wowkaster\serializeAttributes\SerializeAttributesBehavior;
use anda\cms\helpers\Data;

/**
 * This is the model class for table "{{%web_module}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $class
 * @property string $icon
 * @property string $settings
 * @property integer $order_num
 * @property integer $status
 */
class Module extends \anda\cms\base\Model
{
    /**
     * @inheritdoc
     */
    const CACHE_KEY = 'andacmsModules';

    const STATUS_ON = 1;

    const STATUS_OFF = 0;
    
    public static function tableName()
    {
        return '{{%'.self::getTablePrefix().'module}}';
    }

    public function behaviors()
    {
        return [
            'serialize' => [
                'class' => SerializeAttributesBehavior::className(),
                'convertAttr' => ['settings' => 'json']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'title', 'class', 'icon', 'order_num', 'status'], 'required'],
//            [['settings'], 'string'],
            [['order_num', 'status'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['title', 'class', 'icon'], 'string', 'max' => 128],
            [['settings'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'title' => 'Title',
            'class' => 'Class',
            'icon' => 'Icon',
            'settings' => 'Settings',
            'order_num' => 'Order Num',
            'status' => 'Status',
        ];
    }

    /**
     * @return array
     */
    public static function findAllActive(){
//        $result = [];
//        foreach (self::find()->where(['status' => self::STATUS_ON])->orderBy('order_num')->all() as $module) {
//            $result[$module->name] = (object)$module->attributes;
//        }
//
//        return $result;


        $result = Data::cache(self::CACHE_KEY, 3600, function() {
            $result = [];
            foreach (self::find()->where(['status' => self::STATUS_ON])->orderBy('order_num')->all() as $module) {
                $result[$module->name] = (object)$module->attributes;
            }

            return $result;
        });

        return $result;
    }
}
