<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model anda\cms\modules\post\models\Post */

$this->title = 'Update '.ucfirst($this->context->module->id).': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-update">

    <?= $this->render('_form', [
        'model' => $model,
        'treeArray' => $treeArray,
    ]) ?>

</div>
