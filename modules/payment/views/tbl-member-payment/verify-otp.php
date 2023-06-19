<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
?>

<div class="modal modal-default fade" id="OtpModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Verify OTP'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo $form->errorSummary($model, ['id' => 'error-summary']); ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'otp_code')->textInput(['maxlength' => 4, 'placeholder' => 'Enter OTP'])->label(false); ?>
                    </div>
                    <div class="col-md-9 mt10">
                        <?= Html::button(Yii::t('app', 'Verify'), ['class' => 'btn btn-primary verify']) ?>
                        <button type="button" class="btn btn-danger btn-raised close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



