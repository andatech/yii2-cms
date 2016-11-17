<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use anda\cms\base\WidgetSettings;
use dosamigos\ckeditor\CKEditor;
use anda\filemanager\KCFinder;
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
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab"><i class="fa fa-pencil"></i> <span class="hidden-xs">Content</span></a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab"><i class="fa fa-line-chart"></i> <span class="hidden-xs">Seo</span></a>
                        </li>
                        <li>
                            <a href="#tab_3" data-toggle="tab"><i class="fa fa-flag"></i> <span class="hidden-xs">Meta</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">

                            <?= $form->field($model, 'introtext')->textarea(['rows' => 2]) ?>

                            <?= $form->field($model, 'content')->widget(CKEditor::className(), [
                                'preset' => 'full',
                                'clientOptions' => KCFinder::registered()
                            ]) ?>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">

                            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_3">
                            <div class="callout callout-info">
                                <p><i class="fa fa-info-circle"></i> Date time format as <mark>d/m/Y H:i:s</mark></p>
                            </div>

                            <?= $form->field($model, 'published_at')->textInput()->widget(DateTimePicker::classname(), WidgetSettings::DateTimePicker()) ?>

                            <?= $form->field($model, 'version')->textInput() ?>

                            <?= $form->field($model, 'hits')->textInput() ?>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>

            <div class="col-sm-3">
                <label class="control-label" for="<?= Html::getInputId($model, 'image'); ?>" style="width: 100%; margin: 0; cursor: pointer;">
                    <?= $model->getAttributeLabel('image'); ?>
                    <?php
                    $clientsPath = Yii::$app->assetManager->getPublishedUrl('@anda/cms/clients');
                    $image = rtrim($model->behaviors()['crop-image']['attributes']['image']['savePathAlias'], '/').'/'.$model->image;
                    if (is_file($image)){
                        $imageUrl = $model->getImageUrl('image');
                    }else{
                        $imageUrl = $clientsPath.'/images/image-none.jpg';
                    }

                    echo Html::img($imageUrl, ['style' => 'width: 100%;']);
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