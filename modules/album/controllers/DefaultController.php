<?php

namespace anda\cms\modules\album\controllers;

use Yii;
use anda\cms\modules\category\models\Category;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\InvalidCallException;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;

use anda\cms\modules\post\controllers\DefaultController as Controller;

/**
 * DefaultController implements the CRUD actions for Album model.
 */
class DefaultController extends Controller
{
    public $modelClass = 'anda\cms\modules\album\models\Album';

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
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionUploadImages($id)
    {
        if(Yii::$app->request->isAjax && Yii::$app->request->post()){
            $model = $this->findModel($id);
            $model->prepareDirectories();
            $defaultResize = [
                'full' => ['width' => 2048, 'height' => 1000],
                'thumb' => ['width' => 800, 'height' => 600],
            ];
            if(isset($this->module->settings['resize'])){
                $resizes = array_replace_recursive($defaultResize, $this->module->settings['resize']);
            }else{
                $resizes = $defaultResize;
            }

            $images = UploadedFile::getInstancesByName('galleries');
            if($images){
                foreach ($images as $file){
                    $fileName = $file->baseName . '.' . $file->extension;
                    $tempDir = $this->getGalleryPath(). '/' . $id .'/temp';

                    $savePath = $tempDir. '/'. $fileName;
                    $fullPath = $this->getGalleryPath(). '/' . $id .'/full/'. $fileName;
                    $thumbPath = $this->getGalleryPath(). '/' . $id .'/thumb/'. $fileName;

                    if($file->saveAs($savePath)){
                        $tempImage = Image::getImagine()->open($savePath);

                        $tempImage->thumbnail(new Box($resizes['full']['width'], $resizes['full']['height']))->save($fullPath, ['quality' => 100]);

                        $tempImage->thumbnail(new Box($resizes['thumb']['width'], $resizes['thumb']['height']))->save($thumbPath, ['quality' => 90]);

                        echo json_encode(['success' => 'true']);
                    }else{
                        echo json_encode(['success' => 'false']);
                    }
                }
                FileHelper::removeDirectory($tempDir);
            }
//            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionDeleteImage($id)
    {
        $request = Yii::$app->request;
        if($request->post('key')) {
            $dir = $this->getGalleryPath(). '/' . $id;
            $path = [
                'full' => $dir.'/full/'.$request->post('key'),
                'thumb' => $dir.'/thumb/'.$request->post('key'),
            ];

            $success = [];
            foreach ($path as $key => $file){
                if(is_file($file)){
                    if(unlink($file)){
                        $success[$key] = true;
                    }else{
                        $success[$key] = false;
                    }
                }
            }
            echo Json::encode(['delete' => $success]);
        }else{
            throw new NotFoundHttpException('Error not have key.');
        }
    }

    private function getGalleryPath()
    {
        $modelClass = $this->modelClass;
        $dir = $this->module->masterModule->uploadDir;
        $dir .= '/' . $modelClass::ALBUM_FOLDER . '/' . $modelClass::GALLERY_FOLDER;

        return $dir;
    }
}
