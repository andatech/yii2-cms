<?php
namespace anda\cms\modules\article\controllers;

use Yii;
use anda\cms\modules\category\models\Category;
use anda\cms\modules\post\controllers\DefaultController as Controller;

/**
 * Default controller for the `article` module
 */
class DefaultController extends Controller
{
    public $modelClass = 'anda\cms\modules\article\models\Article';

    public $modelSearchClass = 'anda\cms\modules\article\models\ArticleSearch';

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
     * Renders the index view for the module
     * @return string
     */
}
