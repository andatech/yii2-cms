<?php

namespace anda\cms\modules\category\controllers;

use Yii;
use anda\cms\modules\category\models\Category;
use anda\cms\modules\category\models\CategorySearch;
use anda\cms\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Category model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $searchModel = new CategorySearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
        $treeArray = Category::find()->dataFancytree();
        return $this->render('index', ['treeArray' => $treeArray]);
    }

    public function actionMoveNode($id, $mode, $targetId)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model = $this->findModel($id);
            $target = $this->findModel($targetId);
            switch ($mode){
                case 'over': $model->moveAsLast($target);
                    break;
                case 'before': $model->moveBefore($target);
                    break;
                case 'after': $model->moveAfter($target);
                    break;
            }
            $result = ['process' => true, 'message' => 'Move '.$model->id.' to '.$target->id.' has success..', 'mode' => $mode];
            echo \yii\helpers\Json::encode($result);
            return;
            //return $this->redirect(['tree', 'id' => $model->id]);
        }
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' Category.
     * @return mixed
     */
    public function actionCreate($parent_id=null)
    {
        $model = new Category();
        $treeArray = Category::find()->dataFancytree();

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if($parent_id === null){
                $model->saveNode();
            }else{
                $root = $this->findModel(intval($parent_id));
                $model->appendTo($root);
            }
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            if (Yii::$app->request->isAjax){
                return $this->renderAjax('create', [
                    'model' => $model,
                    'treeArray' => $treeArray,
                ]);
            }
            return $this->render('create', [
                'model' => $model,
                'treeArray' => $treeArray,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' Category.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            $model->saveNode();
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            if(Yii::$app->request->isAjax){
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' Category.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteNode();

        return $this->redirect(['index']);
    }

    public function actionTree($id=null, $mode=null, $targetId=null)
    {
        if (Yii::$app->request->isAjax && $id !== null) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model = $this->findModel($id);
            $target = $this->findModel($targetId);
            switch ($mode){
                case 'over': $model->moveAsLast($target);
                    break;
                case 'before': $model->moveBefore($target);
                    break;
                case 'after': $model->moveAfter($target);
                    break;
            }
            $result = ['process' => true, 'message' => 'Move '.$model->id.' to '.$target->id.' has success..', 'mode' => $mode];
            echo \yii\helpers\Json::encode($result);
            return;
            //return $this->redirect(['tree', 'id' => $model->id]);
        }
        $treeArray = Category::find()->dataFancytree();
        return $this->render('tree', ['treeArray' => $treeArray]);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested Category does not exist.');
        }
    }
}
