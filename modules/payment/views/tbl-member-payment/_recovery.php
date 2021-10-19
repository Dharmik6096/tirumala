<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblProject */
/* @var $form yii\widgets\ActiveForm */
$this->title = Yii::t('app', 'Member Recovery');
$Header = substr($aliasmodel->member_code, -4) . ' > ' . Yii::$app->general->getforeignkey($aliasmodel->memberCode, 'member_name');
?>
<div class="modal modal-default fade" id="recoverOtherMemberModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <!--<h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Recovery') ?></h4>-->
                <div class="panel-heading">
                    <?= $this->title . ' (' . $Header . ')' ?>   
                    <div id="total-payment">
                        Adjust Recovery :: <?= $aliasmodel->adjust_recovery; ?>
                    </div>
                </div>   
            </div>
            <div class='row pad-10'>
                <div class="col-md-12">
                    <?php
                    $form = ActiveForm::begin(['options' => [
                                    'class' => 'form-group popup-form',
                                    'id' => 'recovery-form',
                                ],
                                'action' => Url::to(['/payment/tbl-member-payment/recovery-adjust'])
                    ]);
                    ?>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered web_theme_table table-striped table-main table-language">
                                <thead>
                                    <tr><?= Html::activeHiddenInput($aliasmodel, 'member_payment_alias_code', ['id' => 'Code']); ?> 
                                    <tr><?= Html::activeHiddenInput($aliasmodel, 'adjust_recovery', ['id' => 'adjustRecovery']); ?> 
                                    <tr><?= Html::activeHiddenInput($aliasmodel, 'adjust_recovery', ['id' => 'adjustRecovery']); ?> 
                                        <th><?php echo Yii::t('app', 'Member Code') ?></th>
                                        <th><?php echo $aliasmodel->getAttributeLabel('member_code') ?></th>
                                        <th><?php echo Yii::t('app', 'Old Recovery') ?></th>
                                        <th><?php echo Yii::t('app', 'New Recovery') ?></th>
                                        <th><?php echo Yii::t('app', 'Net Payble') ?></th>
                                    </tr>
                                </thead>
                                <tbody class="appendRaw">
                                    <?php
                                    if (!empty($recoverMember)) {
                                        foreach ($recoverMember as $recoveryData) {
                                            ?>  
                                            <tr class="<?= $recoveryData->member_payment_alias_code ?>">
                                                <?= Html::activeHiddenInput($recoveryData, '[' . $recoveryData->member_payment_alias_code . ']member_payment_alias_code'); ?> 
                                                <td class="member_code"><?= substr($recoveryData->member_code, -4) ?></td>
                                                <td class="member_name"><?= Yii::$app->general->getforeignkey($recoveryData->memberCode, 'member_name'); ?></td>
                                                <td class="old_recovery each_old_recover"><?= $form->field($recoveryData, '[' . $recoveryData->member_payment_alias_code . ']old_recovery')->textInput(['class' => 'old_recovery form-control', "readonly" => TRUE, 'value' => $recoveryData->recovery])->label(FALSE); ?></td>
                                                <td class="new_recovery each_new_recover"><?= $form->field($recoveryData, '[' . $recoveryData->member_payment_alias_code . ']recovery')->textInput(['class' => 'new_recovery form-control number-validate'])->label(FALSE); ?></td>
                                                <td class="final_amount"><?= $form->field($recoveryData, '[' . $recoveryData->member_payment_alias_code . ']final_amount')->textInput(['class' => 'final_amount form-control', "readonly" => TRUE])->label(FALSE); ?></td>
                                            </tr>
                                            <?php
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
                            $requestUrl = \Yii::$app->request->getHostInfo() . Yii::$app->request->url;
                            AjaxSubmitButton::begin([
                                'label' => Yii::t('app', 'Save'),
                                'id' => 'recoveryBtn',
                                'ajaxOptions' => [
                                    'type' => 'POST',
                                    'url' => Url::to(['recovery-adjust']),
                                    'beforeSend' => new JsExpression("function(data){
                                            var AdjustRec = '" . $aliasmodel->adjust_recovery . "';
                                                var totalOldRec=0;
                                                var totalNewRec=0;
                                            $('.each_old_recover').each(function(){
                                                 var trClass = $(this).parents('tr').attr('class');
                                                 var oldRec = parseFloat($('#tblmemberpaymentalias-'+trClass+'-old_recovery').val());
                                                    if(totalOldRec == '' ||  isNaN(totalOldRec)){
                                                            totalOldRec=0;
                                                    }
                                                    totalOldRec=totalOldRec+oldRec;
                                            }); 
                                            $('.each_new_recover').each(function(){
                                                 var trClass = $(this).parents('tr').attr('class');
                                                 var NewRec = parseFloat($('#tblmemberpaymentalias-'+trClass+'-recovery').val());
                                                    if(NewRec == '' ||  isNaN(NewRec)){
                                                            NewRec=0;
                                                    }
                                                    totalNewRec=totalNewRec+NewRec;
                                            }); 
                                            if(AdjustRec != totalNewRec){
                                                bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Total Recovery must be equal to Adjust Recovery.</span>',function(){
//                                                    bootbox.hideAll();
                                                });
                                            return false;
                                            }
//                                                $('#loadercontent').show();
//                                                $('#pageloader').show();
                                                }"),
                                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
//                                                                $("#loadercontent").hide();
//                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    bootbox.alert("<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-info\"><i class=\"fa fa-info\"></i></div><span>"+data.msg+" </span></div></div>");
                                                                    $("#recoverOtherMemberModal").modal("toggle"); 
//                                                                    location.reload();
//                                                                    window.location = "' . $requestUrl . '";
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                }else{
//                                                                    $("#pageloader").hide();
//                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
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

                            <?php // Html::resetButton('Reset', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$script = "
    var AdjustRec = '" . $aliasmodel->adjust_recovery . "';
        $('.new_recovery').val('');
      ";
$this->registerJs($script, View::POS_END, date('ymdhis'));
