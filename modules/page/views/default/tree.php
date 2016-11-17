<?php
use yii\web\JsExpression;
?>
<?php
echo \wbraganca\fancytree\FancytreeWidget::widget([
    'id' => 'page',
    'options' =>[
        'source' => $treeArray,
        'extensions' => ['dnd'],
        'dnd' => [
            'preventVoidMoves' => true,
            'preventRecursiveMoves' => true,
            'autoExpandMS' => 400,
            'dragStart' => new JsExpression('function(node, data) {
                return true;
                    }'),
            'dragEnter' => new JsExpression('function(node, data) {
                return true;
            }'),
            'dragDrop' => new JsExpression('function(node, data) {
                $.get( "'.\yii\helpers\Url::to(['tree']).'", { id: data.otherNode.key, mode: data.hitMode, targetId: data.node.key } )
                    .done(function( dataAjax ) {
                        if(dataAjax.process){
                            data.otherNode.moveTo(node, data.hitMode);
                        }
                    }, "json");
                }'
            ),
        ],
    ]
]);
?>