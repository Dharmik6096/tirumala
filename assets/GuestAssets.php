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
class GuestAssets extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/pcdf/assets/css/font-awesome.css',
        'themes/pcdf/assets/css/pageloader.css',
        'themes/pcdf/assets/css/style.css',
        'themes/pcdf/assets/css/font.css',
    ];
    public $js = [
        'themes/pcdf/assets/js/bootstrap.js',
        'themes/pcdf/assets/js/liveloader.js',
        'themes/pcdf/assets/js/bootbox.min.js',
        'themes/pcdf/assets/js/script.js',
        'themes/pcdf/assets/js/style.js',
        'themes/pcdf/assets/js/shortcut_bind.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
