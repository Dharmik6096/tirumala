<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'Member Payment Process : Step 3');
$fromDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($aliasModel->paymentCycleCode, 'from_date'));
$toDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($aliasModel->paymentCycleCode, 'to_date'));

$bmc_info = Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_code') . ' > ' . Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_name') . ' > ' .
        $fromDate . ' to ' . $toDate;
$message = Yii::t('app', 'Payment data of  all society will be locked and considered as final for ' . Yii::$app->general->getforeignkey($aliasModel->bmcCode, 'bmc_name') . ' (' . $fromDate . ' to ' . $toDate . '). Are you sure ?');
$config = (isset(Yii::$app->session->get('unionConfig')[$aliasModel->union_code]['recovery_from_other_member']) && Yii::$app->session->get('unionConfig')[$aliasModel->union_code]['recovery_from_other_member'] == 1) ? TRUE : FALSE;
?>
<?php
$array = $dataProvider->getModels();
$tot_amt = array_sum(array_map(function($array) {
            return $array['final_amount'];
        }, $array));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">      
        <div class="panel-heading">
            <?= $this->title . ' (' . $bmc_info . ')' ?>   
            <div id="total-payment">
                Total Payable :: <?= $tot_amt; ?>
            </div>
        </div>     
        <?php
        $form = ActiveForm::begin([
                    'id' => 'payment-adjust',
                    'validateOnBlur' => TRUE,
                    'validateOnChange' => TRUE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php
        $attribute = [
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code')],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                }],
            ['attribute' => 'dcs_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }],
            ['attribute' => 'member_code', 'value' => function($model) {
                    return substr($model->member_code, -4);
                }, 'label' => Yii::t('app', 'Member Code')],
            ['attribute' => 'member_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
                }],
            ['attribute' => 'kg_fat'],
            ['attribute' => 'kg_snf'],
            ['attribute' => 'qty', 'pageSummary' => true],
            ['attribute' => 'total_amount', 'value' => 'total_amount', 'pageSummary' => true],
            ['attribute' => 'total_addition', 'value' => 'total_addition', 'pageSummary' => true],
            ['attribute' => 'total_deduction', 'value' => 'total_deduction', 'pageSummary' => true],
            ['attribute' => 'previous_hold', 'pageSummary' => true],
            ['attribute' => 'previous_due', 'pageSummary' => true],
            ['attribute' => 'net_payable', 'pageSummary' => true, 'contentOptions' => ['class' => 'final-amount'],],
            ['attribute' => 'hold_amount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::activeHiddenInput($model, 'member_payment_alias_code[' . $index . ']', ['class' => 'alis_code', 'value' => $model->member_payment_alias_code]) . $form->field($model, 'hold_amount[' . $index . ']')->textInput(['value' => $model->hold_amount, 'class' => 'number-validate hold-amount cal-amount form-control',])->label(FALSE);
                },
            ],
            ['attribute' => 'additional_pay',
                'format' => 'raw',
                //  'pageSummary' => true,
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::hiddenInput('process_lock_flag', 'Process', ['class' => 'process_lock_flag']) . $form->field($model, 'additional_pay[' . $index . ']')->textInput(['value' => $model->additional_pay, 'class' => 'adjust-amount form-control cal-amount number-validate',])->label(FALSE);
                },
            ],
            ['attribute' => 'adjust_recovery',
                'format' => 'raw',
                'visible' => $config,
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                //  'pageSummary' => true,
                'value' => function ($model, $key, $index) use ($form) {
                    return Html::activeHiddenInput($model, 'payment_cycle_code[' . $index . ']', ['class' => 'payment_cycle', 'value' => $model->payment_cycle_code]) . Html::activeHiddenInput($model, 'plant_code[' . $index . ']', ['class' => 'plant', 'value' => $model->plant_code]) . Html::activeHiddenInput($model, 'mcc_plant_code[' . $index . ']', ['class' => 'mcc', 'value' => $model->mcc_plant_code]) . Html::activeHiddenInput($model, 'bmc_code[' . $index . ']', ['class' => 'bmc', 'value' => $model->bmc_code]) . Html::activeHiddenInput($model, 'dcs_code[' . $index . ']', ['class' => 'dcs', 'value' => $model->dcs_code]) . Html::activeHiddenInput($model, 'member_code[' . $index . ']', ['class' => 'member', 'value' => $model->member_code]) . $form->field($model, 'adjust_recovery[' . $index . ']')->textInput(['class' => 'adjust-recovery form-control number-validate', 'value' => $model->adjust_recovery])->label(FALSE);
                },
            ],
            ['attribute' => 'recovery',
                'format' => 'raw',
                'visible' => $config,
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                //  'pageSummary' => true,
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, 'recovery[' . $index . ']')->textInput(['class' => 'recovery form-control', "readOnly" => TRUE, 'value' => $model->recovery])->label(FALSE);
                },
            ],
            ['attribute' => 'final_amount',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                //  'pageSummary' => true,
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, 'final_amount[' . $index . ']')->textInput(['class' => 'net-amount form-control', "disabled" => TRUE, 'value' => $model->final_amount])->label(FALSE);
                },
            ],
            ['attribute' => 'adjust_remark',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, 'adjust_remark[' . $index . ']')->textInput(['value' => $model->adjust_remark])->label(FALSE);
                },
            ],
        ];

        $grid_option = [
            'id' => 'member-payment-adjust-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => true,
            'actions' => [
                'member-bill-head' => function ($url, $model) {
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code, 'data-member_code' => $model->member_code];
                    return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-member-payment/member-bill-head', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code, 'member_code' => $model->member_code], $options);
                },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], false);
        ?>
    </div>
    <div class="panel-footer" >
        <?php //Yii::$app->controls->save('Confirm', $model);           ?>
        <?= Html::button(Yii::t('app', 'Save as Draft'), ['class' => 'btn btn-primary ', 'id' => 'adjust']); ?>
        <?= Html::button(Yii::t('app', 'Finalize'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock']); ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'create-payment'); ?> 
    </div>
</div>
<div id='bill_head_view'></div>
<?php ActiveForm::end(); ?>
<div id="recoverOtherMember"></div>

<?php
$script = '$("#adjust").click(function() {
    $(".process_lock_flag").val("Process");
     var negativeVal = "No";
    $(".final-amount").each(function() {
        var parent = $(this).parents("tr");
        var final = parseFloat(parent.find(".final-amount").text());
        var netPay = parseFloat(parent.find(".net-amount").val());
        if(final == "" ||  isNaN(final)){
            final=0;
        }
        if(final < 0){
            if((!isNaN(netPay) && netPay < 0)) {
                negativeVal = "Yes";
            }
        }
    });
    var message = "' . $message . '";
    var negativeCount = ' . $negativeValCount . ';
    if(negativeCount > 0 || negativeVal == "Yes") {
        var dispMessage = "' . Yii::t('app', 'Net Payable must be Positive for each Member.') . '";
        bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispMessage+"</span>");
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
            if(totalRec != totaladjRec) {
                var dispmessage = "' . Yii::t('app', 'Sum of Adjust Recovery and Sum of Reovery Must be Same.') . '";
                bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispmessage+"</span>");
            } else {
                 $("#payment-adjust").submit();
            }
    }
});
$("#adjust-lock").click(function() {
    $(".process_lock_flag").val("Lock");
    
    var negativeVal = "No";
    $(".final-amount").each(function() {
        var parent = $(this).parents("tr");
        var final = parseFloat(parent.find(".final-amount").text());
        var netPay = parseFloat(parent.find(".net-amount").val());
        if(final == "" ||  isNaN(final)){
            final=0;
        }
        if(final < 0){
            if(netPay == "" || (!isNaN(netPay) && netPay < 0)) {
                negativeVal = "Yes";
            }
        }
    });
    var message = "' . $message . '";
    var negativeCount = ' . $negativeValCount . ';
    if(negativeCount > 0 || negativeVal == "Yes") {
        var dispMessage = "' . Yii::t('app', 'Net Payable must be Positive for each Member.') . '";
        bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispMessage+"</span>");
    } else {
        bootbox.confirm({
            message: "<div class=\"bg-danger\"><i class=\"fa fa-question-circle\"></i></div><span>"+message+"</span>",
            buttons: {
                confirm: {
                    label: "' . Yii::t('app', 'Yes') . '",
                    className: "btn-primary"
                },
                cancel: {
                    label: "' . Yii::t('app', 'No') . '",
                    className: "btn-danger"
                }
            },
            callback: function (result) {
                if(result){
                    $("#payment-adjust").submit();
                }
            }
        });
    
    }
});
';
$script .= " 


