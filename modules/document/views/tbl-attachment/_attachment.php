<?php

use yii\web\View;

$this->registerCssFile(Yii::getAlias('@web') . '/themes/emilk/assets/css/viewer.min.css', [
    'depends' => [\yii\web\JqueryAsset::class],
]);

$this->registerJsFile(Yii::getAlias('@web') . '/themes/emilk/assets/js/viewer.min.js', [
    'depends' => [\yii\web\JqueryAsset::class],
    'position' => \yii\web\View::POS_END,
]);
?>
<div class="attachment">
    <h2 class="attachment_head">Attachments</h2>
    <div class="attachment_section">
        <?php if (!empty($attachments)): ?>
            <div id="image-gallery">
                <?php foreach ($attachments as $attachment): ?>
                    <?php
                    $attachmentPath = $attachment->attachment;
                    $extension = pathinfo($attachmentPath, PATHINFO_EXTENSION);
                    $docName = Yii::$app->general->getforeignkey($attachment->docId, 'doc_name');
                    ?>
                    <?php if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <div class="inline_block">
                            <p class="dashboard_filter_form"><?= $docName ?></p>
                            <div class="image_section">
                                <img src="<?= $attachmentPath ?>" class="image-viewer" alt="<?php echo $docName; ?>" style="max-width:100%; height:auto; cursor:pointer;">
                            </div>
                        </div>
                    <?php else: ?>
                        <p>Attachment is not an image.</p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No Attachment</p>
        <?php endif; ?>
    </div>
</div>

<?php
$script = <<<JS
document.addEventListener("DOMContentLoaded", function () {
    var viewer = new Viewer(document.getElementById('image-gallery'), {
        navbar: true,
        toolbar: {
            zoomIn: 1,
            zoomOut: 1,
            oneToOne: 1,
            reset: 1,
            prev: 1,
            play: {
                show: 1,
                size: 'large'
            },
            next: 1,
            rotateLeft: 1,
            rotateRight: 1,
            flipHorizontal: 1,
            flipVertical: 1
        },  
        hidden: function () {
            document.body.classList.remove('viewer-blur');
        },
        shown: function () {
            document.body.classList.add('viewer-blur');
        }
    });
});
JS;

$this->registerJs($script, View::POS_END, 'attachment');
?>