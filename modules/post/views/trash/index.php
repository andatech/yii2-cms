<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use anda\cms\base\WidgetSettings;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel anda\cms\modules\album\models\AlbumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ucfirst($this->context->module->id).' :: Trash';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php
$exportFilename = ucfirst($this->title);
$btnEmptyTrash = '';
if ($this->context->module->id === 'post') {
    $btnEmptyTrash = Html::a('<i class="glyphicon glyphicon-trash"></i> Empty Trash', ['empty'], [
        'class' => 'btn btn-danger btn-flat',
        'data-pjax' => 0,
        'title' => 'ล้าง',
        'aria-label' => 'ล้าง',
        'data-confirm' => "คุณแน่ใจหรือไม่ที่จะลบรายการนี้?",
        'data-method' => "post"
    ]);
}
$allColumns = [
    'id' => 'id',
    'category_id' => [
        'attribute' => 'category_id',
        'value' => 'category.title',
//        'value' => function($model, $key, $index, $column){
//            return $model->category->getparentsText(' &raquo; ') . ' &raquo; ' . $model->category->title;
//        },
        'format' => 'html',
    ],
    'status' => [
        'attribute' => 'status',
        'value' => 'statusText'
    ],
    'title' => 'title',
    'slug' => 'slug',
    'introtext' => 'introtext:ntext',
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
     'publish_up' => [
         'attribute'=>'publish_up',
         'value' => function($model, $key, $index, $column){
             if($model->publish_up === null || empty($model->publish_up)){
                 return null;
             }
             $d = \DateTime::createFromFormat('d/m/Y H:i:s', $model->publish_up);
             return $d->format('d/m/Y');
         },
         'filterType' => '\kartik\widgets\DatePicker',
         'filterWidgetOptions' => WidgetSettings::DatePicker(),
     ],
     'publish_down' => [
         'attribute'=>'publish_down',
         'value' => function($model, $key, $index, $column){
             if($model->publish_down === null || empty($model->publish_down)){
                 return null;
             }
             $d = \DateTime::createFromFormat('d/m/Y H:i:s', $model->publish_down);
             return $d->format('d/m/Y');
         },
         'filterType' => '\kartik\widgets\DatePicker',
         'filterWidgetOptions' => WidgetSettings::DatePicker(),
     ],
     'version' => 'version',
     'created_by' => [
         'attribute' => 'created_by',
         'value' => 'createdBy.profile.fullname'
     ],
     'created_at' => [
         'attribute' => 'created_at',
         'value' => 'created_at',
         'format' => ['datetime', 'php:d/m/y H:i:s'],
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
    'status' => [
        'attribute' => 'status',
        'value' => 'statusLabel',
        'format' => 'html',
        'filter' => $searchModel->statuses
    ],
    $allColumns['category_id'],
//    $allColumns['published_at'],
    $allColumns['created_by'],
    $allColumns['updated_at'],
    $allColumns['deleted_at'],

//    ['class' => '\kartik\grid\ActionColumn',]
    [
        'class' => '\kartik\grid\ActionColumn',
        'template' => '{restore} {delete}',
        'buttons'=>[
            'restore' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-open-file"></span>', ['restore', 'id'=>$model->id], [
                    'title' => Yii::t('yii', 'Restore'),
                    'data-method' => 'post',
                    'data-pjax' => 0,
                    'class' => 'btn-restore',
                ]);

            },
        ],
    ]
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
<div class="album-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'data-grid',
        'pjax'=>true,
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
            'type'=>'danger',
            'before'=> '<div class="btn-group">'.
                Html::a('<i class="fa fa-table"></i> '.ucfirst($this->context->module->id), ['default/index'], [
                    'class' => 'btn btn-primary btn-flat',
                    'data-pjax' => 0
                ]) . ' '.
                $btnEmptyTrash.
                '</div>',
        ],
        'toolbar' => [
            '{export}',
            '{toggleData}',
            $fullExportMenu,
        ],
        'columns' => $gridColumns,
    ]); ?>
</div>
<?php
$js[] = "$(document).on('click', '#btn-reload-grid', function(e){
    e.preventDefault();
    $.pjax.reload({container: '#data-grid-pjax'});
});";

$js[] = "$('body').on('click', '.btn-restore', function(){
    var key = $(this).closest('tr').data('key');
    $.post($(this).attr('href'), {id: key}, function(data){
        if(data.success){
            $.pjax.reload({container: '#data-grid-pjax'});
        }else{
            alert('Can not restore this item');
        }
    });
    return false;
});";

$this->registerJs(implode("\n", $js));