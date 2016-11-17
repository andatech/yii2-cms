<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

if (Yii::$app->controller->action->id === 'login') {
/**
 * Do not use this code in your template. Remove it.
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    dmstr\web\AdminLteAsset::register($this);
    anda\cms\assets\CmsAsset::register($this);

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    
    ?>

    <?php
    if(isset($this->context->module->masterModule)) {
        $masterModule = $this->context->module->masterModule;
    }else{
        $masterModule = $this->context->module;
    }

    $profile = Yii::$app->user->identity->profile;

    $params = [
        'directoryAsset' => $directoryAsset,
        'profile' => $profile,
        'masterModule' => $masterModule
    ];
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="<?= $masterModule->themeCssClass; ?>">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <?php $profile = Yii::$app->user->identity->profile; ?>

        <?= $this->render('header.php', $params) ?>

        <?= $this->render('left.php', $params) ?>

        <?= $this->render('content.php', array_merge($params, ['content' => $content])) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
