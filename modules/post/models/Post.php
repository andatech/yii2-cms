<?php

namespace anda\cms\modules\post\models;

use Yii;
use anda\cms\base\Model as ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "{{%web_post}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $status
 * @property string $title
 * @property string $slug
 * @property string $introtext
 * @property string $content
 * @property integer $hits
 * @property string $image
 * @property string $published_at
 * @property string $publish_up
 * @property string $publish_down
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
class Post extends ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 0;
    const STATUS_DRAFT = 2;
    const STATUS_ARCHIVED = 3;

    public $myName = 'post';

    public $globalSearch;
//    public $module = null;


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
                        'savePathAlias' => $this->masterModule->uploadDir.'/'.$this->myName.'/',
                        'urlPrefix' => $this->masterModule->uploadUrl.'/'.$this->myName.'/',
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
            'publish_up' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['publish_up'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['publish_up'],
                ],
                'value' => function () {
                    return $this->verifyDateTime($this->publish_up);
                },
            ],
            'publish_down' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['publish_down'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['publish_down'],
                ],
                'value' => function () {
                    return $this->verifyDateTime($this->publish_down);
                },
            ],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'deleted_at' => date('Y-m-d H:i:s')
                ],
                'restoreAttributeValues' => [
                    'deleted_at' => null
                ]
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%'.self::getTablePrefix().'post}}';
    }

    public static function find()
    {
        return parent::find()
            ->joinWith(['category', 'createdBy.profile'])
            ->where(['deleted_at' => null]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'status', 'title', 'content'], 'required'],
            [['category_id', 'status', 'hits', 'version', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['introtext', 'content'], 'string'],
            [['slug', 'introtext', 'published_at', 'created_by', 'created_at', 'updated_by', 'updated_at', 'publish_up', 'publish_down', 'deleted_at', 'globalSearch'], 'safe'],
            [['title', 'slug'], 'string', 'max' => 512],
            [['meta_title'], 'string', 'max' => 128],
            [['hits'], 'default', 'value' => 0],
            [['version'], 'default', 'value' => 1],
            [['published_at'], 'default', 'value' => date('d/m/Y H:i:s')],
            [['deleted_at'], 'default', 'value' => null],
            [['meta_keywords', 'meta_description'], 'string', 'max' => 255],
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
            'category_id' => Yii::t('andacms/module', 'Category'),
            'status' => Yii::t('andacms/module', 'Status'),
            'title' => Yii::t('andacms/module', 'Title'),
            'slug' => Yii::t('andacms/module', 'Slug'),
            'introtext' => Yii::t('andacms/module', 'Introtext'),
            'content' => Yii::t('andacms/module', 'Content'),
            'hits' => Yii::t('andacms/module', 'Hits'),
            'image' => Yii::t('andacms/module', 'Image'),
            'published_at' => Yii::t('andacms/module', 'Published At'),
            'publish_up' => Yii::t('andacms/module', 'Publish Up'),
            'publish_down' => Yii::t('andacms/module', 'Publish Down'),
            'version' => Yii::t('andacms/module', 'Version'),
            'created_by' => Yii::t('andacms/module', 'Created By'),
            'created_at' => Yii::t('andacms/module', 'Created At'),
            'updated_by' => Yii::t('andacms/module', 'Updated By'),
            'updated_at' => Yii::t('andacms/module', 'Updated At'),
            'meta_title' => Yii::t('andacms/module', 'Meta Title'),
            'meta_keywords' => Yii::t('andacms/module', 'Meta Keywords'),
            'meta_description' => Yii::t('andacms/module', 'Meta Description'),
            'deleted_at' => Yii::t('andacms/module', 'Deleted At'),
            'globalSearch' => Yii::t('andacms/module', 'Search'),
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
            case self::STATUS_UNPUBLISHED : $type = 'danger'; break;
            case self::STATUS_DRAFT : $type = 'warning'; break;
            case self::STATUS_ARCHIVED : $type = 'info'; break;
            default: $type = 'default'; break;
        }
        return '<span class="label label-'.$type.'">'.$text.'</span>';
    }

    public function getCategory()
    {
        return $this->hasOne(\anda\cms\modules\category\models\Category::className(), ['id' => 'category_id']);
    }



    public function afterFind()
    {
        parent::afterFind();
        //$format = WidgetSettings::DateTimePicker()['pluginOptions']['format'];
        $format_db = 'Y-m-d H:i:s';
        $format_ui = 'd/m/Y H:i:s';
        $this->published_at = \DateTime::createFromFormat($format_db, $this->published_at)->format($format_ui);
        $this->publish_up = ($this->publish_up !== null) ? \DateTime::createFromFormat($format_db, $this->publish_up)->format($format_ui) : null;
        $this->publish_down = ($this->publish_down !== null) ? \DateTime::createFromFormat($format_db, $this->publish_down)->format($format_ui) : null;

//        print_r(WidgetSettings::DateTimePicker()['pluginOptions']['format']);
    }


    protected function verifyDateTime($date)
    {
//        var_dump($date); Yii::$app->end();
        if(empty($date) || is_null($date)){
            return null;
        }else {
            $dateTime = \DateTime::createFromFormat('d/m/Y H:i:s', $date);
            return $dateTime->format('Y-m-d H:i:s');
        }
    }


    public function getImageUrl($attr)
    {
        $imageBehavior = $this->getBehavior('crop-image')->attributes[$attr];
        $filename = $imageBehavior['savePathAlias'].$this->{$attr};
        if(is_file($filename)) {
            return parent::getImageUrl($attr);
        }

        $clientsPath = Yii::$app->assetManager->getPublishedUrl('@anda/cms/clients');
        return $clientsPath.'/images/image-none.jpg';
    }

    public function getContentPreview()
    {
        if (!empty($this->introtext)){
            return $this->introtext;
        }
        $strlenLimit = 500;
        $text = \yii\helpers\HtmlPurifier::process($this->content);
        $text = strip_tags($text);
        $text = trim($text);
        $text = preg_replace('~[\r\n\t]+~', '', $text);
        if(strlen($text) > $strlenLimit){
            $text = substr($text, 0, $strlenLimit).'...';
        }


        return $text;
    }
}
