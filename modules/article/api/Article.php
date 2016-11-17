<?php
namespace anda\cms\modules\article\api;

use Yii;
use anda\cms\base\ApiChildModule;

class Article extends ApiChildModule
{
    public $modelClass = 'anda\cms\modules\article\models\Article';

    public $modelSearchClass = 'anda\cms\modules\article\models\ArticleSearch';
    
}