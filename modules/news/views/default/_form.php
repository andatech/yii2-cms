<?php
$fromModule = $this->context->module->id;
//include_once realpath($this->context->masterViewsDir).'/'.basename(__DIR__).'/'.basename(__FILE__);
$fileView = realpath($this->context->masterViewsDir).'/'.basename(__DIR__).'/'.basename(__FILE__);

//echo $fileView;
echo $this->render('@anda/cms/modules/post/views/default/'.basename(__FILE__),['model' => $model, 'treeArray' => $treeArray]);