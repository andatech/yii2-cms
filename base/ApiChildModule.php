<?php

namespace anda\cms\base;

use Yii;
use yii\base\Object;
use yii\web\NotFoundHttpException;
use anda\cms\modules\category\models\Category;

class ApiChildModule extends Object
{
    public $modelClass = 'anda\cms\modules\post\models\Post';

    public $modelSearchClass = 'anda\cms\modules\post\models\PostSearch';

    public $moduleId;

    public $_searchModel;


    public function last($limit=5)
    {
        return $this->find()->limit($limit)->orderBy(['id' => SORT_DESC])->all();
    }

    public function get($id)
    {
        $model = $this->find()->andWhere([$this->getTableName().'.id' => $id])->one();
        if (is_null($model)){
            throw new NotFoundHttpException('The requested page does not exist.');
        } else {
            return $model;
        }
    }

    public function view($id, $counter = true)
    {
        $model = $this->get($id);
        if ($counter) {
            $model->updateCounters(['hits' => 1]);
        }

        return $model;
    }











    public function getTableName()
    {
        $modelClass = $this->modelClass;
        return $modelClass::tableName();
    }

    public function getAncestors($id)
    {
        $model = Category::findOne($id);
        return $model->ancestors()->all();
    }

    public function getCategoryRoots()
    {
        return Category::find()->roots()->all();
    }

    protected function getItemRoot()
    {
        $roots = [];
        foreach ($this->getCategoryRoots() as $root){
            $roots[] = $root->attributes;
        }

        $key = array_search($this->moduleId, array_column($roots, 'name'));

        if (is_int($key)) {
            return $roots[$key]['root'];
        }
        return false;
    }

    public function getSearchModel()
    {
        $modelSearchClass = $this->modelSearchClass;
        $this->_searchModel = new $modelSearchClass();

        return $this->_searchModel;
    }

    public function getDataProvider()
    {

        $itemRoot = $this->getItemRoot();

        $params = Yii::$app->request->queryParams;

        $dataProvider = $this->_searchModel->search($params);

        if ($itemRoot !== false) {
            $dataProvider->query->andFilterWhere(['root' => $itemRoot]);
        }

        return $dataProvider;
    }

    public function find()
    {
        $itemRoot = $this->getItemRoot();
        $modelClass = $this->modelClass;
        if ($itemRoot !== false) {
            return $modelClass::find()->andWhere(['root' => $itemRoot]);
        }
        return $modelClass::find();
    }
}