<?php



/* @var $this yii\web\View */
/* @var $model anda\cms\modules\page\models\Page */

$this->title = 'Add new Page';
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">
    <?php
    if(Yii::$app->request->get('parent_id')){
        $parents = \anda\cms\modules\menu\models\Menu::findOne(Yii::$app->request->get('parent_id'));
        $this->params['path'] = $parents->getParentsText(' &raquo; ').' &raquo; '.$parents->title;
    }else{
        $this->params['path'] = 'Root';
    }

    $model->type = 0;
    ?>
    <?= $this->render('_form', [
        'model' => $model,
        'hints' => $hints
    ]) ?>

</div>
