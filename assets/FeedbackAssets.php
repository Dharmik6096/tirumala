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
class FeedbackAssets extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/pcdf/assets/emoji/css/emoji.css',
        'themes/pcdf/assets/emoji/css/emoji.css.map',
        'themes/pcdf/assets/emoji/css/chatboard.css',
    ];
    public $js = [
        'themes/pcdf/assets/emoji/js/config.min.js',
        'themes/pcdf/assets/emoji/js/emoji-picker.min.js',
        'themes/pcdf/assets/emoji/js/jquery.emojiarea.min.js',
        'themes/pcdf/assets/emoji/js/util.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
