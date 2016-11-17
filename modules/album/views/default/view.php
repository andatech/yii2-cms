<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model anda\cms\modules\album\models\Album */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$gropHeaderOptions = [
    'style' => 'background-color: #d2d6de'
];

?>

<div class="album-view">
    <div class="row">
        <div class="col-sm-9">
            <!-- Input upload -->
            <?php
            echo FileInput::widget([
                'name' => 'galleries[]',
                'options'=>[
                    'accept' => 'image/*',
                    'multiple'=>true
                ],
                'pluginOptions' => [
                    'uploadAsync' => true,
                    'initialPreview'=>$model->initialPreview,
                    'initialPreviewAsData'=>true,
                    'initialCaption'=>"Select images",
//                    'initialPreviewConfig' => [
//                        ['caption' => 'Moon.jpg', 'size' => '873727'],
//                        ['caption' => 'Earth.jpg', 'size' => '1287883'],
//                    ],
                    'initialPreviewConfig' => $model->initialPreviewConfig,
                    'overwriteInitial'=>false,
                    'maxFileSize'=>2800,
                    'uploadUrl' => Url::to(['upload-images', 'id' => $model->id]),
                    'deleteUrl' => Url::to(['delete-image', 'id' => $model->id]),
                ]
            ]);
            ?>
        </div>
        <div class="col-sm-3">
            <?php
            echo DetailView::widget([
                'model'=>$model,
                'vAlign' => DetailView::ALIGN_TOP,
                //        'formOptions' => ['action' => ['update', 'id'=>$model->id]],
                'buttons1' => Html::a(
                        '<i class="fa fa-table"></i>',
                        ['index'],
                        [
                            'class' => 'kv-action-btn kv-btn-update',
                            'title'=>Yii::t('yii', 'Index'),
                            'data-toggle'=>'tooltip'
                        ]).
                    Html::a(
                        '<i class="fa fa-pencil"></i>',
                        ['update', 'id' => $model->id],
                        [
                            'class' => 'kv-action-btn kv-btn-update',
                            'title'=>Yii::t('yii', 'Update'),
                            'data-toggle'=>'tooltip'
                        ]).
                    '{delete}',
                'deleteOptions'=>[ // your ajax delete parameters
                    'url' => ['delete'],
                    'params' => ['id' => $model->id, 'custom_param' => true],
                ],
                'panel'=>[
                    'heading'=>'Album # ' . $model->id,
                    'type'=>DetailView::TYPE_INFO,
                ],

                'attributes'=>[
                    [
                        'attribute'=>'id',
                        'format'=>'raw',
                        'value'=>'<kbd>'.$model->id.'</kbd>',
                        'displayOnly'=>true
                    ],
                    [
                        'attribute' => 'category_id',
                        'value' => $model->category->getParentsText(' &raquo; ').' &raquo; '.$model->category->title,
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'status',
                        'value' => $model->statusText
                    ],
                    [
                        'group' => true,
                        'label' => '<i class="fa fa-pencil"></i> Content',
                        'rowOptions' => $gropHeaderOptions,
                    ],
                    'title',
                    'introtext:ntext',
                    [
                        'group' => true,
                        'label' => '<i class="fa fa-line-chart"></i> SEO',
                        'rowOptions' => $gropHeaderOptions,
                    ],
                    'slug',
                    'meta_title',
                    'meta_keywords',
                    'meta_description',
                    [
                        'group' => true,
                        'label' => '<i class="fa fa-flag"></i> META',
                        'rowOptions' => $gropHeaderOptions,
                    ],
                    'published_at',
                    'hits',
                    //            'image',
                ]
            ]);
            ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title text-info">Fixed Information</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table table-bordered table-striped">
                        <tbody>
                        <tr>
                            <th><?= $model->getAttributeLabel('created_by'); ?></th>
                            <td><?= $model->createdBy->profile->fullname; ?></td>
                        </tr>
                        <tr>
                            <th><?= $model->getAttributeLabel('created_at'); ?></th>
                            <td><?= Yii::$app->formatter->asDatetime($model->created_at); ?></td>
                        </tr>
                        <tr>
                            <th><?= $model->getAttributeLabel('updated_by'); ?></th>
                            <td><?= $model->updatedBy->profile->fullname; ?></td>
                        </tr>
                        <tr>
                            <th><?= $model->getAttributeLabel('updated_at'); ?></th>
                            <td><?= Yii::$app->formatter->asDatetime($model->updated_at); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
