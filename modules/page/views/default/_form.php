<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use anda\cms\base\WidgetSettings;
use dosamigos\ckeditor\CKEditor;
use anda\filemanager\KCFinder;
use kartik\widgets\DateTimePicker;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model anda\cms\modules\page\models\Page */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="page-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-sm-9">
            <div class="box box-<?= $model->isNewRecord ? 'success' : 'primary'; ?>">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $model->isNewRecord ? 'Create' : 'Update'; ?></h3>
                </div>
                <div class="box-body">
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
                <div class="box-footer">
                    <div class="pull-right">
                        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> Create' : '<i class="fa fa-check" aria-hidden="true"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                    <?= Html::a('<i class="fa fa-times"></i> Discard', ['index'], ['class' => 'btn btn-default']); ?>
                </div>
            </div>
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

            <div class="form-group">
                <strong>Parent</strong>
                <?php
                $treeWithRoot[] = [
                    'key' => 0,
                    'title' => 'Root',
                    'folder' => 1,
                    'children' => $treeArray
                ];
                echo \wbraganca\fancytree\FancytreeWidget::widget([
                    'id' => 'page',
                    'options' =>[
                        'source' => $treeWithRoot,
                        'activate' => new JsExpression('function(event, data) {
                            var node = data.node;
                            $("#parentId").val(node.key);
                        }'),
                    ]
                ]);
    //            $this->registerJs("$('#".$form->id."').submit(function(e){
    //            $(\"#fancyree_page\").fancytree(\"getTree\").generateFormElements();
    //            });");
                if(!$model->isNewRecord) {
                    $parent = $model->parent()->one();
                    $parentId = ($parent) ? $parent->id : $model->id;
                    echo Html::input('hidden', 'parentIdOld', $parentId, ['id' => 'parentIdOld']);
                    $this->registerJs("$(\"#fancyree_page\").fancytree(\"getTree\").activateKey(\"" . $model->id . "\");");
                    $this->registerJs("var node = $(\"#fancyree_page\").fancytree(\"getActiveNode\"); node.remove();");

                    if ($parent) {
                        $this->registerJs("$(\"#fancyree_page\").fancytree(\"getTree\").activateKey(\"" . $parent->id . "\");");
                    }
                }else{
                    $parentId = 0;
                }
                echo Html::input('hidden', 'parentId', $parentId, ['id' => 'parentId']);
                ?>
            </div>

            <?php echo $form->field($model, 'content_module')->dropDownList($model->getContentModules()) ?>

            <?= $form->field($model, 'status')->dropDownList($model->getStatuses()) ?>
            <hr />
            <div class="form-group">

                <div class="pull-right">
                    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> Create' : '<i class="fa fa-check" aria-hidden="true"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <?= Html::a('<i class="fa fa-times"></i> Discard', ['index'], ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerCss("
.fullinput{
    width: 100%;
}
");