<?php
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php

/* @var $this yii\web\View */
/* @var $searchModel anda\cms\modules\page\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-index">
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <?= Html::button('<i class="fa fa-tree"></i> '.Yii::t('andacms/module','Create Root'), ['id' =>'create-root', 'class' => 'btn btn-github btn-flat']); ?>
                <div class="pull-right">
                <?= Html::button('<i class="fa fa-plus"></i> '.Yii::t('andacms/module','Create Child'), ['id' =>'create-sub', 'class' => 'btn btn-success btn-flat']); ?>
                </div>
            </div>
            <?php
            echo \wbraganca\fancytree\FancytreeWidget::widget([
                'id' => 'category',
                'options' =>[
                    'source' => $treeArray,
                    'activate' => new JsExpression('function(event, data) {
    var node = data.node;
    $.get( "'.Url::to(['update']).'", { id: node.key } )
        .done(function( data ) {
            $("#form-tree").html(data);
    });
}'),
                    'extensions' => ['dnd',],
                    'dnd' => [
                        'preventVoidMoves' => true,
                        'preventRecursiveMoves' => true,
                        'autoExpandMS' => 400,
                        'dragStart' => new JsExpression('function(node, data) { return true; }'),
                        'dragEnter' => new JsExpression('function(node, data) { return true; }'),
                        'dragDrop' => new JsExpression('function(node, data) {
    $.get( "'.Url::to(['move-node']).'", { id: data.otherNode.key, mode: data.hitMode, targetId: data.node.key } )
        .done(function( dataAjax ) {
            if(dataAjax.process){
                data.otherNode.moveTo(node, data.hitMode);
            }
        }, 
    "json");
    }'
                        ),
                    ],
                ]
            ]);
            ?>
        </div>
        <div class="col-sm-9">
            <div id="form-tree">Loading...</div>
        </div>
    </div>
</div>

<?php
$js[] = "$(document).on('click', '#create-root', function(){
    $.get('".Url::to(['create'])."').done(function(data){
        $('#form-tree').html(data);
        $('#category-name').focus();
    });
});";

$js[] = "$(document).on('click', '#create-sub', function(){
    var node = $('#fancyree_category').fancytree('getActiveNode');
      if( node ){
//        alert('Currently active: ' + node.key);
        $.get('".Url::to(['create'])."', { parent_id: node.key }).done(function(data){
            $('#form-tree').html(data);
            $('#category-name').focus();
        });
      }else{
        alert('No active node.');
      }
});";


$this->registerJs(implode("\n", $js));
?>
<?php
if(Yii::$app->request->get('id')) {
    $this->registerJs("$(\"#fancyree_category\").fancytree(\"getTree\").activateKey(\"".Yii::$app->request->get('id')."\");");
}else{
    if(isset($treeArray[0])){
        $this->registerJs("$(\"#fancyree_category\").fancytree(\"getTree\").activateKey(\"".$treeArray[0]['key']."\");");
    }
}