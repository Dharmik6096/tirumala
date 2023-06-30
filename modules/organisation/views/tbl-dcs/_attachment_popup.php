<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kato\DropZone;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;

$this->title = 'Attachments';
?>

<div class="modal fade in popup_modal" id="AttachmentViewModal" role="dialog">
    <div class="modal-dialog w750 hide-grid-settings hide-grid-search">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal">×</button>
                <h4 class="modal-title" id="modal-title"><?= $this->title ?></h4>
            </div>
            <div class="modal-body full_width_grid" id="modal-body">
                <div class="row">
                <?php
                    foreach ($attachment as $data) {
                        ?>
                        <div class="col-sm-1">
                            <div class="col-sm-12">
                                <?php
                                echo Html::a(Html::img($data->thumbnail, ['class' => 'thumbnail_image_large', 'alt' => '']), $data->attachment, [
                                    'class' => 'image-popup-no-margins',
                                    'download' => $data->attachment_type,
                                ]);
                                ?>
                            </div>
                        </div>
                    <?php }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>