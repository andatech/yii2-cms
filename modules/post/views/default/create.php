<?php



/* @var $this yii\web\View */
/* @var $model anda\cms\modules\post\models\Post */

$this->title = 'Add new '.ucfirst($this->context->module->id);
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <?= $this->render('_form', [
        'model' => $model,
        'treeArray' => $treeArray,
    ]) ?>

</div>
