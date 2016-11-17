<?php
namespace anda\cms\modules\calendar\api;

use Yii;
use anda\cms\base\ApiChildModule;

class Calendar extends ApiChildModule
{
    public $modelClass = 'anda\cms\modules\calendar\models\Calendar';

    public $modelSearchClass = 'anda\cms\modules\calendar\models\CalendarSearch';
    
}