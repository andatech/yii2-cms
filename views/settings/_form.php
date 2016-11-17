<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model anda\cms\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6"><?= $form->field($model, 'type')->textInput(['readonly' => true]) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> Create' : '<i class="fa fa-check"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
