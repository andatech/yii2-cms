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

    public function view($id, $seo = true, $counter = true)
    {
        $model = $this->get($id);
        if ($counter) { $model->updateCounters(['hits' => 1]);}
        if($seo) { $this->setItemSeo($model); }

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

    public function setItemSeo($model)
    {
        $view = Yii::$app->getView();

        $seos['title'] = (empty($model->meta_title) || is_null($model->meta_title)) ? $model->title : $model->meta_title;
        $seos['keywords'] = (empty($model->meta_keywords) || is_null($model->meta_keywords)) ? Yii::$app->name : $model->meta_keywords.', '.Yii::$app->name;
        $seos['description'] = (empty($model->meta_description) || is_null($model->meta_description)) ? $model->getContentPreview() : $model->meta_description;
        $seos['og:url'] = Yii::$app->request->absoluteUrl;
        $seos['og:title'] = $seos['title'];
        $seos['og:description'] = $seos['description'];
        $seos['og:type'] = 'website';
        $seos['og:image'] = $model->getImageUrl('image', false);
        $seos['og:site_name'] = Yii::$app->name;

        foreach ($seos as $key => $seo) {
            if (!is_null($seo)) {
                $view->registerMetaTag(['name' => $key, 'content' => $seo], $key);
            }
        }

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