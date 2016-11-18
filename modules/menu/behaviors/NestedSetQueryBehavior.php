<?php

namespace anda\cms\modules\menu\behaviors;

use wbraganca\behaviors\NestedSetQueryBehavior as Behavior;
use yii\helpers\Url;

/**
 * @author Wanderson Bragança <wanderson.wbc@gmail.com>
 */
class NestedSetQueryBehavior extends Behavior
{
    private $_replacer;

    public function dataMenu($root = 0, $level = null, $replacer = [])
    {
        $this->_replacer = $replacer;
        $data = array_values($this->prepareData2Menu($root, $level));
        return $this->makeData2Menu($data);
    }

    private function prepareData2Menu($root = 0, $level = null)
    {
        $res = [];
        if (is_object($root)) {
            switch ($root->{$root->typeAttribute}){
                case $root::TYPE_MODULE :
                    $replaced = (array_key_exists($root->{$root->moduleIdAttribute}, $this->_replacer)) ? $this->_replacer[$root->{$root->moduleIdAttribute}] : $root->{$root->moduleIdAttribute};
                    if (!strpos($replaced, '/')) {
                        $replaced .= '/view';
                    }
                    $url = Url::to([$replaced, 'id' => $root->{$root->moduleRecordIdAttribute}]);

                    break;
                case $root::TYPE_NONE : $url = '#!'; break;
                case $root::TYPE_URL : $url = $root->{$root->urlAttribute}; break;
                default : $url = '#!'; break;
            }
            $res[$root->{$root->idAttribute}] = [
                'id' => $root->{$root->idAttribute},
                'label' => $root->{$root->titleAttribute},
                'url' => $url,
                'linkOptions' => ''
            ];

            if ($level) {
                foreach ($root->children()->all() as $childRoot) {
                    $aux = $this->prepareData2Menu($childRoot, $level - 1);

                    if (isset($res[$root->{$root->idAttribute}]['items']) && !empty($aux)) {
                        $res[$root->{$root->idAttribute}]['folder'] = true;
                        $res[$root->{$root->idAttribute}]['items'] += $aux;

                    } elseif(!empty($aux)) {
                        $res[$root->{$root->idAttribute}]['folder'] = true;
                        $res[$root->{$root->idAttribute}]['items'] = $aux;
                    }
                }
            } elseif (is_null($level)) {
                foreach ($root->children()->all() as $childRoot) {
                    $aux = $this->prepareData2Menu($childRoot, null);
                    if (isset($res[$root->{$root->idAttribute}]['items']) && !empty($aux)) {
                        $res[$root->{$root->idAttribute}]['folder'] = true;
                        $res[$root->{$root->idAttribute}]['items'] += $aux;

                    } elseif(!empty($aux)) {
                        $res[$root->{$root->idAttribute}]['folder'] = true;
                        $res[$root->{$root->idAttribute}]['items'] = $aux;
                    }
                }
            }
        } elseif (is_scalar($root)) {
            if ($root == 0) {
                foreach ($this->roots()->all() as $rootItem) {
                    if ($level) {
                        $res += $this->prepareData2Menu($rootItem, $level - 1);
                    } elseif (is_null($level)) {
                        $res += $this->prepareData2Menu($rootItem, null, $action);
                    }
                }
            } else {
                $modelClass = $this->owner->modelClass;
                $model = new $modelClass;
                $root = $modelClass::find()->andWhere([$model->idAttribute => $root])->one();
                if ($root) {
                    $res += $this->prepareData2Menu($root, $level);
                }
                unset($model);
            }
        }
        return $res;
    }

    private function makeData2Menu(&$data)
    {
        $tree = [];
        foreach ($data as $key => &$item) {
            if (isset($item['children'])) {
                $item['children'] = array_values($item['items']);
                $tree[$key] = $this->makeData2Menu($item['items']);
            }
            $tree[$key] = $item;
        }
        return $tree;
    }
}
