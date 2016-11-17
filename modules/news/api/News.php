<?php
namespace anda\cms\modules\news\api;

use Yii;
use anda\cms\base\ApiChildModule;

class News extends ApiChildModule
{
    public $modelClass = 'anda\cms\modules\news\models\News';

    public $modelSearchClass = 'anda\cms\modules\news\models\NewsSearch';
    
}