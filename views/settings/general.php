<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model anda\cms\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->title = ucfirst($this->context->action->id);
?>
<?php
if(empty($model->language)){$model->language = $model::DEFAULT_VALUE['language'];}
if(empty($model->timezone)){$model->timezone = $model::DEFAULT_VALUE['timezone'];}
if(empty($model->dateformat)){$model->dateformat = $model::DEFAULT_VALUE['dateformat'];}
if(empty($model->timeformat)){$model->timeformat = $model::DEFAULT_VALUE['timeformat'];}
if(empty($model->datetimeformat)){$model->datetimeformat = $model::DEFAULT_VALUE['datetimeformat'];}
?>
<div class="setting-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'label' => 'col-sm-3',
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput() ?>
    
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->input('email') ?>

    <?= $form->field($model, 'language')->dropDownList($model->languages) ?>

    <?= $form->field($model, 'timezone')->dropDownList($model->timezones) ?>

    <?= $form->field($model, 'dateformat')->dropDownList($model->dateFormats) ?>

    <?= $form->field($model, 'timeformat')->dropDownList($model->timeFormats) ?>

    <?= $form->field($model, 'datetimeformat')->dropDownList($model->dateTimeFormats) ?>

        <div class="col-sm-9 col-sm-offset-3">
                <?= Html::submitButton('<i class="fa fa-check"></i> Update', ['class' => 'btn btn-primary']) ?>

        </div>

    <?php ActiveForm::end(); ?>

</div>