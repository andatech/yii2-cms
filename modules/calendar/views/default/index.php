<?php
$fromModule = $this->context->module->id;

$gridColumnsArray = [
    'id',
    'title',
    'category_id',
    'created_by',
    'created_at',
    'publish_up',
    'publish_down',
    ['class' => '\kartik\grid\ActionColumn',]
];
include_once realpath($this->context->masterViewsDir).'/'.basename(__DIR__).'/'.basename(__FILE__);