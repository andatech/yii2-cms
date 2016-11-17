<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-warning"></i> <?= $model->title; ?> module not have category root</h4>
    Please create category root before using this module.
</div>

<div class="setting-index">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['readonly' => true]); ?>

    <?= $form->field($model, 'title') ?>

    <?= Html::activeHiddenInput($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-check" aria-hidden="true"></i> Create', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>