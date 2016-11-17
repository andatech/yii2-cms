<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use anda\cms\base\WidgetSettings;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel anda\cms\modules\page\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$exportFilename = ucfirst($this->title);

$allColumns = [
    'id' => [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:30px;'],
    ],
    'parentsText' => [
        'attribute' => 'parents',
        'value' => 'parentsText',
        'format' => 'html',
        'filter' => Html::input('text',null,'Not available',['class' => 'form-control', 'disabled' => true]),
    ],
    'status' => [
        'attribute' => 'status',
        'value' => 'statusLabel',
        'format' => 'html',
        'filter' => $searchModel->statuses,
        'headerOptions' => ['style' => 'width:100px;'],
    ],
    'title' => 'title',
    'slug' => 'slug',
    'content' => 'content:ntext',
    'hits' => 'hits',
    'image' => 'image',
    'published_at' => [
        'attribute'=>'published_at',
        'value' => function($model, $key, $index, $column){
            $d = \DateTime::createFromFormat('d/m/Y H:i:s', $model->published_at);
//            return $d->format('U');
            return $d->format('d/m/Y');
        },
//        'format' => ['date'],
        'filterType' => '\kartik\widgets\DatePicker',
        'filterWidgetOptions' => WidgetSettings::DatePicker(),
    ],
     'version' => 'version',
     'created_by' => [
         'attribute' => 'created_by',
         'value' => 'createdBy.profile.fullname',
         'headerOptions' => ['style' => 'width:150px;'],
     ],
     'created_at' => [
         'attribute' => 'created_at',
         'value' => 'created_at',
         'format' => ['date', 'php:d/m/Y'],
         'filterType' => '\kartik\widgets\DatePicker',
         'filterWidgetOptions' => WidgetSettings::DatePicker(),
     ],
     'updated_by' => [
         'attribute' => 'updated_by',
         'value' => 'updatedBy.profile.fullname'
     ],
     'updated_at' => [
         'attribute' => 'updated_at',
         'value' => 'updated_at',
         'format' => ['datetime'],
         'filterType' => '\kartik\widgets\DatePicker',
         'filterWidgetOptions' => WidgetSettings::DatePicker(),
     ],
     'meta_title' => 'meta_title',
     'meta_keywords' => 'meta_keywords',
     'meta_description' => 'meta_description',
     'deleted_at' => [
         'attribute' => 'deleted_at',
         'value' => 'deleted_at',
         'format' => ['datetime']
     ],
];

$gridColumns = [
//        ['class' => '\kartik\grid\SerialColumn'],
    $allColumns['id'],
    $allColumns['title'],
    $allColumns['parentsText'],
    $allColumns['status'],
    //$allColumns['published_at'],
    $allColumns['created_by'],
    //$allColumns['created_at'],

    ['class' => '\kartik\grid\ActionColumn',]
];


$fullExportMenu = ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $allColumns,
    'filename' => $this->title,
    'showConfirmAlert' => false,
    'target' => ExportMenu::TARGET_BLANK,
    'fontAwesome' => true,
    'pjaxContainerId' => 'kv-pjax-container',
    'dropdownOptions' => [
        'label' => 'Full',
        'class' => 'btn btn-default',
        'itemsBefore' => [
            '<li class="dropdown-header">Export All Data</li>',
        ],
    ],
]);


?>
<div class="page-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'data-grid',
        'pjax'=>true,
//        'resizableColumns'=>true,
//        'resizeStorageKey'=>Yii::$app->user->id . '-' . date("m"),
        'floatHeader'=>true,
//        'floatHeaderOptions'=>['scrollingTop'=>'50'],
        'export' => [
            'label' => Yii::t('yii', 'Page'),
            'fontAwesome' => true,
            'target' => GridView::TARGET_SELF,
            'showConfirmAlert' => false,
        ],
        'exportConfig' => [
            GridView::HTML=>['filename' => $exportFilename],
            GridView::CSV=>['filename' => $exportFilename],
            GridView::TEXT=>['filename' => $exportFilename],
            GridView::EXCEL=>['filename' => $exportFilename],
            GridView::PDF=>['filename' => $exportFilename],
            GridView::JSON=>['filename' => $exportFilename],
        ],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="'.$this->context->module->icon.'"></i> '.Html::encode($this->title).'</h3>',
            'type'=>'primary',
            'before'=> '<div class="btn-group">'.
                Html::a('<i class="glyphicon glyphicon-plus"></i> Create Page', ['create'], [
                    'class' => 'btn btn-success btn-flat',
                    'data-pjax' => 0
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i> Reload', '#!', [
                    'class' => 'btn btn-info btn-flat btn-reload',
                    'title' => 'Reload',
                    'id' => 'btn-reload-grid'
                ]). ' '.
                Html::a('<i class="glyphicon glyphicon-tree-conifer"></i> Tree manager', ['tree'], [
                    'class' => 'btn btn-info btn-flat btn-github',
                    'title' => 'Tree manager',
                    'data-pjax' => 0
                ]).
                '</div>',
        ],
        'toolbar' => [
            '{export}',
            '{toggleData}',
            $fullExportMenu,
        ],
        'columns' => $gridColumns, /*[
            ['class' => '\kartik\grid\SerialColumn'],

            'id',
            //'status',
            'title',
            //'slug',
            // 'content:ntext',
            // 'hits',
            // 'image',
            // 'page_type',
             'published_at',
            // 'version',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',
            // 'meta_title',
            // 'meta_keywords',
            // 'meta_description',
            // 'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],*/
    ]); ?>
</div>
<?php
$js[] = "
$(document).on('click', '#btn-reload-grid', function(e){
    e.preventDefault();
    $.pjax.reload({container: '#data-grid-pjax'});
});
";

$this->registerJs(implode("\n", $js));