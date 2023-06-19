<?php 
use app\modules\changelog\components\LastUpdateWidget;

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
                <p>Developed by @Everest Instruments Pvt. Ltd.</p>
            </div>
        </div>
    </div>
</footer>