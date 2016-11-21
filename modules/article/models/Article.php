<?php
namespace anda\cms\modules\article\models;

use anda\cms\modules\post\models\Post as ActiveRecord;

class Article extends ActiveRecord
{
    public $myName = 'article';
}