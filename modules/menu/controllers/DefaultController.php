<?php

namespace anda\cms\modules\menu\controllers;

use anda\cms\modules\category\models\Category;
use Yii;
use anda\cms\modules\menu\models\Menu;
use anda\cms\modules\menu\models\MenuSearch;
use anda\cms\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Menu model.
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex($root=null)
    {
        $menuModel = Menu::find();
        $roots = $menuModel->roots()->all();
        $child = (!is_null($root)) ? $menuModel->dataFancytree($root) : [];
        return $this->render('index', [
            'roots' => $roots,
            'child' => $child
        ]);
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
            return $result;
        }
    }

    /**
     * Displays a single Menu model.
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
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' Menu.
     * @return mixed
     */
    public function actionCreate($parent_id=null)
    {
        $model = new Menu();
        $hints = [
            'module_record_id' => Yii::t('andacms/module', 'Please enter the title {number} or more characters.', ['number' => 3]),
        ];
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
            return $this->redirect(['index', 'root' => $model->root, 'id' => $model->id]);
        } else {
            if (Yii::$app->request->isAjax){
                return $this->renderAjax('create', [
                    'model' => $model,
                    'hints' => $hints,
                ]);
            }
            return $this->render('create', [
                'model' => $model,
                'hints' => $hints,
            ]);
        }
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' Menu.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $hints = [
            'module_record_id' => implode(' » ', $this->getAncestors($model)),
        ];

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            if (Yii::$app->request->isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            $model->saveNode();
            return $this->redirect(['index', 'root' => $model->root, 'id' => $model->id]);
        } else {
            if(Yii::$app->request->isAjax){
                return $this->renderAjax('update', [
                    'model' => $model,
                    'hints' => $hints,
                ]);
            }
            return $this->render('update', [
                'model' => $model,
                'hints' => $hints,
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' Menu.
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
        $treeArray = Menu::find()->dataFancytree();
        return $this->render('tree', ['treeArray' => $treeArray]);
    }


    public function actionModuleRecords($module_id, $q = null, $id = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => []];

        if (!is_null($q)) {
            $modelSearchClass = 'anda\cms\modules\\' . $module_id . '\models\\' . ucfirst($module_id) . 'Search';
            $searchModel = new $modelSearchClass();
            $dataProvider = $searchModel->search([]);
            $dataProvider->query->andFilterWhere(['like', $searchModel::tableName().'.title', $q]);


            if ($this->isNodeTable($searchModel)) { //เช็คว่าตารางนี้เป็นตารางที่มี parent ในตัวหรือไม่
//            echo 'has Root';
            } else {
//            echo 'No......';
                $dataProvider->query->orFilterWhere(['like', $searchModel::tableName().'.content', $q]);
                $category = Category::findOne(['name' => $module_id]);
                if (isset($category->id)) {
                    $dataProvider->query->andFilterWhere(['root' => $category->id]);
                }
            }

            $out['results'] = array_values($dataProvider->getModels());
            $out['get'] = Yii::$app->request->queryParams;
        }
        return $out;

    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested Menu does not exist.');
        }
    }


    protected function isNodeTable($model)
    {
        $arrTableNodeAttr = ['root', 'lft', 'rgt', 'level']; //ตารางที่มี parent เป็นของตัวเองจะมีฟิลด์ชุดนี้อยู่
        $c = 0;
        foreach ($model as $attr => $val) { //วนลูปชื่อฟิลด์ทั้งหมด
            if (in_array($attr, $arrTableNodeAttr)) { //ถ้ามีชื่อฟิลด์ตรงกับตัวไดตัวหนึ่งใน $arrTableNodeAttr ให้ $c เพิ่มค่าขึ้น 1
                $c++;
            }
        }

        if ($c === count($arrTableNodeAttr)) { //ถ้าค่าของ $c เท่ากับจำนวนสมาชิกของ $arrTableNodeAttr แสดงว่าตารางนี้เป็นตารางที่มี parent ในตัวเอง
            return true;
        }

        return false;
    }

    public function getAncestors($model)
    {
        if($model->module_id != $model::TYPE_NONE) {
            $modelSearchClass = 'anda\cms\modules\\' . $model->module_id . '\models\\' . ucfirst($model->module_id) . 'Search';
            $record = $modelSearchClass::findOne($model->module_record_id);
            $parents = [];
            if (isset($record->root)) {
                $ancestors = $record->ancestors()->all();
            } elseif (isset($record->category_id)) {
                $category = Category::findOne($record->category_id);
                $ancestors = $category->ancestors()->all();
            }

            foreach ($ancestors as $ancestor) {
                $parents[] = $ancestor->title;
            }
            krsort($parents);

            if (isset($category)) {
                $parents[] = $category->title;
            }

            $parents[] = '<span class="text-primary">' . $record->title . '</span>';

            return $parents;
        }

        return [];
    }
}
