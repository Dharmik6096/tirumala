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
        'themes/emilk/assets/css/font.css',
        'themes/emilk/assets/css/fontawesome-all.min.css',
        'themes/emilk/assets/css/pageloader.css',
        'themes/emilk/assets/css/style.css'
    ];
    public $js = [
        'themes/emilk/assets/js/bootstrap.js',
        'themes/emilk/assets/js/liveloader.js',
        'themes/emilk/assets/js/bootbox.min.js',
        'themes/emilk/assets/js/script.js',
        'themes/emilk/assets/js/style.js',
        'themes/emilk/assets/js/shortcut_bind.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];

    public function init() {
        parent::init();
        \Yii::$app->general->setDesignTheme($this, \Yii::$app->session['eiplCode']);
    }

}
