<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css?a=1',
        'css/style.css?a=44',
        'css/custom.css',
        'css/responsive.css?r=45',
        'css/lightslider.min.css',
        'css/sweetalert.css',
    ];
    public $js = [
        'js/bootstrap.min.js?a=1',
        'js/lightslider.min.js',
        'js/script.js?s=3',
        'js/sweetalert.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}
