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
        'themes/emilk/assets/css/font-awesome.css',
        'themes/emilk/assets/css/pageloader.css',
        'themes/emilk/assets/css/style.css',
        'themes/emilk/assets/css/print.css',
        'themes/emilk/assets/css/font.css',
        // 'vendor/bower-asset/jquery-ui/themes/smoothness/jquery-ui.min.css',
    ];
    public $js = [
//        'themes/nddb/assets/js/bootstrap.js',
        'themes/emilk/assets/js/liveloader.js',
        'themes/emilk/assets/js/snfcalc.js',
        'themes/emilk/assets/js/shortcut_bind.js',
        'themes/emilk/assets/js/jquery.shortcuts.js',
        'themes/emilk/assets/js/bootbox.min.js',
        'themes/emilk/assets/js/vmenuModule.js',
        'themes/emilk/assets/js/script.js',
        'themes/emilk/assets/js/fixed_table.js',
        'themes/emilk/assets/js/style.js',
        'themes/emilk/assets/js/jquery.CongelarFilaColumna.js',
        'themes/emilk/assets/js/html2excel.js',
        // 'vendor/bower-asset/jquery-ui/ui/minified/core.min.js',
        // 'vendor/bower-asset/jquery-ui/ui/minified/widget.min.js',
        // 'vendor/bower-asset/jquery-ui/ui/minified/mouse.min.js',
        // 'vendor/bower-asset/jquery-ui/ui/minified/sortable.min.js'
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
