<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Vendor Recovery');
$society_name = Yii::$app->general->getCustomer($model, $model->customer_type) . ' (' .
        Yii::$app->general->getCustomer($model, $model->customer_type, TRUE) . '-' .
        Yii::$app->general->getforeignkey($model->customerType, 'customer_desc') . ')';
$adjust_recovery = abs($model->net_payable);
?>
<div class="modal modal-default fade" id="RecoveryModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel">
                    <?= Yii::t('app', 'Recovery Detail of ') . $society_name ?><br/>
                    <?= Yii::t('app', 'Total Amount : ') . $adjust_recovery
                    ?>
                </h4>
            </div>
            <div class='row pad-10'>
                <div class="col-md-12">
                    <?php
                    $form = ActiveForm::begin(['options' => [
                                    'class' => 'form-group popup-form',
                                    'id' => 'recovery-form',
                                ],
                                'action' => Url::to(['/payment/tbl-vsp-payment/add-recovery', 'code' => $model->vsp_payment_code])
                    ]);
                    echo $form->errorSummary($model);
                    ?>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered web_theme_table table-striped table-main table-language">
                                <thead>
                                    <tr>
                                        <th><?php echo Yii::t('app', 'Code Ex.') ?></th>
                                        <th><?php echo Yii::t('app', 'Name') ?></th>
                                        <th><?php echo Yii::t('app', 'Recovery') ?></th>
                                        <th><?php echo Yii::t('app', 'Old Recovery(-)') ?></th>
                                        <th><?php echo Yii::t('app', 'New Recovery(+)') ?></th>
                                        <th><?php echo Yii::t('app', 'Total Recovery') ?></th>
                                    </tr>
                                </thead>
                                <tbody class="appendRaw">
                                    <?php
                                    $index = 0;
                                    if (!empty($recoveryData)) {
                                        foreach ($recoveryData as $data) {
                                            ?>  
                                            <tr>
                                                <td><?= Yii::$app->general->getCustomer($data, $data->customer_type, TRUE) ?></td>
                                                <td><?= Yii::$app->general->getCustomer($data, $data->customer_type) ?></td>
                                                <td class="recovery-amount"><?= $data->recovery ?></td>
                                                <td class="old-recovery"><?= empty($data->old_recovery) ? 0.00 : $data->old_recovery ?></td>
                                                <td class="no_padding_input hide_help_block"><?= Html::activeHiddenInput($data, '[' . $index . ']vsp_payment_code', ['value' => $data->vsp_payment_code]) . $form->field($data, '[' . $index . ']new_recovery')->textInput(['value' => empty($data->old_recovery) ? 0.00 : $data->old_recovery, 'class' => 'number-validate new-recovery cal-recovery form-control',])->label(FALSE); ?></td>
                                                <td class="total-recovery"><?= $data->recovery ?></td>
                                            </tr>
                                            <?php
                                            $index++;
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer mt10 col-sm-12">
                        <div class="col-md-12 top-bottom-15 padding-50">
                            <?php
                            AjaxSubmitButton::begin([
                                'label' => Yii::t('app', 'Save'),
                                'id' => 'recoveryBtn',
                                'ajaxOptions' => [
                                    'type' => 'POST',
                                    'url' => Url::to(['/payment/tbl-vsp-payment/add-recovery', 'code' => $model->vsp_payment_code]),
                                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                if (data.status == "success"){ 
                                                                    bootbox.alert("<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-info\"><i class=\"fa fa-info\"></i></div><span>"+data.msg+" </span></div></div>", function(){
                                                                          location.reload(); 
                                                                    });
                                                                 }else{
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        if(key=="msg" && key != null){   
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                        }
                                                                    });
                                                                    $(".error-summary").show();
                                                                   
                                                                }
                                                 }'),
                                ],
                                'options' => ['class' => 'btn btn-default btn-raised',
                                    'type' => 'submit'],
                            ]);
                            AjaxSubmitButton::end();
                            ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
