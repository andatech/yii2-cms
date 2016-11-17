<?php

namespace anda\cms\controllers;

use Yii;
use anda\cms\models\Setting;
use anda\cms\models\SettingSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use anda\cms\base\Controller;

/**
 * SettingsController implements the CRUD actions for Setting model.
 */
class SettingsController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout = '@anda/cms/views/settings/_submenu';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Setting models.
     * @return mixed
     */
    public function actionIndex()
    {
        /*if(Yii::$app->request->get('SettingSearch') === null){
            $typeKeys = array_keys(\anda\cms\models\Setting::getTypes());
            return $this->redirect(['index', 'SettingSearch'=>['type'=>current($typeKeys)]]);
        }
        $searchModel = new SettingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
        return $this->redirect(['general']);
        //return Yii::$app->runAction('settings/general');
    }
    public function actionGeneral()
    {
        $model = new \anda\cms\models\GeneralSetting;
        if ($model->load(Yii::$app->request->post())) {
            foreach ($model->attributes as $name => $value){
                $settingModel = Setting::find()->where(['name' => $name, 'type' => $this->action->id])->one();
                if($settingModel){
                    $settingModel->value = $value;
                    $settingModel->save();
                }else{
                    $settingModel = new Setting();
                    $settingModel->type = $this->action->id;
                    $settingModel->name = $name;
                    $settingModel->language = null;
                    $settingModel->value = $value;
                    $settingModel->save();
                }
            }
            return $this->redirect([$this->action->id]);
        }else{
            $settingModel = Setting::find()->where(['type' => $this->action->id])->all();
            foreach ($settingModel as $key => $row){
                $name = $row->name;
                $model->$name = $row->value;
            }
            return $this->render($this->action->id, ['model' => $model]);
        }
    }

    public function actionReading()
    {
        $model = new \anda\cms\models\ReadingSetting;
        if ($model->load(Yii::$app->request->post())) {
            foreach ($model->attributes as $name => $value){
                $settingModel = Setting::find()->where(['name' => $name, 'type' => $this->action->id])->one();
                if($settingModel){
                    $settingModel->value = $value;
                    $settingModel->save();
                }else{
                    $settingModel = new Setting();
                    $settingModel->type = $this->action->id;
                    $settingModel->name = $name;
                    $settingModel->language = null;
                    $settingModel->value = $value;
                    $settingModel->save();
                }
            }
            return $this->redirect([$this->action->id]);
        }else{
            $settingModel = Setting::find()->where(['type' => $this->action->id])->all();
            foreach ($settingModel as $key => $row){
                $name = $row->name;
                $model->$name = $row->value;
            }
            return $this->render($this->action->id, ['model' => $model]);
        }
    }

    public function actionSeo()
    {

    }

    public function actionFlush()
    {
        $frontendAssetPath = Yii::getAlias('@frontend') . '/web/assets/*';
        $backendAssetPath = Yii::getAlias('@backend') . '/web/assets/*';
        $assets = array_merge(
            glob($frontendAssetPath, GLOB_ONLYDIR),
            glob($backendAssetPath, GLOB_ONLYDIR)
        );

        foreach ($assets as $asset){
            \yii\helpers\FileHelper::removeDirectory($asset);
        }

        return Yii::$app->getResponse()->redirect(Yii::$app->getRequest()->referrer);
    }

    /**
     * Displays a single Setting model.
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
     * Creates a new Setting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Setting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'Setting[type]' => Yii::$app->request->post('Setting')['type']]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'Setting[type]' => Yii::$app->request->post('Setting')['type']]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Setting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
