<?php
/**
 * @var $this yii\web\View
 * @var $name string
 * @var $message string
 * @var $exception \yii\web\HttpException
 */
use yii\helpers\Html;
$this->title = $name;
$textColor = $exception->statusCode === 404 ? "text-yellow" : "text-red";
?>

<div class="error-page">
    <h2 class="headline <?=$textColor?>"> <?= $exception->statusCode ?></h2>

    <div class="error-content">
        <h3><i class="fa fa-warning <?=$textColor?>"></i> Oops! <?= nl2br(Html::encode($message)) ?></h3>

        <p>
            We could not find the page you were looking for.
            Meanwhile, you may <hr />
            <h4 class="text-center">
                <?= Html::a('<i class="fa fa-dashboard"></i> Return to dashboard', ['default/index'], ['class'=>'btn btn-primary']); ?> or
                <?= Html::a('<i class="fa fa-chevron-circle-left"></i> Go back', ['#!'], ['class'=>'btn btn-warning', 'onClick' => 'window.history.back(); return false;']); ?>
            </h4>
        </p>
    </div>
    <!-- /.error-content -->
</div>