<?php

use app\modules\changelog\components\LastUpdateWidget;

$eipl_code = Yii::$app->session->get('eiplCode');
$footer_label = 'Developed by @Everest Instruments Pvt. Ltd.';
$footer = ($eipl_code == 'SAAHAJ') ? '' : $footer_label;
?>
<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4">
                <p>Copyright <?= date('Y') ?> © Everest Instruments Pvt. Ltd., All Rights Reserved</p>
            </div>
            <div class="col-sm-4 text-center">
                <p>
                    <?= LastUpdateWidget::widget(); ?>
                </p>
            </div>
            <div class="col-sm-4 text-right">
                <p><?= $footer ?></p>
            </div>
        </div>
    </div>
</footer>