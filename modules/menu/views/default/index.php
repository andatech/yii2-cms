<?php
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php

/* @var $this yii\web\View */

$this->title = ucfirst($this->context->module->id);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-index">
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <?= Html::button('<i class="fa fa-plus"></i> '.Yii::t('andacms/module','Create Root'), ['id' =>'create-root', 'class' => 'btn btn-primary btn-flat']); ?>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Roots</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <?php foreach ($roots as $root) : ?>
                            <li<?= (intval(Yii::$app->request->get('root')) === intval($root->id)) ? ' class="active"' : ''; ?>>
                                <?= Html::a('<i class="fa fa-caret-right"></i> '.$root->title, ['index', 'root' => $root->root]) ?>

                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-sm-9">
            <div id="form-tree">
                <?php
                if (count($child) > 0) {
                    echo \wbraganca\fancytree\FancytreeWidget::widget([
                        'id' => 'menu',
                        'options' =>[
                            'source' => $child,
//                            'activate' => new JsExpression('function(event, data) {
//    var node = data.node;
//    $.get( "'.Url::to(['update']).'", { id: node.key } )
//        .done(function( data ) {
//            $("#form-tree").html(data);
//    });
//}'),
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
                }else{
                    echo '';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$js[] = "$(document).on('click', '#create-root', function(){
    $.get('".Url::to(['create'])."').done(function(data){
        $('#form-tree').html(data);
        $('#menu-name').focus();
    });
});";

$js_bk[] = "$(document).on('click', '#create-sub', function(){
    var node = $('#fancyree_menu').fancytree('getActiveNode');
      if( node ){
//        alert('Currently active: ' + node.key);
        $.get('".Url::to(['create'])."', { parent_id: node.key }).done(function(data){
            $('#form-tree').html(data);
            $('#menu-name').focus();
        });
      }else{
        alert('No active node.');
      }
});";

$js[] = "$('#fancyree_menu').contextmenu({
    delegate: 'span.fancytree-title',
    //menu: '#options',
    menu: [
        {title: 'Update', cmd: 'update', uiIcon: 'ui-icon-pencil'},
        {title: 'Create Child', cmd: 'create', uiIcon: 'ui-icon-plusthick'},
        {title: '----'},
        {title: 'Delete', cmd: 'delete', uiIcon: 'ui-icon-trash', disabled: false },
    ],
    beforeOpen: function(event, ui) {
        var node = $.ui.fancytree.getNode(ui.target);
        // Modify menu entries depending on node status
        
        //if(node.key == '".Yii::$app->request->get('root')."'){
        //    $('#fancyree_menu').contextmenu('enableEntry', 'delete', false);
        //}else{
        //    $('#fancyree_menu').contextmenu('enableEntry', 'delete', true);
        //}
        
        // Show/hide single entries
        //$('#tree').contextmenu('showEntry', 'cut', false);

        // Activate node on right-click
        node.setActive();
    },
    select: function(event, ui) {
        var node = $.ui.fancytree.getNode(ui.target);
        //alert('select ' + ui.cmd + ' on ' + node);
        switch(ui.cmd) {
            case 'create' : 
                window.location = '".Url::to('create')."?parent_id='+node.key;
                break;
            case 'update' : 
                window.location = '".Url::to('update')."?id='+node.key;
                break;
            case 'delete' : 
                if (yii.confirm('WARNING Removing the ' + node.title + ' will be a submenu.')){
                    $.post( '".Url::to('delete')."?id='+node.key, { id: node.key }, function( data ) { window.location.reload(); });
                }
                break;
            default : 
                break;
        }
    }
});";


$this->registerJs(implode("\n", $js));
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@anda/cms/clients');
$this->registerJsFile($directoryAsset."/js/jquery.ui-contextmenu.min.js", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()
    ]
]);
?>
<?php
if(Yii::$app->request->get('id')) {
    $this->registerJs("$('#fancyree_menu').fancytree('getTree').activateKey('".Yii::$app->request->get('id')."');");
}