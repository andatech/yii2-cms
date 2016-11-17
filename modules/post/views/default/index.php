<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use anda\cms\base\WidgetSettings;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel anda\cms\modules\post\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ucfirst($this->context->module->id);
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$exportFilename = ucfirst($this->title);

$allColumns = [
    'id' => 'id',
    'category_id' => [
        'attribute' => 'category_id',
        'value' => function($model, $key, $index, $column){
            if ($model->category) {
                return $model->category->getparentsText(' &raquo; ') . ' &raquo; ' . $model->category->title;
            }else{
                return null;
            }
        },
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

if (!isset($gridColumnsArray)) {
    $gridColumnsArray = [
        'id',
        'title',
        'category_id',
        'created_by',
        'created_at',
        ['class' => '\kartik\grid\ActionColumn',]
    ];
}

foreach ($gridColumnsArray as $key => $value){
    if (is_array($value)){
        $gridColumns[] = $value;
    }else {
        $gridColumns[] = $allColumns[$value];
    }
}


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
<?php
$btnSetting = '';
if(isset($fromModule)){
    $btnSetting = Html::a('<i class="glyphicon glyphicon-cog"></i> '.Yii::t('andacms', 'Settings'), ['setting/index'], [
        'class' => 'btn btn-github btn-flat',
        'data-pjax' => 0
    ]);
}
?>
<div class="post-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'data-grid',
        'pjax'=>true,
//        'resizableColumns'=>true,
//        'resizeStorageKey'=>Yii::$app->user->id . '-' . date("m"),
//        'floatHeader'=>true,
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
                Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('andacms', 'Create'), ['create'], [
                    'class' => 'btn btn-success btn-flat',
                    'data-pjax' => 0
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('andacms', 'Reload'), '#!', [
                    'class' => 'btn btn-info btn-flat btn-reload',
                    'title' => 'Reload',
                    'id' => 'btn-reload-grid'
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-list-alt"></i> '.Yii::t('andacms', 'Categories'), ['/'.$this->context->module->masterModule->id.'/category'], [
                    'class' => 'btn btn-default btn-flat',
                    'data-pjax' => 0
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-trash"></i> '.Yii::t('andacms', 'Trash'), ['trash/index'], [
                    'class' => 'btn btn-warning btn-flat',
                    'data-pjax' => 0
                ]) . ' '.
                $btnSetting.
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
            'category_id',
            //'status',
            'title',
            //'slug',
            // 'introtext:ntext',
            // 'content:ntext',
            // 'hits',
            // 'image',
            // 'post_type',
             'published_at',
            // 'publish_up',
            // 'publish_down',
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