<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
?>

<div class="modal modal-default fade in" id="SendMailModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Send Email Notification') ?></h4>
            </div>
            <div class="modal-body">
                <?php
                $form = ActiveForm::begin([
                            'options' => [
                                'class' => 'form-group popup-form',
                                'id' => 'send-mail-form',
                            ],
                            'action' => Url::to(['send-mail', 'alert_notification_id' => $model->alert_notification_id]),
                            'enableAjaxValidation' => true,
                ]);
                ?>
                <div class="row">
                    <div class="col-sm-12">
                        <?= $form->field($model, 'mail_receiver_detail')->textInput() ?>
                    </div>
                    <div class="col-sm-12">
                        <?= $form->field($model, 'header_info')->textInput(['readonly' => true]) ?>
                    </div>
                    <div class="col-sm-12">
                        <?php
                        $model->message = str_replace(['<br/>'], "\n", $model->message);
                        ?>
                        <?= $form->field($model, 'message')->textarea(['readonly' => true]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary', 'id' => 'send-mail-btn']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$script = "
$(document).ready(function(){
    $('#SendMailModal').modal('show'); 
});
";
$this->registerJs($script, View::POS_END, 'send-mail-modal-script');
?>
