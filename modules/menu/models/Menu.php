<?php

namespace anda\cms\modules\menu\models;

use Yii;
use anda\cms\base\Model as ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
//use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

//use wbraganca\behaviors\NestedSetBehavior;
//use wbraganca\behaviors\NestedSetQuery;
use anda\cms\modules\menu\behaviors\NestedSetBehavior;
use anda\cms\modules\menu\behaviors\NestedSetQuery;

//use anda\cms\modules\post\models\Post;
use anda\cms\modules\post\models\PostSearch;

/**
 * This is the model class for table "{{%web_menu}}".
 *
 * @property integer $id
 * @property integer $root
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property integer $status
 * @property string $title
 * @property integer $type
 * @property integer $url
 * @property integer $module_id
 * @property string $module_record_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 */
class Menu extends ActiveRecord
{
    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    const TYPE_NONE = 0;
    const TYPE_MODULE = 1;
    const TYPE_URL = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%'.self::getTablePrefix().'menu}}';
    }

    function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
            ],
            'nestedsets' => [
                'class' => NestedSetBehavior::className(),
                // 'rootAttribute' => 'root',
                // 'levelAttribute' => 'level',
                // 'hasManyRoots' => true
            ],
        ];
    }

    public static function find()
    {
        return new NestedSetQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['root', 'lft', 'rgt', 'level', 'status', 'type', 'module_record_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['status', 'title', 'type'], 'required'],
            [['title'], 'string', 'max' => 512],
//            [['url'], 'url', 'defaultScheme' => 'http'],
            [['url', 'module_id', 'module_record_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('andacms/module', 'ID'),
            'root' => Yii::t('andacms/module', 'Root'),
            'lft' => Yii::t('andacms/module', 'Lft'),
            'rgt' => Yii::t('andacms/module', 'Rgt'),
            'level' => Yii::t('andacms/module', 'Level'),
            'status' => Yii::t('andacms/module', 'Status'),
            'title' => Yii::t('andacms/module', 'Title'),
            'type' => Yii::t('andacms/module', 'Type'),
            'url' => Yii::t('andacms/module', 'Url'),
            'module_id' => Yii::t('andacms/module', 'Module Id'),
            'module_record_id' => Yii::t('andacms/module', 'Module Record Id'),
            'created_by' => Yii::t('andacms/module', 'Created By'),
            'created_at' => Yii::t('andacms/module', 'Created At'),
            'updated_by' => Yii::t('andacms/module', 'Updated By'),
            'updated_at' => Yii::t('andacms/module', 'Updated At'),
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(Yii::$app->user->identity->className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(Yii::$app->user->identity->className(), ['id' => 'updated_by']);
    }



    public static function getStatuses()
    {
        $arr = [
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_UNPUBLISHED => 'Unpublished',
        ];

        return $arr;
    }

    public function getStatusText()
    {
        return self::getStatuses()[$this->status];
    }

    public function getStatusLabel()
    {
        $text = $this->getStatusText();
        switch ($this->status){
            case self::STATUS_PUBLISHED : $type = 'primary'; break;
            case self::STATUS_UNPUBLISHED : $type = 'danger'; break;
            default: $type = 'default'; break;
        }
        return '<span class="label label-'.$type.'">'.$text.'</span>';
    }

    public function getItemList($disableIds = [])
    {
        if(is_array($disableIds) && count($disableIds) > 0 && current($disableIds) !== null){
            $model = self::find()->where(['not in', 'id', $disableIds])->all();
        }else{
            $model = self::find()->all();
        }

        return ArrayHelper::map($model, 'id', 'title');
    }

    public function getParentsText($glue=' / ', $field='title')
    {
        $parents = $this->parent()->all();
//        $parents = $this->ancestors()->all();
        $arr = [];
        foreach ($parents as $parent){
            $arr[] = $parent->$field;
        }
        //$arr[] = 'Root';
        if(count($arr) > 0){
            krsort($arr);
            return implode($glue, $arr);
        }else{
            return '<span class="not-set">'.Yii::t('yii', '(not set)').'</span>';
        }
    }

    public static function getTypeList($useNone = false)
    {
        $list = [
            self::TYPE_NONE => 'None',
            self::TYPE_MODULE => 'Module',
            self::TYPE_URL => 'Url',
        ];
        if (!$useNone) {
            unset($list[self::TYPE_NONE]);
        }

        return $list;
    }




    public function getDateTimeNow()
    {
        $date = new \DateTime();
        return $date->format('d-m-Y H:i:s');
    }
}
