<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use anda\cms\base\WidgetSettings;
use kartik\widgets\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model anda\cms\modules\page\models\Page */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="page-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="box box-<?= $model->isNewRecord ? 'success' : 'primary'; ?>">
        <div class="box-header with-border">
            <h3 class="box-title">
                <?= $model->isNewRecord ? 'Create' : 'Update'; ?>::
                <span class="text-muted"><?= $this->params['path']; ?></span>
                <span class="text-danger"> &raquo;
                    <?php
                    if($model->isNewRecord){
                        echo 'Untitled';
                    }else{
                        echo $model->title;
                    }
                    ?></span>
            </h3>
        </div>
        <div class="box-body">
            <?= $form->field($model, 'id')->textInput(['disabled' => true]) ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'type', ['options' => ['style' => 'margin-bottom: 0;']])->radioList($model->getTypeList(true), [
                'class' => 'btn-group',
                'data-toggle' => 'buttons',
                'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                    return Html::label(
                        Html::radio(
                            $name,
                            $checked,
                            ['id' => $name . '_' . $value, 'value' => $value]
                        ) . $label,
                        $name . '_' . $value,
                        [
                            'class' => 'btn btn-default model-type' . ($value == $model->type ? ' active':'') // my crappy way to make sure that the first button is checked when the value is null. Using $checked doesn't work
                        ]
                    );
                },
            ]); ?>
            <div class="well">
                <div id="model-type-section-<?= $model::TYPE_NONE ?>" name="" class="model-type-section">
                    NONE
                </div>
                <div id="model-type-section-<?= $model::TYPE_MODULE ?>" name="" class="model-type-section">
                    <?php
                    $moduleList = \yii\helpers\ArrayHelper::map($this->context->module->masterModule->activeModules, 'name', 'title');
                    unset($moduleList[$this->context->module->id]);
                    ?>
                    <?= $form->field($model, 'module_id')->dropDownList($moduleList, ['prompt'=>'Select module']) ?>

                    <?php
                    echo $form->field($model, 'module_record_id')->widget(Select2::classname(), [
                        'initValueText' => $hints['module_record_id'], // set the initial display text
                        'options' => ['placeholder' => 'Search for a city ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => new JsExpression('function(){ return "'.\yii\helpers\Url::to(['module-records']).'?module_id="+$("#menu-module_id").val(); }'),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(data) { return data.title; }'),
                            'templateSelection' => new JsExpression('function (data) { return data.title; }'),
                        ],
                    ])->hint($hints['module_record_id']);
                    ?>

                </div>
                <div id="model-type-section-<?= $model::TYPE_URL ?>" class="model-type-section">
                    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <?= $form->field($model, 'status')->dropDownList($model->getStatuses()) ?>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> Create' : '<i class="fa fa-check" aria-hidden="true"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <?= Html::a('<i class="fa fa-times"></i> Discard', ['index'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJs("
$(document).on('change', 'input:radio[name=\"Menu[type]\"]', function(e){
    if ($(this).is(':checked')){
        var val = $(this).val();
        $('.model-type-section').hide();
        $('#model-type-section-'+val).show();
    }
});
$('input:radio[name=\"Menu[type]\"]').trigger('change');


$(document).on('change', '#menu-module_id', function(e){
    $('#menu-module_record_id').select2('val', '');
});
");

//$this->registerJs("var module_id;", $this::POS_HEAD);