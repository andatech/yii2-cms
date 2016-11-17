<?php



/* @var $this yii\web\View */
/* @var $model anda\cms\modules\page\models\Page */

$this->title = 'Add new Page';
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <?= $this->render('_form', [
        'model' => $model,
        'treeArray' => $treeArray,
    ]) ?>

</div>
