<?php
namespace anda\cms\modules\category\api;

use Yii;
use anda\cms\base\ApiChildModule;

class Category extends ApiChildModule
{
    public $modelClass = 'anda\cms\modules\category\models\Category';

    public $modelSearchClass = 'anda\cms\modules\category\models\CategorySearch';
}