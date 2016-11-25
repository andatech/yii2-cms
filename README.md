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
```