$(document).on('click','.view-head',function(e){
    var payment_cycle_code= $(this).attr('data-payment_cycle_code');
    var bmc_code= $(this).attr('data-bmc_code');
    var dcs_code= $(this).attr('data-dcs_code');
    var member_code= $(this).attr('data-member_code');
    ViewBillHead(payment_cycle_code, bmc_code, dcs_code, member_code);
});

function ViewBillHead(payment_cycle_code, bmc_code, dcs_code, member_code){
    if(payment_cycle_code != '' && bmc_code != '' && dcs_code != ''){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-member-payment/member-bill-head']) . "',
            data: {'payment_cycle_code' : payment_cycle_code,'bmc_code' : bmc_code,'dcs_code' : dcs_code, 'member_code': member_code},
            beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
            },
            success: function(data) {
                $('#bill_head_view').html(data);
                $('#BillHeadModal').modal('toggle');              
                $('#loadercontent').hide();
                $('#pageloader').hide();                                                                  
            },
            error: function(data) {  
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    }
}

    $(document).ready(function () {
        $('.hold-amount').each(function(){
         var id = $(this).attr('id');
         var parent = $(this).parents('tr');
         var val = id.split('-');
         var row_number = val[2];
             calculte(row_number,parent);
        }); 
    });
    function calculte(row_number,parent){
        var adjust = parseFloat($('#tblmemberpaymentalias-adjust_amount-'+row_number).val());
         var final = parseFloat(parent.find('.final-amount').text());
        var hold = parseFloat($('#tblmemberpaymentalias-hold_amount-'+row_number).val());
        var adjustRec = parseFloat($('#tblmemberpaymentalias-adjust_recovery-'+row_number).val());
        var rec = parseFloat($('#tblmemberpaymentalias-recovery-'+row_number).val());
         $('#tblmemberpaymentalias-final_amount-'+row_number).val('')
        if(adjust == '' ||  isNaN(adjust)){
            adjust=0;
        }
        if(hold == '' ||  isNaN(hold)){
            hold=0;
        }
        if(adjustRec == '' ||  isNaN(adjustRec)){
            adjustRec=0;
        }
        if(rec == '' ||  isNaN(rec)){
            rec=0;
        }
        var net = final + adjust - hold + adjustRec - rec; 
        if(net != '' &&  !isNaN(net)){
            $('#tblmemberpaymentalias-final_amount-'+row_number).val(net.toFixed(2));
            SumAmount();
        }
       
    }


    $('.cal-amount').on('blur',function(){
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var adjust = parseFloat(parent.find('.adjust-amount').val());
        var final = parseFloat(parent.find('.final-amount').text());
        var hold = parseFloat(parent.find('.hold-amount').val());
         var val = id.split('-');
         var row_number = val[2];
        var adjustRec = parseFloat(parent.find('.adjust-recovery').val());
        var rec = parseFloat(parent.find('.recovery').val());
        parent.find('.net-amount').val('');
        if(adjust == '' ||  isNaN(adjust)){
            adjust=0;
        }
        if(hold == '' ||  isNaN(hold)){
            hold=0;
        }
        if(adjustRec == '' ||  isNaN(adjustRec)){
            adjustRec=0;
        }
        if(rec == '' ||  isNaN(rec)){
            rec=0;
        }
        var net = final + adjust - hold + adjustRec - rec; 
        if((adjust !=0  || hold !=0) && net != '' && net < 0){
         bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Net Payable should not be less than final amount.</span>',function(){
                bootbox.hideAll();
                    $('#'+id).focus().select();
            });
            return false;
        } else {              
        if(net != '' &&  !isNaN(net)){
         parent.find('.net-amount').val(net.toFixed(2));
          SumAmount();
        }
       }
    });



