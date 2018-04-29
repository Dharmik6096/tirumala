<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
//        'themes/nddb/assets/css/bootstrap.css',
        'themes/pcdf/assets/css/font-awesome.css',
        'themes/pcdf/assets/css/pageloader.css',
        'themes/pcdf/assets/css/style.css',
        'themes/pcdf/assets/css/print.css',
        'themes/pcdf/assets/css/font.css',
        'vendor/bower/jquery-ui/themes/smoothness/jquery-ui.min.css',
    ];
    public $js = [
//        'themes/nddb/assets/js/bootstrap.js',
        'themes/pcdf/assets/js/liveloader.js',
        'themes/pcdf/assets/js/snfcalc.js',
        'themes/pcdf/assets/js/shortcut_bind.js',
        'themes/pcdf/assets/js/jquery.shortcuts.js',
        'themes/pcdf/assets/js/bootbox.min.js',
//        'themes/pcdf/assets/js/vmenuModule.js',
        'themes/pcdf/assets/js/script.js',
        'themes/pcdf/assets/js/style.js',
        'vendor/bower/jquery-ui/ui/minified/core.min.js',
        'vendor/bower/jquery-ui/ui/minified/widget.min.js',
        'vendor/bower/jquery-ui/ui/minified/mouse.min.js',
        'vendor/bower/jquery-ui/ui/minified/sortable.min.js'
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
