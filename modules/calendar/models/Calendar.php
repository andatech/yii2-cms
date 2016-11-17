<?php

namespace anda\cms\modules\calendar\models;

use anda\cms\modules\post\models\Post as ActiveRecord;

class Calendar extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'status', 'title', 'content', 'publish_up', 'publish_down'], 'required'],
            [['category_id', 'status', 'hits', 'version', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['introtext', 'content'], 'string'],
            [['slug', 'introtext', 'published_at', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_at'], 'safe'],
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