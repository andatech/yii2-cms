<?php

$model->load(Yii::$app->request->get());


/* @var $this yii\web\View */
/* @var $model anda\cms\models\Setting */

$this->title = 'Create '.ucfirst($model->type).' Setting';
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