function SumAmount()
 {
 var total = parseFloat(0.00);
      $('.adjust-amount').each(function() {
      var adjust =  parseFloat($(this).val());
  if(adjust != '' &&  !isNaN(adjust)){
          total = total + adjust;  
          }
        }).get();
   $('.hold-amount').each(function() {
      var hold =  parseFloat($(this).val());
  if(hold != '' &&  !isNaN(hold)){
          total = total - hold;  
          }
        }).get();
        total=$tot_amt+total;
 $('#total-payment').html('Total Payable :: '+total.toFixed(2));
 }     


$('.adjust-amount').on('blur',function(){     
        var adjust = parseFloat($(this).val());
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var final = parseFloat(parent.find('.final-amount').text());
         parent.find('.net-amount').val('');
        var net = final + adjust ;  
        if(net != '' && net < 0){
         bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Adjust Amount should not be less than final amount.</span>',function(){
                bootbox.hideAll();
                    $('#'+id).focus().select();
            });
            return false;
        } else {              
        if(net != '' &&  !isNaN(net)){
         parent.find('.net-amount').val(net.toFixed(2));
          SumAmount();
        }
       }
    });
    
    $('.adjust-recovery').on('blur',function(){     
        var adjustRecovery = parseFloat($(this).val());
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        var net = parseFloat(parent.find('.net-amount').val());
        var dcs = parent.find('.dcs').val();
        var plant = parent.find('.plant').val();
        var mcc = parent.find('.mcc').val();
        var bmc = parent.find('.bmc').val();
        var payment_cycle_code = parent.find('.payment_cycle').val();
        var member = parent.find('.member').val();
        var alis_code = parent.find('.alis_code').val();
        if(adjustRecovery !='' && !isNaN(adjustRecovery)){
             $.ajax({
                type: 'get',
                url:'" . Url::to(['/payment/tbl-member-payment/validate-total-recovery']) . "',
                    data:{'member_payment_alias_code':alis_code,'plant_code':plant,'mcc_plant_code':mcc,'bmc_code':bmc,'payment_cycle_code':payment_cycle_code,'dcs_code':dcs,'member_code':member,'adjust_recovery':adjustRecovery},
                     success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                        $.ajax({
                        type: 'get',
                        url: '" . Url::to(['/payment/tbl-member-payment/recovery-adjust']) . "',
                        data:{'member_payment_alias_code':alis_code,'plant_code':plant,'mcc_plant_code':mcc,'bmc_code':bmc,'payment_cycle_code':payment_cycle_code,'dcs_code':dcs,'member_code':member,'adjust_recovery':adjustRecovery},
                            success: function(data) {     
                                $('#recoverOtherMember').html(data);
                                $('#recoverOtherMemberModal').modal('toggle');    
                                $('#loadercontent').hide();
                                $('#pageloader').hide();
                            },    
                            error: function(data) {    
                                $('#loadercontent').hide();
                                $('#pageloader').hide();
                            }
                        });
                      }else{
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Adjust Recovery Must be '+obj.recovery+' </span></div></div>');
                            parent.find('.adjust-recovery').val(obj.old_recovery);
                        }
                },
                error:function(data){

                }
            });
          
        } else{
//            parent.find('.adjust-recovery').val('');
        }
    });

