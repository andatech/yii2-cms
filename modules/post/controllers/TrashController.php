<?php

namespace anda\cms\modules\post\controllers;


use Yii;
use anda\cms\base\Controller;
use yii\web\NotFoundHttpException;
use anda\cms\modules\category\models\Category;

class TrashController extends Controller
{
    public $modelClass = 'anda\cms\modules\post\models\Post';

    public $modelSearchClass = 'anda\cms\modules\post\models\PostTrashSearch';

    public $masterViewsDir = __DIR__.'/../views';


    public function actionIndex()
    {
        $modelSearchClass = $this->modelSearchClass;
        $searchModel = new $modelSearchClass();

        $params = Yii::$app->request->queryParams;

        $dataProvider = $searchModel->search($params);

        if ($this->module->id !== 'post' && isset($this->getCategoryRoot()->id)) {
            $dataProvider->query->andFilterWhere(['root' => $this->getCategoryRoot()->id]);

        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEmpty()
    {
        $modelClass = $this->modelClass;
        if ($this->module->id === 'post'){
            $model = $modelClass::find()->where(['!=', 'deleted_at', !null])->all();
            foreach ($model as $record){
                $record->delete();
            }
        }

//        Yii::$app->end();

        if(!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $success = $model->restore();

        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ($success) ? ['success' => $success] : ['success' => $success, 'message' => 'Cannot restore this item.'];
        }else{
            return $this->redirect(['index']);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if(!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::find()->where(['!=', 'deleted_at', !null])->andWhere([$modelClass::tableName().'.id'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getCategoryRoot()
    {
        if (($model = Category::findOne(['name' => $this->module->id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}