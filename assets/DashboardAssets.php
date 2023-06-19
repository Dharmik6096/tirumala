<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

//test commit
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DashboardAssets extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
//        'themes/nddb/assets/css/bootstrap.css',
        'themes/emilk/assets/css/font-awesome.css',
        // 'themes/emilk/assets/css/pageloader.css',
        'themes/emilk/assets/css/fullcalendar.min.css',
        'themes/emilk/assets/css/style.css',
        'themes/emilk/assets/css/font.css',
    ];
    public $js = [
        'themes/emilk/assets/js/bootstrap.js',
        // 'themes/emilk/assets/js/liveloader.js',
        'themes/emilk/assets/js/shortcut_bind.js',
        'themes/emilk/assets/js/jquery.shortcuts.js',
        'themes/emilk/assets/js/gstatic.js',
        'themes/emilk/assets/js/highcharts.js',
//        'themes/emilk/assets/js/exporting.js',
        'themes/emilk/assets/js/bootbox.min.js',
//        'themes/emilk/assets/js/vmenuModule.js',
        'themes/emilk/assets/js/snfcalc.js',
        'themes/emilk/assets/js/moment.js',
        'themes/emilk/assets/js/fullcalendar.min.js',
        'themes/emilk/assets/js/script.js',
        'themes/emilk/assets/js/style.js',
        'themes/emilk/assets/js/js.cookie.js',
        'themes/emilk/assets/js/jquery.CongelarFilaColumna.js',
        'themes/emilk/assets/js/html2excel.js',
        'themes/emilk/assets/js/datatables.min.js',
        'vendor/bower-asset/jquery-ui/ui/minified/core.min.js',
        'vendor/bower-asset/jquery-ui/ui/minified/widget.min.js',
        'vendor/bower-asset/jquery-ui/ui/minified/mouse.min.js',
        'vendor/bower-asset/jquery-ui/ui/minified/sortable.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];

}
