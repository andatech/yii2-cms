<?php

namespace anda\cms\modules\page\models;

use Yii;
use anda\cms\base\Model as ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

use wbraganca\behaviors\NestedSetBehavior;
use wbraganca\behaviors\NestedSetQuery;

/**
 * This is the model class for table "{{%web_page}}".
 *
 * @property integer $id
 * @property integer $root
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property integer $status
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property integer $hits
 * @property string $image
 * @property string $published_at
 * @property integer $version
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $deleted_at
 */
class Page extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 2;
    const STATUS_DRAFT = 3;
    const STATUS_ARCHIVED = 4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%'.self::getTablePrefix().'page}}';
    }

    function behaviors()
    {
        return [
            'crop-image' => [
                'class' => \maxmirazh33\image\Behavior::className(),
                'savePathAlias' => $this->masterModule->uploadDir,
                'urlPrefix' => $this->masterModule->uploadUrl,
                'crop' => true,
                'attributes' => [
                    'image' => [
                        'savePathAlias' => $this->masterModule->uploadDir.'/page/',
                        'urlPrefix' => $this->masterModule->uploadUrl.'/page/',
                        'width' => 200,
                        'height' => 100,
                    ],
                ],
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
            ],
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ],
            'published_at' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['published_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['published_at'],
                ],
                'value' => function () {
                    return $this->verifyDateTime($this->published_at);
                },
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
            [['root', 'lft', 'rgt', 'level', 'status', 'hits', 'version', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['status', 'title', ], 'required'],
            [['content'], 'string'],
            [['published_at', 'deleted_at'], 'safe'],
            [['title', 'slug'], 'string', 'max' => 512],
            [['meta_title'], 'string', 'max' => 128],
            [['meta_keywords', 'meta_description'], 'string', 'max' => 255],
            [['version'], 'default', 'value' => 1],
            [['hits'], 'default', 'value' => 0],
//            [['published_at'], 'default', 'value' => Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d H:i:s')],
            [['image'], 'file', 'extensions' => 'jpg, jpeg, gif, png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'root' => 'Root',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'level' => 'Level',
            'status' => 'Status',
            'title' => 'Title',
            'slug' => 'Slug',
            'content' => 'Content',
            'hits' => 'Hits',
            'image' => 'Image',
            'published_at' => 'Published At',
            'version' => 'Version',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'deleted_at' => 'Deleted At',
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



    public static function getStatuses($trash = true)
    {
        $arr = [
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_UNPUBLISHED => 'Unpublished',
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ARCHIVED => 'Archived',
            self::STATUS_DELETED => 'Deleted',
        ];
        if (!$trash){unset($arr[0]);}

        return $arr;
    }

    public function getStatusText()
    {
        return self::getStatuses()[$this->status];
    }

    public function getStatusLabel()
    {
        $text = $this->statusText;
        switch ($this->status){
            case self::STATUS_PUBLISHED : $type = 'primary'; break;
            case self::STATUS_UNPUBLISHED : $type = 'warning'; break;
            case self::STATUS_DRAFT : $type = 'info'; break;
            case self::STATUS_ARCHIVED : $type = 'success'; break;
            case self::STATUS_DELETED : $type = 'danger'; break;
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



    public function afterFind()
    {
        parent::afterFind();

        //$format = WidgetSettings::DateTimePicker()['pluginOptions']['format'];
        $format_db = 'Y-m-d H:i:s';
        $format_ui = 'd/m/Y H:i:s';
        $this->published_at = \DateTime::createFromFormat($format_db, $this->published_at)->format($format_ui);
    }

    public function beforeSave($insert)
    {
        if(!$insert){
            $version = [
                'old' => intval($this->oldAttributes['version']),
                'new' => intval($this->version),
            ];
            if($version['old'] === $version['new']){
                $this->version += 1;
            }
        }

        return parent::beforeSave($insert);

    }


    protected function verifyDateTime($date)
    {
        if(empty($date) || $date === null) {
            $dateTime = new \DateTime();
        }else{
            $dateTime = \DateTime::createFromFormat('d/m/Y H:i:s', $date);
        }
        return $dateTime->format('Y-m-d H:i:s');
    }

    public function getDateTimeNow()
    {
        $date = new \DateTime();
        return $date->format('d-m-Y H:i:s');
    }
}
