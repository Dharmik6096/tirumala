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
        'themes/emilk/assets/emoji/css/emoji.css',
        'themes/emilk/assets/emoji/css/emoji.css.map',
        'themes/emilk/assets/emoji/css/chatboard.css',
    ];
    public $js = [
        'themes/emilk/assets/emoji/js/config.min.js',
        'themes/emilk/assets/emoji/js/emoji-picker.min.js',
        'themes/emilk/assets/emoji/js/jquery.emojiarea.min.js',
        'themes/emilk/assets/emoji/js/util.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
