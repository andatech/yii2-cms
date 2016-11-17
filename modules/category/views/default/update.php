<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model anda\cms\modules\page\models\Page */

$this->title = 'Update Page: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="page-update">
    <?php
    $this->params['path'] = $model->getParentsText(' &raquo; ');
    ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
