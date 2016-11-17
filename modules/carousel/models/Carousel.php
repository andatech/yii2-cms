<?php

namespace anda\cms\modules\carousel\models;

use anda\cms\modules\post\models\Post as ActiveRecord;

class Carousel extends ActiveRecord
{
    function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = array_merge($behaviors, [
            'crop-image' => [
                'class' => \maxmirazh33\image\Behavior::className(),
                'savePathAlias' => $this->masterModule->uploadDir,
                'urlPrefix' => $this->masterModule->uploadUrl,
                'crop' => true,
                'attributes' => [
                    'image' => [
                        'savePathAlias' => $this->masterModule->uploadDir.'/carousel/',
                        'urlPrefix' => $this->masterModule->uploadUrl.'/carousel/',
//                        'width' => 750,
//                        'height' => 264,
                    ],
                ],
            ],
        ]);

        return $behaviors;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'status', 'title', 'publish_up', 'publish_down'], 'required'],
            ['image', 'required', 'on' => 'create'],
            [['category_id', 'status', 'hits', 'version', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['introtext', 'content'], 'string'],
            [['slug', 'introtext', 'content', 'published_at', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_at'], 'safe'],
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
}