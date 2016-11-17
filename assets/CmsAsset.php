<?php
namespace anda\cms\assets;


use yii\web\AssetBundle;

/**
 * AdminLte AssetBundle
 * @since 0.1
 */
class CmsAsset extends AssetBundle
{
    public $sourcePath = '@anda/cms/clients';
    public $css = [
    	'css/adminlte2-fixed.css'
    ];
    public $js = [
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];
}
