<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use anda\cms\base\WidgetSettings;
use kartik\widgets\DateTimePicker;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model anda\cms\modules\post\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="post-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="row">
            <div class="col-sm-9">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'introtext')->textInput(['maxlength' => true]) ?>

                <label class="control-label" for="<?= Html::getInputId($model, 'image'); ?>" style="width: 100%; margin: 0; cursor: pointer;">
                    <?= $model->getAttributeLabel('image'); ?>
                    <?php
                    $clientsPath = Yii::$app->assetManager->getPublishedUrl('@anda/cms/clients');
                    $image = rtrim($model->behaviors()['crop-image']['attributes']['image']['savePathAlias'], '/').'/'.$model->image;
                    if (is_file($image)){
                        $imageUrl = $model->getImageUrl('image');
                        echo Html::img($model->getImageUrl('image'));
                    }else{
                        echo '<div class="small-box bg-gray" style="margin-bottom: 0;">
            <div class="inner" style="opacity: 0.2;">
              <h3 class="text-center">Select image</h3>

              <p>&nbsp;</p>
            </div>
            <div class="icon" style="position: absolute; left: 0; right: 0; margin-left: auto; margin-right: auto; width: 100px;">
              <i class="fa fa-picture-o"></i>
            </div>
          </div>';
//                        $imageUrl = $clientsPath.'/images/image-none.jpg';
                    }

//                    echo Html::img($imageUrl, ['style' => 'width: 100%; max-height: 20%']);
                    ?></label>
                <?= $form->field($model, 'image')->widget(\maxmirazh33\image\Widget::className())->label(false) ?>
                <?php
                $treeView = \wbraganca\fancytree\FancytreeWidget::widget([
                    'id' => 'categories',
                    'options' =>[
                        'source' => $treeArray,
                        'activate' => new JsExpression('function(event, data) {
                            var node = data.node;
                            $("#'.Html::getInputId($model, 'category_id').'").val(node.key);
                        }'),
                    ]
                ]);
                ?>



            </div>

            <div class="col-sm-3">

                <div class="callout callout-info">
                    <p><i class="fa fa-info-circle"></i> Date time format as <mark>d/m/Y H:i:s</mark></p>
                </div>

                <?= $form->field($model, 'published_at')->textInput()->widget(DateTimePicker::classname(), WidgetSettings::DateTimePicker()) ?>

                <?= $form->field($model, 'publish_up')->textInput()->widget(DateTimePicker::classname(), WidgetSettings::DateTimePicker()) ?>

                <?= $form->field($model, 'publish_down')->textInput()->widget(DateTimePicker::classname(), WidgetSettings::DateTimePicker()) ?>

                <?= $form->field($model, 'category_id',['template' => "{label}\n{input}\n".$treeView."\n{hint}\n{error}",])->hiddenInput() ?>

                <?= $form->field($model, 'status')->dropDownList($model->getStatuses()) ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$this->registerJs("$(\"#fancyree_categories\").fancytree(\"getTree\").activateKey(\"" . $model->category_id . "\");");
$this->registerCss("
.fullinput{
    width: 100%;
}
");