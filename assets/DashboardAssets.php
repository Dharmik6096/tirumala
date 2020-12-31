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
        'themes/pcdf/assets/css/font-awesome.css',
        'themes/pcdf/assets/css/pageloader.css',
        'themes/pcdf/assets/css/fullcalendar.min.css',
        'themes/pcdf/assets/css/style.css',
        'themes/pcdf/assets/css/font.css',
    ];
    public $js = [
        'themes/pcdf/assets/js/bootstrap.js',
        'themes/pcdf/assets/js/liveloader.js',
        'themes/pcdf/assets/js/shortcut_bind.js',
        'themes/pcdf/assets/js/jquery.shortcuts.js',
        'themes/pcdf/assets/js/gstatic.js',
        'themes/pcdf/assets/js/highcharts.js',
//        'themes/pcdf/assets/js/exporting.js',
        'themes/pcdf/assets/js/bootbox.min.js',
//        'themes/pcdf/assets/js/vmenuModule.js',
        'themes/pcdf/assets/js/snfcalc.js',
        'themes/pcdf/assets/js/moment.js',
        'themes/pcdf/assets/js/fullcalendar.min.js',
        'themes/pcdf/assets/js/script.js',
        'themes/pcdf/assets/js/style.js',
        'themes/pcdf/assets/js/js.cookie.js',
        'themes/pcdf/assets/js/jquery.CongelarFilaColumna.js',
        'themes/pcdf/assets/js/html2excel.js',
        'vendor/bower-asset/jquery-ui/ui/minified/core.min.js',
        'vendor/bower-asset/jquery-ui/ui/minified/widget.min.js',
        'vendor/bower-asset/jquery-ui/ui/minified/mouse.min.js',
        'vendor/bower-asset/jquery-ui/ui/minified/sortable.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
