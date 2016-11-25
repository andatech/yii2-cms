<?php
namespace anda\cms\modules\menu\api;

use Yii;
use anda\cms\base\ApiChildModule;
use anda\cms\helpers\Data;

class Menu extends ApiChildModule
{
    public $modelClass = 'anda\cms\modules\menu\models\Menu';

    public $modelSearchClass = 'anda\cms\modules\menu\models\MenuSearch';

    public $_model;

    public function init()
    {
        parent::init();

        if (is_null($this->_model)){
            $this->find();
        }
    }

    public function getDataFancytree($root)
    {
        $data = $this->_model->dataFancytree($root);
        $result = [];
        if(count($data) > 0) {
            $result = $data[0];
        }

        return $result;
    }

    public function getDataMenu($root = 0, $level = null, $action = [])
    {
        $result = Data::cache('andacmsMenu'.$root, 3600, function() use ($root, $level, $action){
            $items = [];
            $data = $this->_model->dataMenu($root, $level, $action);
            if (count($data) > 0 && isset(current($data)['items'])) {
                $items = current($data)['items'];
            }

            return $items;
        });

        return $result;
    }

    public function find()
    {
        $modelClass = $this->modelClass;
        $this->_model = $modelClass::find();

        return $this->_model;
    }
}