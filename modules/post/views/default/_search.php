<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model anda\cms\modules\post\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'category_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'slug') ?>

    <?php // echo $form->field($model, 'introtext') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'hits') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'post_type') ?>

    <?php // echo $form->field($model, 'published_at') ?>

    <?php // echo $form->field($model, 'publish_up') ?>

    <?php // echo $form->field($model, 'publish_down') ?>

    <?php // echo $form->field($model, 'version') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'meta_title') ?>

    <?php // echo $form->field($model, 'meta_keywords') ?>

    <?php // echo $form->field($model, 'meta_description') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
