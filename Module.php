<?php

namespace anda\cms;

use Yii;
use anda\cms\models\Module as ModuleModel;
use anda\cms\models\Setting;
use anda\cms\helpers\Data;
use yii\base\BootstrapInterface;

/**
 * cms module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'anda\cms\controllers';

    public $activeModules = [];

    public $tablePrefix = 'cms_';

    public $uploadDir = '@webroot/uploads';

    public $uploadUrl = '@web/uploads';

    public $themeCssClass = 'hold-transition skin-blue sidebar-mini';

    public $defaultTitle = 'Web-Admin';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here

//        $this->setSession();

        $this->setParams();

        $this->setLayout();

        $this->setUploadFolder();

        $this->setActiveModules();

        $this->setBreadcrumbs();

        $this->setDefaultTitle();

        $this->setKCFinderConfig();

        $this->setErrorAction();

        $this->registerTranslations();

//        $this->bootstrapping();
    }

    /**
     * Set uploads Dir and URL when config as alias
     */
    private function setUploadFolder()
    {
        if (substr($this->uploadDir, 0, 1) === '@') {
            $this->uploadDir = Yii::getAlias($this->uploadDir);
        }
        if (substr($this->uploadUrl, 0, 1) === '@') {
            $this->uploadUrl = Yii::getAlias($this->uploadUrl);
        }
    }

    /**
     * Set active modules
     */
    private function setActiveModules()
    {
        $modules = [];
        $this->activeModules = ModuleModel::findAllActive();
        foreach ($this->activeModules as $name => $module) {
            $modules[$name]['class'] = $module->class;
            $modules[$name]['icon'] = $module->icon;
            if (is_array($module->settings)) {
                $modules[$name]['settings'] = $module->settings;
            }
        }

        $this->setModules($modules);
    }

    /**
     * Set Layout
     */
    private function setLayout()
    {
        $this->layoutPath = '@anda/cms/views/layouts';
        $this->layout = 'main';
    }


    /**
     * Set KCFinder session config
     */
    protected function setKCFinderConfig()
    {
        //Yii::$app->session->removeAll();
        $kcfOptions = [
            'disabled' => false,
            'uploadURL' => $this->uploadUrl.'/content',
            'uploadDir' => $this->uploadDir.'/content',
        ];
        Yii::$app->session->set('KCFINDER', $kcfOptions);
    }


    /**
     * set Default title
     */
    protected function setDefaultTitle()
    {
        $view = Yii::$app->getView();
        if (empty($view->title)) {
            $view->title = $this->defaultTitle;
        }
    }

    /**
     * Route error
     */
    protected function setErrorAction()
    {
        $route = Yii::$app->urlManager->parseRequest(Yii::$app->request)[0];
        $arr = explode('/', $route);
        if(trim($arr[0]) === $this->id){
        Yii::$app->errorHandler->errorAction = '/'.$this->id.'/default/error';
        }
    }

    /**
     * Route error
     */
    protected function setBreadcrumbs()
    {
        $route = Yii::$app->urlManager->parseRequest(Yii::$app->request)[0];
        $arr = explode('/', $route);
        if(trim($arr[0]) === $this->id){
            Yii::$app->getView()->params['breadcrumbs'] = [['label' => 'Web Admin', 'url' => ['/' . $this->id]]];
        }
    }

    protected function setParams()
    {
        Yii::$app->params['ANDACMS']['TABLE_PREFIX'] = $this->tablePrefix;
        Yii::$app->params['ANDACMS']['MASTER_MODULE_ID'] = $this->id;
    }

    protected function setSession()
    {
        $session = Yii::$app->session;
        $tmp = $session->get('ANDACMS');
        $tmp['TABLE_PREFIX'] = 'web_';
        $tmp['MASTER_MODULE_ID'] = $this->id;
        $session->set('ANDACMS', $tmp);
    }

    public function bootstrap($app)
    {
//        $model = Setting::find()->all();
//        foreach ($model as $section){
//            $settings[$section->type][$section->name] = $section->value;
//        }

        $settings = Data::cache(Setting::CACHE_KEY, 3600, function() {
            $model = Setting::find()->all();
            $settings = [];
            foreach ($model as $section){
                $settings[$section->type][$section->name] = $section->value;
            }

            return $settings;
        });

        $general = $settings['general'];
        $app->name = $general['title'];
        $app->language = $general['language'];
        $app->timezone = $general['timezone'];
        $app->formatter->dateFormat = $general['dateformat'];
        $app->formatter->timeFormat = $general['timeformat'];
        $app->view->title = $general['title'];

        $seos = [
            'description' => ['name' => 'description', 'content' => $general['description']],
            'keywords' => ['name' => 'description', 'content' => $general['keywords']],
            'content-language' => ['name' => 'content-language', 'content' => $general['language']],
            'content-type' => ['name' => 'content-type', 'content' => 'text/html; charset='.$app->charset],

            'og:url' => ['property' => 'og:url', 'content' => Yii::$app->request->absoluteUrl],
            'og:type' => ['property' => 'og:type', 'content' => 'website'],
            'og:title' => ['property' => 'og:title', 'content' => $general['title']],
            'og:description' => ['property' => 'og:description', 'content' => $general['description']],
            'og:image' => ['property' => 'og:image', 'content' => 'http://www.comm-sci.pn.psu.ac.th/media/parallax/img/bg.png'],
        ];

        foreach ($seos as $key => $seo){
            $app->view->registerMetaTag($seo, $key);
        }

    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['andacms'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@anda/cms/messages',
            'fileMap' => [
                'andacms' => 'andacms.php',
            ]
        ];
    }
}
