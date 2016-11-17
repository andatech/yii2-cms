<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel anda\cms\models\SettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$settingSearch = Yii::$app->request->get('SettingSearch');
$this->title = $searchModel->types[$settingSearch['type']]['label'].' Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-index">

    <?php  $this->params['search-box'] = $this->render('_global-search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class="fa fa-plus" aria-hidden="true"></i> Create '.$this->title, ['create', 'Setting'=>$settingSearch], ['class' => 'btn btn-success']) ?>
    </p>
<?php /*Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'type',
            'name',
            'language',
            'value:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        //return $model->status === 'editable' ? Html::a('Update', $url) : '';
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',['update', 'id'=>$model->id, 'Setting'=>Yii::$app->request->get('SettingSearch')]);
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end();*/ ?>
    <?php $model = $dataProvider->getModels(); ?>
    <?php $form = ActiveForm::begin(); ?>
    <?php foreach ($model as $idx => $row): ?>
        <?= $form->field($row, 'value')->textInput([
            'name' => 'Setting['.$settingSearch['type'].']['.$row->name.']',
            'id' => 'setting-'.$settingSearch['type'].'-'.$row->name.'',
        ])->label($row->label) ?>
    <?php endforeach; ?>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-floppy-o"></i> Save', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
