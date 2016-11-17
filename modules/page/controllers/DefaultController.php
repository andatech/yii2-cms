<?php

namespace anda\cms\modules\page\controllers;

use Yii;
use anda\cms\modules\page\models\Page;
use anda\cms\modules\page\models\PageSearch;
use anda\cms\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Page model.
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
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
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
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();
        $treeArray = Page::find()->dataFancytree();

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            if(intval($post['parentId']) === 0){
                $model->saveNode();
            }else{
                $root = $this->findModel(intval($post['parentId']));
                $model->appendTo($root);
            }
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'treeArray' => $treeArray,
            ]);
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $model->saveNode();
            if ($post['parentIdOld'] !== $post['parentId']){
                if(intval($post['parentId']) === 0){
                    if($model->id !== $model->root) {
                        $model->moveAsRoot();
                    }
                }else{
                    $target = $this->findModel(intval($post['parentId']));
                    $model->moveAsLast($target);
                }
            }

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            $treeArray = Page::find()->dataFancytree();
            return $this->render('update', [
                'model' => $model,
                'treeArray' => $treeArray,
            ]);
        }
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
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
        $treeArray = Page::find()->dataFancytree();
        return $this->render('tree', ['treeArray' => $treeArray]);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
