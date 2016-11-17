<?php

namespace anda\cms\modules\category\models;

use Yii;
use anda\cms\base\Model as ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
//use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

use wbraganca\behaviors\NestedSetBehavior;
use wbraganca\behaviors\NestedSetQuery;

//use anda\cms\modules\post\models\Post;
use anda\cms\modules\post\models\PostSearch;

/**
 * This is the model class for table "{{%web_category}}".
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
class Category extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%'.self::getTablePrefix().'category}}';
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
                        'savePathAlias' => $this->masterModule->uploadDir.'/category/',
                        'urlPrefix' => $this->masterModule->uploadUrl.'/category/',
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
            [['root', 'lft', 'rgt', 'level', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['status', 'name', 'title', ], 'required'],
            [['title', 'slug'], 'string', 'max' => 512],
            [['name', 'meta_title'], 'string', 'max' => 128],
            [['meta_keywords', 'meta_description'], 'string', 'max' => 255],
            ['name', 'unique'],
            ['name', 'match', 'pattern' => '/^[a-z]\w*$/i', 'message' => Yii::t('andacms/module', 'Please choose an a-z, 0-9 (sample: abc123)')],
            [['image'], 'file', 'extensions' => 'jpg, jpeg, gif, png'],
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
            'name' => Yii::t('andacms/module', 'Name'),
            'title' => Yii::t('andacms/module', 'Title'),
            'slug' => Yii::t('andacms/module', 'Slug'),
            'image' => Yii::t('andacms/module', 'Image'),
            'created_by' => Yii::t('andacms/module', 'Created By'),
            'created_at' => Yii::t('andacms/module', 'Created At'),
            'updated_by' => Yii::t('andacms/module', 'Updated By'),
            'updated_at' => Yii::t('andacms/module', 'Updated At'),
            'meta_title' => Yii::t('andacms/module', 'Meta Title'),
            'meta_keywords' => Yii::t('andacms/module', 'Meta Keywords'),
            'meta_description' => Yii::t('andacms/module', 'Meta Description'),
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

    public function getPosts()
    {
        return $this->hasMany(PostSearch::className(), ['category_id' => 'id']);
    }



    public static function getStatuses($trash = true)
    {
        $arr = [
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_UNPUBLISHED => 'Unpublished',
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




    public function getDateTimeNow()
    {
        $date = new \DateTime();
        return $date->format('d-m-Y H:i:s');
    }
}
