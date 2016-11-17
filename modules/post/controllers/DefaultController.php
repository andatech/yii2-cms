<?php

namespace anda\cms\modules\post\controllers;

use Yii;
//use anda\cms\modules\post\models\Post;
//use anda\cms\modules\post\models\PostSearch;
use anda\cms\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\InvalidCallException;
use yii\helpers\Json;
use yii\helpers\Url;
use anda\cms\modules\category\models\Category;

/**
 * DefaultController implements the CRUD actions for Post model.
 */
class DefaultController extends Controller
{
    public $modelClass = 'anda\cms\modules\post\models\Post';

    public $modelSearchClass = 'anda\cms\modules\post\models\PostSearch';

//    public $categoryName = null;

    public $masterViewsDir = __DIR__.'/../views';
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
     * Lists all Post models.
     * @return mixed
     */
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

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'treeArray' => $this->getTreeArray()
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->updateCounters(['version' => 1]);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'treeArray' => $this->getTreeArray()
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    public function actionDelete() {
        $id = null;
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && $post) {
            $id = $post['id'];
        }elseif(Yii::$app->request->get('id')){
            $id = Yii::$app->request->get('id');
        }

        if($id !== null){
            $model = $this->findModel($id);
            if ($model) {
                $model->softDelete();
                echo Json::encode([
                    'success' => true,
                    'messages' => [
                        'kv-detail-info' => 'The book # ' . $id . ' was successfully deleted. <a href="' .
                            Url::to(['index']) . '" class="btn btn-sm btn-info">' .
                            '<i class="glyphicon glyphicon-hand-right"></i>  Click here</a> to proceed.'
                    ]
                ]);
            } else {
                echo Json::encode([
                    'success' => false,
                    'messages' => [
                        'kv-detail-error' => 'Cannot delete the book # ' . $id . '.'
                    ]
                ]);
            }
            return;
        }
        throw new InvalidCallException("You are not allowed to do this operation. Contact the administrator.");
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
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getCategoryRoot()
    {
//        if (($model = Category::findOne(['name' => $this->module->id])) !== null) {
//            return $model;
//        } else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
        return Category::findOne(['name' => $this->module->id]);
    }

    protected function getTreeArray()
    {
        if ($this->module->id === 'post'){
            $treeArray = Category::find()->dataFancytree();
        }else{
            $category = Category::find()->dataFancytree($this->getCategoryRoot()->id);
            $treeArray = (isset(current($category)['children'])) ? current($category)['children'] : []; //เอา Node root ออกให้หลือแต่รายการภายใน Node
        }

        return $treeArray;
    }
}
