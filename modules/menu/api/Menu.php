<?php
namespace anda\cms\modules\menu\api;

use Yii;
use anda\cms\base\ApiChildModule;

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
        $data = $this->_model->dataMenu($root, $level, $action);
        $result = []; //current($data);
        if (count($data) > 0 && isset(current($data)['items'])) {
            $result = current($data)['items'];
        }
//        if(isset($data[0]) && isset($data[0]['items'])) {
//            $result = $data[0]['items'];
//        }

        return $result;
    }

    public function find()
    {
        $modelClass = $this->modelClass;
        $this->_model = $modelClass::find();

        return $this->_model;
    }
}