";
$script .= " function SumAmountold()
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
 
    $(document).on('change','.new_recovery input', function() { 
        var trClass = $(this).closest('tr').attr('class');
        claculateNetPay(trClass);
    });
    
    function claculateNetPay(trClass){
        var recovery = parseFloat($('#tblmemberpaymentalias-'+trClass+'-recovery').val());
        var net = parseFloat($('#tblmemberpaymentalias-'+trClass+'-final_amount').val());
        var oldRec = parseFloat($('#tblmemberpaymentalias-'+trClass+'-old_recovery').val());
        
        if(recovery == '' || isNaN(recovery)){
            recovery = 0;
        }
        if(oldRec == '' || isNaN(oldRec)){
            oldRec = 0;
        }
        var netPay = oldRec + recovery;
        if(netPay > net){
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Recovery Not More Than Net Pay.</span></div></div>');
                $('#tblmemberpaymentalias-'+trClass+'-recovery').val('');
//                setTimeout(function(){
//                    $('#tblmemberpaymentalias-'+trClass+'-recovery').focus();
//                },100);
        }else{
//            var newNetPay= net-netPay;
//            setTimeout(function(){
//                $('#tblmemberpaymentalias-'+trClass+'-final_amount.final_amount').val(newNetPay.toFixed(2)); 
//            }, 500);
        }
    } 
 ";

$this->registerJs($script, View::POS_END, 'payment-adjust-script');
?>
<?php
$script = "$('.kv-panel-before').hide();";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>