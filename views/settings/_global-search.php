<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model anda\cms\models\SettingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'fieldConfig' => [
            'options' => [
                //'tag' => false,
                'class' => 'has-feedback'
            ],
        ],
    ]); ?>

    <?= $form->field($model, 'globalSearch',[
        'template' => '{input}<span class="glyphicon glyphicon-search form-control-feedback"></span>'
    ])->textInput([
        'class' => 'form-control input-sm',
    ]) ?>

    <?= $form->field($model, 'type', ['template' => '{input}'])->hiddenInput() ?>

    <?php ActiveForm::end(); ?>

</div>
