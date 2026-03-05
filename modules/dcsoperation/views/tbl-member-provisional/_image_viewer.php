<?php

use yii\web\View;
use yii\web\JqueryAsset;

$this->registerCssFile(Yii::getAlias('@web') . '/themes/emilk/assets/css/viewer.min.css', [
    'depends' => [JqueryAsset::class],
]);

$this->registerJsFile(Yii::getAlias('@web') . '/themes/emilk/assets/js/viewer.min.js', [
    'depends' => [JqueryAsset::class],
    'position' => View::POS_END,
]);

$script = "
$(document).ready(function() {
    $(document).on('click', '.image-preview-click', function(e) {
        e.preventDefault();
        var viewer = new Viewer(this, {
            className: 'provisional-viewer',
            navbar: false,
            title: true,
            toolbar: {
                zoomIn: 1,
                zoomOut: 1,
                oneToOne: 1,
                reset: 1,
                prev: 0,
                play: {
                    show: 1,
                    size: 'large'
                },
                next: 0,
                rotateLeft: 1,
                rotateRight: 1,
                flipHorizontal: 1,
                flipVertical: 1
            },
            hidden: function() {
                viewer.destroy();
            }
        });
        viewer.show();
    });
});
";

$this->registerJs($script, View::POS_END, 'common-image-viewer');
?>
