<?php
namespace anda\cms\modules\album\api;

use Yii;
use anda\cms\base\ApiChildModule;

class Album extends ApiChildModule
{
    public $modelClass = 'anda\cms\modules\album\models\Album';

    public $modelSearchClass = 'anda\cms\modules\album\models\AlbumSearch';
    
}