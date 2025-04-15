<?php

use yii\helpers\Html;
?>
<div class="panel panel-default panel-grid panel-main hide-grid-settings">
    <div class="panel-body">
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading mt_0">Material Receipt Attachment</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('_attachment_list', [
                    'attachment' => $attachment,
                    'receipt_attachment' => $receipt_attachment
                ])
                ?>
            </div>
        </div>
    </div>
</div>