<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model anda\cms\modules\post\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$gropHeaderOptions = [
    'style' => 'background-color: #d2d6de'
];
echo $model->createdBy->profile->className();
?>
<div class="post-view">
    <div class="row">
        <div class="col-sm-9">
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
                    'heading'=>'Post # ' . $model->title,
                    'type'=>DetailView::TYPE_INFO,
                ],

                'attributes'=>[
                    ['columns' => [
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
                    ]],
                    [
                        'group' => true,
                        'label' => '<i class="fa fa-pencil"></i> Content',
                        'rowOptions' => $gropHeaderOptions,
                    ],
                    'title',
                    'introtext:ntext',
                    [
                        'attribute' => 'content',
                        'format' => 'html',
                    ],
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
                    'publish_up',
                    'publish_down',
                    'version',
                    'hits',
        //            'image',
                ]
            ]);
            ?>
        </div>
        <div class="col-sm-3">

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
                        <tr>
                            <th><?= $model->getAttributeLabel('deleted_at'); ?></th>
                            <td><?= ($model->deleted_at === null || empty($model->deleted_at)) ? '<span class="not-set">'.Yii::t('yii', '(not set)').'</span>' : Yii::$app->formatter->asDatetime($model->deleted_at); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
