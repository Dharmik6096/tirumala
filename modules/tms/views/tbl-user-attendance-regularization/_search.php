<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblUserAttendanceRegularization */
/* @var $form yii\widgets\ActiveForm */

$actual_in_time = Yii::$app->controls->view_time($regularizationModelData->actual_in_time);
$actual_out_time = Yii::$app->controls->view_time($regularizationModelData->actual_out_time);
?>
<div class="modal modal-default fade" id="RejectedAttendanceModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">× </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Reject Attendance Regularization') ?></h4>
            </div>

            <div class='row pad-10'>
                <div class="col-sm-12">
                    <?php
                    $form = ActiveForm::begin([
                                'options' => [
                                    'class' => 'form-group popup-form',
                                    'id' => 'reject-attendance-form',
                                ],
                                'action' => Url::to(['/tms/tbl-user-attendance-regularization/reject-regularization', 'id' => $regularizationModelData->process_approval_code])
                    ]);
                    ?>

                    <div class="col-sm-3">
                        <?= Yii::$app->controls->date($regularizationModelData, $form, 'apply_date', '', FALSE, FALSE, TRUE); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($regularizationModelData, 'actual_in_time')->textInput(['disabled' => true]) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($regularizationModelData, 'actual_out_time')->textInput(['disabled' => true]) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($regularizationModelData, 'requested_in_time')->textInput(['value' => Yii::$app->controls->view_time($regularizationModelData->requested_in_time), 'disabled' => true]) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($regularizationModelData, 'requested_out_time')->textInput(['value' => Yii::$app->controls->view_time($regularizationModelData->requested_out_time), 'disabled' => true]) ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <?= $form->field($regularizationModelData, 'regularization_reason')->textarea(['disabled' => true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($regularizationModelData, 'rejection_remark')->textarea() ?>
                    </div>
                    <?= Html::activeHiddenInput($regularizationModelData, 'regularization_code'); ?>

                    <div class="modal-footer mt10 col-sm-12">
                        <div class="col-md-12 top-bottom-15 padding-50">
                            <?php
                            AjaxSubmitButton::begin([
                                'label' => Yii::t('app', 'Reject'),
                                'ajaxOptions' => [
                                    'type' => 'POST',
                                    'url' => Url::to(['reject-regularization', 'id' => $regularizationModelData->process_approval_code]),
                                    'beforeSend' => new JsExpression("function(data){
                                                }"),
                                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                if (data.status == "success"){ 
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                } else if (data.msg && data.msg.trim() !== ""){
                                                                        bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                                } else {
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();

                                                                    $.each(data, function(key, val) {
                                                                        $(".field-"+key+" .help-block").text(val);
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                    }'),
                                ],
                                'options' => [
                                    'class' => 'btn-login btn btn-default btn-raised',
                                    'type' => 'submit'
                                ],
                            ]);
                            AjaxSubmitButton::end();
                            ?>
                            <?= Html::resetButton('Reset', ['class' => 'btn-login btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>