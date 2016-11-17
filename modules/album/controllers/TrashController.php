<?php

namespace anda\cms\modules\album\controllers;



use Yii;
use anda\cms\base\Controller;
use yii\web\NotFoundHttpException;
use anda\cms\modules\album\models\Album;
use anda\cms\modules\album\models\AlbumTrashSearch;

class TrashController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new AlbumTrashSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEmpty()
    {
//        Album::deleteAll('status = :status', [':status' => 0]);
        $model = Album::find()->where(['!=', 'deleted_at', !null])->all();
        foreach ($model as $record){
            $record->delete();
        }

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
     * Finds the Album model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Album the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Album::find()->where(['!=', 'deleted_at', !null])->andWhere(['id'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}