# yii2-cms
Yii 2 CMS from Anda Tech

#### Main config

```php
    ...
    'bootstrap' => [
        'log',
        'web-admin',
    ],
    ...
    'modules' => [
      'web-admin' => [
        'class' => 'anda\cms\Module',
        'tablePrefix' => 'web_',
        'uploadDir' => '@uploads/anda-cms',
        'uploadUrl' => '/uploads/anda-cms'
      ]
    ],
    'components' => [
      ...
      'andacms' => [
        'class' => 'anda\cms\components\API',
      ],
      ...
    ]
```


#### Sample Controller 

```php
    public function actionIndex()
    {
        $model = Yii::$app->andacms->getChildModule('news');
        $searchModel = $model->getSearchModel();
        $dataProvider = $model->getDataProvider();

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionView($id)
    {
        $model = Yii::$app->andacms->getChildModule('news')->view($id);
        if (Yii::$app->request->isAjax){
            return $this->renderAjax('view',[
                'model' => $model
            ]);
        }
        return $this->render('view',[
            'model' => $model
        ]);
    }
    
    public function actionFrontpage()
    {
        $render = Data::cache('frontpage'.ucfirst($this->id), 3600, function(){
            $model = Yii::$app->andacms->getChildModule($this->id);
            $model->getSearchModel();
            $dataProvider = $model->getDataProvider();
            $dataProvider->pagination->pageSize=$this->frontpageLimitItems;

            return $this->renderPartial('frontpage',[
                'dataProvider' => $dataProvider
            ]);
        });

        return $render;
    }
    

    public function actionCategory($id)
    {
        $categoryModel = Yii::$app->andacms->getChildModule('category')->get($id);

        $searchModel = Yii::$app->andacms->getChildModule($this->id)->getSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->query->andFilterWhere(['category_id' => $id]);

        return $this->render('category',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'categoryModel' => $categoryModel
        ]);
    }
```
