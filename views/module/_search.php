<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model anda\cms\models\ModuleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="module-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'class') ?>

    <?= $form->field($model, 'icon') ?>

    <?php // echo $form->field($model, 'settings') ?>

    <?php // echo $form->field($model, 'order_num') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
