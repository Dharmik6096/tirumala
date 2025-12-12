<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Member Payment Process : Step 3');
$fromDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($aliasModel->paymentCycleCode, 'from_date'));
$toDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($aliasModel->paymentCycleCode, 'to_date'));

$bmc_info = Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'ref_code') . ' > ' . Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_name') . ' > ' .
        $fromDate . ' to ' . $toDate;
$message = Yii::t('app', 'Payment data of  all society will be locked and considered as final for ' . Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_name') . ' (' . $fromDate . ' to ' . $toDate . '). Are you sure ?');
$config = (isset(Yii::$app->session->get('unionConfig')[$aliasModel->union_code]['recovery_from_other_member']) && Yii::$app->session->get('unionConfig')[$aliasModel->union_code]['recovery_from_other_member'] == 1) ? TRUE : FALSE;
$milk_short_recovery_member = isset(Yii::$app->session->get('unionConfig')[$aliasModel->union_code]['milk_short_recovery_member']) ? Yii::$app->session->get('unionConfig')[$aliasModel->union_code]['milk_short_recovery_member'] : 0;
$urlForPost = ['member-payment-adjust', 'union_code' => $model->union_code, 'payment_cycle_code' => $model->payment_cycle_code, 'plant_code' => $model->plant_code, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code];

$member_payment_hold_type = Yii::$app->general->getUnionConfiguration($model->union_code, 'member_payment_hold_type', 'PORTAL') > 0 ? true : false;

$shortage_info = '';
$shortage_pending_info = '';
$shortage_amount = 0;

if ($milk_short_recovery_member == '1') {
    $shortage_amount_other = Yii::$app->general->getforeignkey($aliasModel->shortageRecoveryOtherMember, 'recovery_amount');
    $shortage_amount_mpg = Yii::$app->general->getforeignkey($aliasModel->shortageRecoveryMpgMember, 'recovery_amount');
    $shortage_amount = number_format(((float)$shortage_amount_other + (float)$shortage_amount_mpg), 2, '.','');
    $shortage_info = 'Shortage Amount :: ' . $shortage_amount;
    $shortage_pending_info .= '<span id="total-shortage-amount" class="ml-50">Pending Shortage Amount :: ' . $shortage_amount . '</span>';
    echo Html::hiddenInput('pending_shortage_amount', $shortage_amount, ['class' => 'pending_shortage_amount', 'id' => 'pending_shortage_amount']);
}
$is_bank_integrated = Yii::$app->general->getUnionConfiguration($model->union_code, 'is_bank_integrated', 'PORTAL') == 1 ? true : false;
?>
<?php
//$array = $dataProvider->getModels();
$array = $dataProvider; //->getModels();
$tot_amt = array_sum(array_map(function ($array) {
            return $array['final_amount'];
        }, $array));
?>
<div class="modal modal-default fade" id="MemberPaymentAdjustmentModel" role="dialog">
    <div class="modal-dialog width_100-50">

        <?php
        $form = ActiveForm::begin([
                    'id' => 'payment-adjust-member',
                    'validateOnBlur' => TRUE,
                    'validateOnChange' => TRUE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
                    'action' => $urlForPost
        ]);
        ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel">
                    <?= $this->title . ' (' . $bmc_info . ')' ?> 
                    <br/>
                    <?= $shortage_info ?>
                    <?= $shortage_pending_info ?>
                    <div id="total-payment">
                        Total Payable :: <?= $tot_amt; ?>
                    </div>
                </h4>
            </div>
            <div class="popup-header bg_white">

                <div class="table-responsive kv-grid-container">
                    <table class="table table-bordered table-hover kv-grid-table kv-table-wrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= Yii::t('app', 'DCS Code') ?></th>
                                <th><?= Yii::t('app', 'Code Ex.') ?></th>
                                <th><?= Yii::t('app', 'DCS') ?></th>
                                <th class="sticky-column btn-danger"><?= Yii::t('app', 'Member Code') ?></th>
                                <th><?= Yii::t('app', 'Member') ?></th>
                                <?php
                                if($is_bank_integrated){ ?>
                                    <th><?= Yii::t('app', 'IFSC') ?></th>
                                    <th><?= Yii::t('app', 'Bank Account No') ?></th>
                                <?php
                                } ?>
                                <th><?= Yii::t('app', 'KgFAT') ?></th>
                                <th><?= Yii::t('app', 'KgSNF') ?></th>
                                <th><?= Yii::t('app', 'Total Qty') ?></th>
                                <th><?= Yii::t('app', 'Milk Amount(+)') ?></th>
                                <th><?= Yii::t('app', 'Addition(+)') ?></th>
                                <th><?= Yii::t('app', 'Deduction(-)') ?></th>
                                <th><?= Yii::t('app', 'Previous Hold(+)') ?></th>
                                <th><?= Yii::t('app', 'Previous Due(-)') ?></th>
                                <th><?= Yii::t('app', 'Final Pay') ?></th>
                                <?php if ($member_payment_hold_type) { ?>
                                    <th><?= Yii::t('app', 'Hold Type') ?></th>
                                <?php } ?>
                                <th><?= Yii::t('app', 'Hold Amount(-)') ?></th>
                                <th><?= Yii::t('app', 'Additional Pay(+)') ?></th>
                                <?php if ($milk_short_recovery_member == '1') { ?>
                                    <th><?= Yii::t('app', 'Shortage Recovery(-)') ?></th>
                                <?php } ?>
                                <th><?= Yii::t('app', 'Adjust Recovery') ?></th>
                                <th><?= Yii::t('app', 'Recovery') ?></th>
                                <th><?= Yii::t('app', 'Net Payable') ?></th>
                                <th><?= Yii::t('app', 'Remarks') ?></th>
                                <th><?= Yii::t('app', 'Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalQty = 0;
                            $totalMilkAmt = 0;
                            $totalAddition = 0;
                            $totalDeduction = 0;
                            $totalPrevHold = 0;
                            $totalPrevDue = 0;
                            $totalFinalPay = 0;
                            ?>
                            <?php foreach ($dataProvider as $index => $m) { ?>
                                <?php
//                        $dcs = $m->dcsCode;
//                        $member = $m->memberCode;
                                $totalQty = $totalQty + $m['qty'];
                                $totalMilkAmt = $totalMilkAmt + $m['total_amount'];
                                $totalAddition = $totalAddition + $m['total_addition'];
                                $totalDeduction = $totalDeduction + $m['total_deduction'];
                                $totalPrevHold = $totalPrevHold + $m['previous_hold'];
                                $totalPrevDue = $totalPrevDue + $m['previous_due'];
                                $totalFinalPay = $totalFinalPay + $m['net_payable'];
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>                                    
                                    <td><?= $m['ref_code'] ?></td>
                                    <td><?= $m['dcs_code_ex'] ?></td>
                                    <td><?= $m['dcs_name'] ?></td>
                                    <td class="sticky-column"><?= substr($m['member_code'], -4) ?></td>
                                    <td><?= $m['member_name'] ?></td>
                                    <?php
                                    if($is_bank_integrated){ ?>
                                        <td><?= $m['ifsc'] ?></td>
                                        <td><?= $m['bank_account_no'] ?></td>
                                    <?php
                                    } ?>
                                    <td><?= $m['kg_fat'] ?></td>
                                    <td><?= $m['kg_snf'] ?></td>
                                    <td><?= $m['qty'] ?></td>
                                    <td><?= $m['total_amount'] ?></td>
                                    <td><?= $m['total_addition'] ?></td>
                                    <td><?= $m['total_deduction'] ?></td>
                                    <td><?= $m['previous_hold'] ?></td>
                                    <td><?= $m['previous_due'] ?></td>
                                    <td class='final-amount'><?= $m['net_payable'] ?></td>
                                    <?php
                                    if($member_payment_hold_type){ ?>
                                        <td class="no_padding_input hide_help_block">
                                            <?php
                                            $holdTypeData = Yii::$app->dropdown->getRecords('hold_type')['data'];
                                            echo $form->field($model, 'hold_type', ['options' => ['class' => 'hold-type']])->dropDownList(
                                                    $holdTypeData,
                                                    [
                                                'prompt' => Yii::t('app', 'Select'),
                                                'class' => 'hold_type form-control',
                                                'id' => 'tblmemberpaymentalias-hold_type-' . $index,
                                                'name' => 'TblMemberPaymentAlias[hold_type][' . $index . ']',
                                                'options' => [$m['hold_type'] => ['Selected' => true]]
                                                    ]
                                            )->label(false);
                                            ?>
                                        </td>
                                    <?php
                                    } ?>
                                    <td class="no_padding_input hide_help_block">
                                        <?php
                                        echo Html::activeHiddenInput($model, 'member_payment_alias_code[' . $index . ']', ['class' => 'alis_code', 'value' => $m['member_payment_alias_code']]);
                                        echo $form->field($model, 'hold_amount[' . $index . ']')->textInput(['value' => $m['hold_amount'], 'class' => 'number-validate hold-amount cal-amount form-control',])->label(FALSE);
                                        ?>
                                    </td>
                                    <td class="no_padding_input hide_help_block">
                                        <?php
                                        echo Html::hiddenInput('process_lock_flag_member', 'Process', ['class' => 'process_lock_flag_member']);
                                        echo $form->field($model, 'additional_pay[' . $index . ']')->textInput(['value' => $m['additional_pay'], 'class' => 'adjust-amount form-control cal-amount number-validate',])->label(FALSE)
                                        ?>
                                    </td>
                                    <?php if ($milk_short_recovery_member == '1') { ?>
                                        <td class="no_padding_input hide_help_block">
                                            <?php
                                            echo Html::activeHiddenInput($model, 'shortage_amount_old[' . $index . ']', ['class' => 'shortage-amount-old', 'value' => !empty($m['shortage_amount']) ? $m['shortage_amount'] : 0]);
                                            echo $form->field($model, 'shortage_amount[' . $index . ']')->textInput(['value' => $m['shortage_amount'], 'class' => 'shortage-amount form-control cal-amount number-validate',])->label(FALSE);
                                            echo Html::activeHiddenInput($model, 'shortage_head_code[' . $index . ']', ['class' => 'shortage_head', 'value' => $m['shortage_head_code']]);
                                            ?>
                                        </td>
                                    <?php } ?>
                                    <td class="no_padding_input hide_help_block">
                                        <?php
                                        echo Html::activeHiddenInput($model, 'payment_cycle_code[' . $index . ']', ['class' => 'payment_cycle', 'value' => $m['payment_cycle_code']]);
                                        echo Html::activeHiddenInput($model, 'plant_code[' . $index . ']', ['class' => 'plant', 'value' => $m['plant_code']]);
                                        echo Html::activeHiddenInput($model, 'mcc_plant_code[' . $index . ']', ['class' => 'mcc', 'value' => $m['mcc_plant_code']]);
                                        echo Html::activeHiddenInput($model, 'bmc_code[' . $index . ']', ['class' => 'bmc', 'value' => $m['bmc_code']]);
                                        echo Html::activeHiddenInput($model, 'dcs_code[' . $index . ']', ['class' => 'dcs', 'value' => $m['dcs_code']]);
                                        echo Html::activeHiddenInput($model, 'member_code[' . $index . ']', ['class' => 'member', 'value' => $m['member_code']]);
                                        echo $form->field($model, 'adjust_recovery[' . $index . ']')->textInput(['class' => 'adjust-recovery form-control number-validate', 'value' => $m['adjust_recovery']])->label(FALSE)
                                        ?>
                                    </td>
                                    <td class="no_padding_input hide_help_block">
                                        <?php
                                        echo $form->field($model, 'recovery[' . $index . ']')->textInput(['class' => 'recovery form-control', "readOnly" => TRUE, 'value' => $m['recovery']])->label(FALSE);
                                        ?>
                                    </td>
                                    <td class="no_padding_input hide_help_block">
                                        <?php
                                        echo $form->field($model, 'final_amount[' . $index . ']')->textInput(['class' => 'net-amount form-control', "disabled" => TRUE, 'value' => $m['final_amount']])->label(FALSE);
                                        ?>
                                    </td>
                                    <td class="no_padding_input hide_help_block">
                                        <?php
                                        echo $form->field($model, 'adjust_remark[' . $index . ']')->textInput(['value' => $m['adjust_remark']])->label(FALSE);
                                        ?>
                                    </td>
                                    <td class="action-cell skip-export kv-align-center kv-align-middle">
                                        <?php
                                        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-payment_cycle_code' => $m['payment_cycle_code'], 'data-bmc_code' => $m['bmc_code'], 'data-dcs_code' => $m['dcs_code'], 'data-member_code' => $m['member_code']];
                                        echo GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-member-payment/member-bill-head', 'payment_cycle_code' => $m['payment_cycle_code'], 'bmc_code' => $m['bmc_code'], 'dcs_code' => $m['dcs_code'], 'member_code' => $m['member_code']], $options);
                                        ?>

                                        <?php
                                        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'memberinstallments', 'data-original-title' => 'Member Installment', 'data-payment_cycle_code' => $m['payment_cycle_code'], 'data-bmc_code' => $m['bmc_code'], 'data-dcs_code' => $m['dcs_code'], 'data-member_code' => $m['member_code']];
                                        echo GhostHtml::a_alert('<i class="fa fa-plus"></i>', ['/payment/tbl-member-payment/member-installment', 'payment_cycle_code' => $m['payment_cycle_code'], 'bmc_code' => $m['bmc_code'], 'dcs_code' => $m['dcs_code'], 'member_code' => $m['member_code']], $options);
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <?php if (!empty($dataProvider)) { ?>
                            <tbody class="kv-page-summary-container">
                                <tr class="kv-page-summary warning">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <?php
                                    if($is_bank_integrated){ ?>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    <?php
                                    } ?>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><?= $totalQty ?></td>
                                    <td><?= $totalMilkAmt ?></td>
                                    <td><?= $totalAddition ?></td>
                                    <td><?= $totalDeduction ?></td>
                                    <td><?= $totalPrevHold ?></td>
                                    <td><?= $totalPrevDue ?></td>
                                    <td><?= $totalFinalPay ?></td>
                                    <?php if ($milk_short_recovery_member == '1') { ?>
                                        <td>&nbsp;</td>
                                    <?php } ?>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <div class="panel-footer" >
                <?php //Yii::$app->controls->save('Confirm', $model);                   ?>
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Save'),
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to($urlForPost),
                        'beforeSend' => new JsExpression('function(data){
                                            $(".process_lock_flag_member").val("Process");
                                            var negativeVal = "No";
                                            var permanentAndPossitiveValue = "No";
                                            $(".final-amount").each(function() {
                                                var parent = $(this).parents("tr");
                                                var final = parseFloat(parent.find(".final-amount").text());
                                                var netPay = parseFloat(parent.find(".net-amount").val());
                                                var holdType = parent.find(".hold_type").val();
                                                if(final == "" ||  isNaN(final)){
                                                    final=0;
                                                }
//                                                if(final < 0){
                                                    if((!isNaN(netPay) && netPay < 0)) {
                                                        negativeVal = "Yes";
                                                    }
//                                                }
                                                if(holdType == "permanent" && netPay > 0){
                                                    permanentAndPossitiveValue = "Yes";
                                                }
                                            });
                                            var message = "' . $message . '";
                                            var negativeCount = ' . $negativeValCount . ';
                                            var dispMessage = "";
                                            if(negativeCount > 0 || negativeVal == "Yes") {
                                                dispMessage = dispMessage+"' . Yii::t('app', 'Net Payable must be Positive for each Member.') . '";
                                            }
                                            if(permanentAndPossitiveValue == "Yes") {
                                                dispMessage = dispMessage+"<br>Net Payable value must be zero for all users when Hold Type is Permanent";
                                            }
                                            if(milk_short_recovery_member == 1){
                                                var pending_shortage = parseFloat(0.00);
                                                $(".shortage-amount").each(function() {
                                                        var shortage =  parseFloat($(this).val());
                                                        if(shortage != "" &&  !isNaN(shortage)){
                                                            pending_shortage = pending_shortage - shortage;  
                                                        }
                                                }).get();
                                                pending_shortage = parseFloat(parseFloat($("#pending_shortage_amount").val()) + parseFloat(pending_shortage)).toFixed(2);
                                                if(pending_shortage < 0){
                                                    dispMessage = dispMessage+"<br>Shortage amount should not be greater than total shortage amount.";
                                                }
                                            }
                                            if(dispMessage != ""){
                                                bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispMessage+"</span>");
                                                return false;
                                            } else {
                                               var totalRec=0;
                                               var totaladjRec=0;
                                               $(".adjust-recovery").each(function() {
                                                   var parent = $(this).parents("tr");
                                                   var adjustRec = parseFloat(parent.find(".adjust-recovery").val());
                                                   var recovery = parseFloat(parent.find(".recovery").val());
                                                       if(adjustRec == "" ||  isNaN(adjustRec)){
                                                           adjustRec=0;
                                                       }
                                                       if(recovery == "" ||  isNaN(recovery)){
                                                           recovery=0;
                                                       }
                                                   totalRec=totalRec+recovery;
                                                   totaladjRec=totaladjRec+adjustRec;
                                               });
                                              //  if(totalRec != totaladjRec) {
                                                
                                                //    $("#loadercontent").hide();
                                                  //  $("#pageloader").hide(); 
                                                  //  var dispmessage = "' . Yii::t('app', 'Sum of Adjust Recovery and Sum of Reovery Must be Same.') . '";
                                                   // bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispmessage+"</span>");
                                                   // return false;
                                               // } else {
                                                    $("#loadercontent").show();
                                                    $("#pageloader").show(); 
                                               // }
//                                                return false;
                                            }
                                            $("#loadercontent").show();
                                            $("#pageloader").show();
                                        }'),
                        'success' => new JsExpression('function(data){
                                                                var obj=$.parseJSON(data);
                                                                if (obj.status == "success"){ 
                                                                    location.reload();
                                                                }else{
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+obj.msg+"</span>");
                                                                }
                                                 }'),
                    ],
                    'options' => ['class' => 'btn btn-default btn-raised',
                        'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
                <?php // Html::button(Yii::t('app', 'Save as Draft'), ['class' => 'btn btn-primary ', 'id' => 'memberPaymentSave']); ?>
                <?php // Html::button(Yii::t('app', 'Finalize'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock']); ?>
                <?php // Yii::$app->controls->custombutton('Cancel', 'create-payment'); ?> 
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<!--<div id='member_installment'></div>
<div id="recoverOtherMember"></div>-->



<?php
$script = "
var milk_short_recovery_member = $milk_short_recovery_member;
function SumAmount()
{
    var total = parseFloat(0.00);
    $('.net-amount').each(function() {
        var netAmount =  parseFloat($(this).val());
        if(netAmount != '' &&  !isNaN(netAmount)){
            total = total + netAmount;  
        }
    });
    if(milk_short_recovery_member == 1){
        shortageRecovery();
    }
    $('#total-payment').html('Total Payable :: '+total.toFixed(2));
}


function shortageRecovery(){
    var pending_shortage = parseFloat(0.00);
    $('.shortage-amount').each(function() {
            var shortage =  parseFloat($(this).val());
            if(shortage != '' &&  !isNaN(shortage)){
                pending_shortage = pending_shortage - shortage;  
            }
    }).get();
    pending_shortage = parseFloat(parseFloat($('#pending_shortage_amount').val()) + parseFloat(pending_shortage)).toFixed(2);
    if(pending_shortage < 0){
        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Shortage amount should not be greater than total shortage amount.</span>');
        return false;
    }
    $('#total-shortage-amount').html('Pending Shortage Amount :: '+Math.abs(pending_shortage).toFixed(2));
}
function SumAmountold()
{
    var total = parseFloat(0.00);
    $('.adjust-amount').each(function() {
        var adjust =  parseFloat($(this).val());
        if(adjust != '' &&  !isNaN(adjust)){
            total = total + adjust;  
        }
    }).get();
    total=$tot_amt+total;
    $('#total-payment').html('Total Payable :: '+total.toFixed(2));
}

// $('.kv-panel-before').hide();
 

$('#loadercontent').hide();
$('#pageloader').hide();  
";
$this->registerJs($script, View::POS_END, 'payment-adjust-script-list');
?>