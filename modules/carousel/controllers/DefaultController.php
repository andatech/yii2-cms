<?php
namespace anda\cms\modules\carousel\controllers;

use Yii;
use anda\cms\modules\category\models\Category;
use anda\cms\modules\post\controllers\DefaultController as Controller;

/**
 * Default controller for the `carousel` module
 */
class DefaultController extends Controller
{

    public $modelClass = 'anda\cms\modules\carousel\models\Carousel';
    
    public $modelSearchClass = 'anda\cms\modules\carousel\models\CarouselSearch';

    public function beforeAction($action)
    {
        $myCategory = Category::find()->where(['name' => $this->module->id])->roots()->one();
        if (is_null($myCategory)){
            return $this->redirect(['category/create']);
        }else{
            if (!isset($this->module->settings['categoryRoot'])) {
                $model = \anda\cms\models\Module::findOne(['name' => $this->module->id]);
                if(is_array($model->settings)) {
                    $model->settings = array_merge($model->settings, ['categoryRoot' => $myCategory->root]);
                }else{
                    $model->settings = ['categoryRoot' => $myCategory->root];
                }
                $model->save();
            }
        }

        return parent::beforeAction($action);
    }



    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'treeArray' => $this->treeArray
            ]);
        }
    }
}
