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
class LoginAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/pcdf/assets/css/font-awesome.css',
//        'themes/pcdf/assets/css/pageloader.css',
        'themes/pcdf/assets/css/style.css',
    ];
    public $js = [
        'themes/pcdf/assets/js/liveloaderLogin.js',
        'themes/pcdf/assets/js/style.js',
        'themes/pcdf/assets/js/bootbox.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function init() {
        parent::init();
        \Yii::$app->general->setDesignTheme($this);
    }

}
