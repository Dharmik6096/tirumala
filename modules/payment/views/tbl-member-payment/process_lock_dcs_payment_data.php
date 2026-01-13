<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'Member Payment Process : Step 2');
$fromDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'from_date'));
$toDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'to_date'));
$code = $name = '';
$data = Yii::$app->general->getPaymentHeader($model);
if (!empty($data)) {
    $code = $data['code'];
    $name = $data['name'];
}

$bmc_info = $code . ' > ' . $name . ' > ' .
        $fromDate . ' to ' . $toDate;
$message = Yii::t('app', 'Payment data of  all society will be locked and considered as final for ' . $name . ' (' . $fromDate . ' to ' . $toDate . '). Are you sure ?');
?>
<?php
$array = $dataProvider->getModels();
$tot_amt = array_sum(array_map(function ($array) {
            return $array['final_amount'];
        }, $array));
$milk_short_recovery_member = isset(Yii::$app->session->get('unionConfig')[$model->union_code]['milk_short_recovery_member']) ? Yii::$app->session->get('unionConfig')[$model->union_code]['milk_short_recovery_member'] : 0;
$allow_stop_payment_member = isset(Yii::$app->session->get('unionConfig')[$model->union_code]['allow_stop_payment_member']) ? Yii::$app->session->get('unionConfig')[$model->union_code]['allow_stop_payment_member'] : 0;
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title . ' (' . $bmc_info . ')' ?>   
            <div id="total-payment-dcs">
                Total Payable :: <?= $tot_amt; ?>
            </div>     
        </div>


        <div class="panel-body">    
            <div class="grid-searchasd large-search hidden-print recovery_padding">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'member-wise-payment-summary-form',
                            'validateOnBlur' => false,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                            'action' => Url::to(['list-member-payment-summary-data'])
//                            'action' => Url::to(['list-member-payment'])
                ]);
                echo $form->errorSummary($model);
                ?>
                <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
                <?= Html::activeHiddenInput($model, 'union_code'); ?>
                <?= Html::activeHiddenInput($model, 'plant_code'); ?>
                <!-- Html::activeHiddenInput($model, 'mcc_plant_code'); -->
                <?php if (is_array($model->mcc_plant_code)) { ?>
                    <?php foreach ($model->mcc_plant_code as $mcc_plant_code) { ?>
                        <?= Html::activeHiddenInput($model, 'mcc_plant_code[]', ['value' => $mcc_plant_code]); ?>
                    <?php } ?>
                <?php } else { ?>
                    <?= Html::activeHiddenInput($model, 'mcc_plant_code'); ?>
                <?php } ?>
                <?php if (is_array($model->bmc_code)) { ?>
                    <?php foreach ($model->bmc_code as $bmc_code) { ?>
                        <?= Html::activeHiddenInput($model, 'bmc_code[]', ['value' => $bmc_code]); ?>
                    <?php } ?>
                <?php } else { ?>
                    <?= Html::activeHiddenInput($model, 'bmc_code'); ?>
                <?php } ?>

                <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
                <?= Html::activeHiddenInput($model, 'dcs_code'); ?>
                <?= Html::hiddenInput('process_lock_flag', 'Process', ['class' => 'process_lock_flag']); ?>
                <?php
                $attribute = [
                        ['class' => 'kartik\grid\CheckboxColumn',
                        'rowSelectedClass' => GridView::TYPE_SUCCESS,
                        'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                        'checkboxOptions' => function ($model) {
                            return ['value' => $model['dcs_code']];
                        }, 'visible' => $allow_stop_payment_member == '1'],
                        ['attribute' => 'stop_reason',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Yii::$app->dropdown->dropdownfilterStatic('stop_payment_type', $model, '[' . $model->dcs_code . ']stop_payment_type', '');
                        }, 'visible' => $allow_stop_payment_member == '1'
                    ],
                        ['attribute' => 'ref_code', 'label' => Yii::t('app', 'DCS Code'), 'value' => function ($model) {
                            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
                        }],
                        ['attribute' => 'dcs_code_ex', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function ($model) {
                            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                        }],
                        ['attribute' => 'dcs_code', 'value' => function ($model) {
                            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                        }],
                        ['attribute' => 'member_count'],
                        ['attribute' => 'kg_fat'],
                        ['attribute' => 'kg_snf'],
                        ['attribute' => 'qty', 'pageSummary' => true],
                        ['attribute' => 'shortage_amount',
                        'label' => Yii::t('app', 'Shortage Amount'),
                        'value' => function ($model, $key, $index) {
                            $other_member_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveryOtherMember, 'recovery_amount'));
                            $mpg_member_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveryMpgMember, 'recovery_amount'));
                            $member_recovered_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveredMember, 'amount'));
                            $total_shortage_amount = (!empty($other_member_amount) ? $other_member_amount : 0) + (!empty($mpg_member_amount) ? $mpg_member_amount : 0);
                            $amount = $total_shortage_amount - (!empty($member_recovered_amount) ? $member_recovered_amount : 0);
                            $amount = round($amount, 2);
                            $options = ['class' => 'shortage-recovery-amount'];
                            echo Html::hiddenInput('shortage-recovery-amount', $amount, $options);
                            return $total_shortage_amount;
                        }, 'visible' => $milk_short_recovery_member == '1', 'pageSummary' => true],
                        ['attribute' => 'recovered_amount',
                        'label' => Yii::t('app', 'Recovered Amount'),
                        'value' => function ($model, $key, $index) {
                            $member_recovered_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveredMember, 'amount'));
                            $amount = round((!empty($member_recovered_amount) ? $member_recovered_amount : 0), 2);
                            return $amount;
                        }, 'visible' => $milk_short_recovery_member == '1', 'pageSummary' => true],
                        ['attribute' => 'total_amount', 'value' => 'total_amount', 'pageSummary' => true],
                        ['attribute' => 'total_addition', 'value' => 'total_addition', 'pageSummary' => true],
                        ['attribute' => 'total_deduction', 'value' => 'total_deduction', 'pageSummary' => true],
                        ['attribute' => 'previous_hold', 'pageSummary' => true],
                        ['attribute' => 'previous_due', 'pageSummary' => true],
                        ['attribute' => 'net_payable', 'pageSummary' => true,],
                        ['attribute' => 'hold_amount', 'pageSummary' => true,],
                        ['attribute' => 'additional_pay', 'pageSummary' => true,],
                        ['attribute' => 'final_amount', 'pageSummary' => true,],
                ];

                $grid_option = [
                    'id' => 'member-payment-process-second' . uniqid(),
                    'attributes' => $attribute,
                    'active_column' => false,
                    'showPageSummary' => true,
                    'actions' => [
                        'bill-head' => function ($url, $model) {
                            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-dcs-head', 'data-original-title' => 'View Bill Head', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code];
                            return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-member-payment/bill-head', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code], $options);
                        },
                        'member_data_update' => function ($url, $model) {
                            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head-pencil', 'data-original-title' => 'Member Payment Adjustment', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-union_code' => $model->union_code, 'data-plant_code' => $model->plant_code, 'data-mcc_plant_code' => $model->mcc_plant_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code];
                            return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/payment/tbl-member-payment/member-payment-adjust-list', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code], $options);
                        },
                        'members' => function ($url, $model) {
                            $options = ['data-toggle' => 'tooltip', 'target' => '_blank', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'View Members')];
                            return GhostHtml::a('<i class="fa fa-users"></i>', ['/payment/tbl-member-payment/payment-members-list', 'cycle' => $model->payment_cycle_code, 'dcs_code' => $model->dcs_code], $options);
                        },
                    ]
                ];

                $rowOptions = function ($model) use ($negativeDcsCode, $milk_short_recovery_member) {
                    if ($milk_short_recovery_member == 1) {
                        $other_member_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveryOtherMember, 'recovery_amount'));
                        $mpg_member_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveryMpgMember, 'recovery_amount'));
                        $member_recovered_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveredMember, 'amount'));
                        $total_shortage_amount = (!empty($other_member_amount) ? $other_member_amount : 0) + (!empty($mpg_member_amount) ? $mpg_member_amount : 0);
                    }
                    $rowclass = '';
                    if (in_array($model->dcs_code, $negativeDcsCode) || ($milk_short_recovery_member == 1 && $total_shortage_amount > 0 && trim($total_shortage_amount) != trim($member_recovered_amount))) {
                        $rowclass = 'danger';
                    }

                    return ['class' => $rowclass];
                };
                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create'], false, [], [], true, $rowOptions);
                ?>
                <div class="col-md-12" >
                    <?php if (!empty($dataProvider->getModels())) { ?>
                        <?php foreach ($dataProvider->getModels() as $data) { ?>
                            <?= Html::activeHiddenInput($model, 'dcs_code[]', ['value' => $data['dcs_code']]); ?>
                        <?php } ?>
                        <?php //Yii::$app->controls->save('Confirm', $model);                ?>
                        <?= GhostHtml::a_alert(Yii::t('app', 'Save as Draft'), ['/payment/tbl-member-payment/draft-payment'], ['class' => 'btn btn-primary', 'id' => 'adjustDcsData']); ?>
                        <?= GhostHtml::a_alert(Yii::t('app', 'Finalize'), ['/payment/tbl-member-payment/finalize-payment'], ['class' => 'btn btn-primary', 'id' => 'adjust-lock-dcs-data']); ?>
                        <?php // Html::button(Yii::t('app', 'Save as Draft'), ['class' => 'btn btn-primary ', 'id' => 'adjustDcsData']); ?>
                        <?php // Html::button(Yii::t('app', 'Finalize'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock-dcs-data']); ?>

                    <?php } ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'create-payment'); ?>        
                </div>
                <?php ActiveForm::end(); ?>


            </div>
        </div>
    </div>
</div>
<div id='bill_head_view_dcs'></div>
<div id='adjustment_view'></div>
<div id='bill_head_view_member'></div>
<div id='member_installment'></div>
<div id="recoverOtherMember"></div>
<?php
$script = "
$('.kv-panel-before').hide(); 

$(document).on('click','.view-dcs-head',function(e){
    var payment_cycle_code= $(this).attr('data-payment_cycle_code');
    var bmc_code= $(this).attr('data-bmc_code');
    var dcs_code= $(this).attr('data-dcs_code');
    ViewBillHead(payment_cycle_code, bmc_code, dcs_code);
});

function ViewBillHead(payment_cycle_code, bmc_code, dcs_code){
    if(payment_cycle_code != '' && bmc_code != '' && dcs_code != ''){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-member-payment/bill-head']) . "',
            data: {'payment_cycle_code' : payment_cycle_code,'bmc_code' : bmc_code,'dcs_code' : dcs_code},
            beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
            },
            success: function(data) {
                $('#bill_head_view_dcs').html(data);
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


var payCode = '';
var uniCode = '';
var plantCode = '';
var mccPlantCode = '';
var bmcCode = '';
var dcsCode = '';
function ViewMemberAdjustmentDataAfterPopupSave(){
    ViewMemberAdjustmentData(payCode, uniCode, plantCode, mccPlantCode, bmcCode, dcsCode);
}

$(document).on('click','.view-head-pencil',function(e){
    var payment_cycle_code= $(this).attr('data-payment_cycle_code');
    var union_code= $(this).attr('data-union_code');
    var plant_code= $(this).attr('data-plant_code');
    var mcc_plant_code= $(this).attr('data-mcc_plant_code');
    var bmc_code= $(this).attr('data-bmc_code');
    var dcs_code= $(this).attr('data-dcs_code');
    payCode = payment_cycle_code;
    uniCode = union_code;
    plantCode = plant_code;
    mccPlantCode = mcc_plant_code;
    bmcCode = bmc_code;
    dcsCode = dcs_code;
    ViewMemberAdjustmentData(payment_cycle_code, union_code, plant_code, mcc_plant_code, bmc_code, dcs_code);
});

function ViewMemberAdjustmentData(payment_cycle_code, union_code, plant_code, mcc_plant_code, bmc_code, dcs_code){
    if(payment_cycle_code != '' && bmc_code != '' && dcs_code != ''){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-member-payment/member-payment-adjust-list']) . "',
            data: {'payment_cycle_code' : payment_cycle_code,'union_code': union_code, 'plant_code': plant_code, 'mcc_plant_code': mcc_plant_code, 'bmc_code' : bmc_code,'dcs_code' : dcs_code},
            beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
            },
            success: function(data) {
                $('#adjustment_view').html(data);
                $('#MemberPaymentAdjustmentModel').modal('toggle');              
                $('#loadercontent').hide();
                $('#pageloader').hide();   
                $('.hold-amount').each(function(){
                    var id = $(this).attr('id');
                    var parent = $(this).parents('tr');
                    var val = id.split('-');
                    var row_number = val[2];
                     calculte(row_number,parent);
                }); 
            },
            error: function(data) {  
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    }
}




//$('#adjustDcsData').click(function() {
//    $('.process_lock_flag').val('Process');
//    var checkBoxCount = $('.kv-row-checkbox:checked').length;
//    if(checkBoxCount > 0) {
//        $('#flag').val($(this).prop('name'));
//        $('#member-wise-payment-summary-form').submit();
////        $('form#w1').submit();
//    } else {
//        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Please Select atleast one Record') . "</span>');
//    }
//});
//$('#adjust-lock-dcs-data').click(function() {
//    $('.process_lock_flag').val('Lock');
//    var negativeCount = '';
//    var message = '" . $message . "';
//        
//    if(negativeCount > 0) {
//        var dispMessage = '" . Yii::t('app', 'Net Payable must be Positive for each Member.') . "';
//        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+dispMessage+'</span>');
//    } else {
//        bootbox.confirm({
//            message: '<div class=\'bg-danger\'><i class=\'fa fa-question-circle\'></i></div><span>'+message+'</span>',
//            buttons: {
//                confirm: {
//                    label: '" . Yii::t('app', 'Yes') . " ',
//                    className: 'btn-primary'
//                },
//                cancel: {
//                    label: '" . Yii::t('app', 'No') . "' ,
//                    className: 'btn-danger'
//                }
//            },
//            callback: function (result) {
//                if(result){
//                    $('#member-wise-payment-summary-form').submit();
//                }
//            }
//        });
//    }
////    $('form#w1').submit();
//});







$(document).on('click','.view-head',function(e){
    var payment_cycle_code= $(this).attr('data-payment_cycle_code');
    var bmc_code= $(this).attr('data-bmc_code');
    var dcs_code= $(this).attr('data-dcs_code');
    var member_code= $(this).attr('data-member_code');
    ViewMemberBillHead(payment_cycle_code, bmc_code, dcs_code, member_code);
});

function ViewMemberBillHead(payment_cycle_code, bmc_code, dcs_code, member_code){
    if(payment_cycle_code != '' && bmc_code != '' && dcs_code != ''){         
    $.ajax({
            type: 'post',
            url: '" . Url::to(['/payment/tbl-member-payment/member-bill-head']) . "',
            data: {'payment_cycle_code' : payment_cycle_code,'bmc_code' : bmc_code,'dcs_code' : dcs_code, 'member_code': member_code,'allow_update': '1'},
            beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
            },
            success: function(data) {
                $('#bill_head_view_member').html(data);
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

    function calculte(row_number,parent){
//        var adjust = parseFloat($('#tblmemberpaymentalias-adjust_amount-'+row_number).val());
        var adjust = parseFloat($('#tblmemberpaymentalias-additional_pay-'+row_number).val());
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
        $('#tblmemberpaymentalias-final_amount-'+row_number).val(net.toFixed(2));
        if(net != '' &&  !isNaN(net)){
            $('#tblmemberpaymentalias-final_amount-'+row_number).val(net.toFixed(2));
            SumAmount();
        }
       
    }

    
    $(document).on('keyup','.cal-amount',function(e){
        var id = $(this).attr('id');
        var refreshValue = true;
        if ($(this).hasClass('adjust-amount')) {
            refreshValue = false;
        }
        var parent = $(this).parents('tr');
        var adjust = parseFloat(parent.find('.adjust-amount').val());
        var final = parseFloat(parent.find('.final-amount').text());
        var hold = parseFloat(parent.find('.hold-amount').val());
         var val = id.split('-');
         var row_number = val[2];
        var adjustRec = parseFloat(parent.find('.adjust-recovery').val());
        var rec = parseFloat(parent.find('.recovery').val());
        var shortage_old = parseFloat(parent.find('.shortage-amount-old').val());
        if(shortage_old == '' ||  isNaN(shortage_old)){
            shortage_old=0;
        }
        var shortage = shortage_old - parseFloat(parent.find('.shortage-amount').val());
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
        if(shortage == '' ||  isNaN(shortage)){
            shortage=0;
        }
        var net_amount = parseFloat(final + adjust - hold + adjustRec - rec + shortage);
        net_amount = parseFloat(net_amount.toFixed(2))
        net = net_amount.toFixed(2)
        if((adjust !=0  || hold !=0 || shortage !=0 || shortage_old !=0) && net != '' && net < 0){
            bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Net Payable should not be less than final amount.</span>',function(){
                bootbox.hideAll();
                if(refreshValue){
                    $('#'+id).focus().val(.00);
                }
                $('#'+id).focus().select();
            });
            return false;
        } else {              
            if(net != '' &&  !isNaN(net)){
                parent.find('.net-amount').val(net);
                SumAmount();
            }
        }
    });


    // $(document).on('blur','.adjust-amount',function(e){
    //     //$('.adjust-amount').on('blur',function(){     
    //     var adjust = parseFloat($(this).val());
    //     var id = $(this).attr('id');
    //     var parent = $(this).parents('tr');
    //     var final = parseFloat(parent.find('.final-amount').text());
    //     parent.find('.net-amount').val('');
    //     var net = final + adjust ;  
    //     if(net != '' && net < 0){
    //      bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Adjust Amount should not be less than final amount.</span>',function(){
    //             bootbox.hideAll();
    //                 $('#'+id).focus().select();
    //         });
    //         return false;
    //     } else {              
    //     if(net != '' &&  !isNaN(net)){
    //      parent.find('.net-amount').val(net.toFixed(2));
    //       SumAmount();
    //     }
    //    }
    // });
    
    $(document).on('blur','.adjust-recovery',function(e){
//    $('.adjust-recovery').on('blur',function(){     
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
                        data:{'member_payment_alias_code':alis_code,'plant_code':plant,'mcc_plant_code':mcc,'bmc_code':bmc,'payment_cycle_code':payment_cycle_code,'dcs_code':dcs,'member_code':member,'adjust_recovery':adjustRecovery,'recovery_dcs':dcs},
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
        oldRec = 0;
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

    $(document).on('click','.memberinstallments',function(e){
        var trClass = $(this).closest('tr').attr('class');
        var payment_cycle_code= $(this).attr('data-payment_cycle_code');
        var bmc_code= $(this).attr('data-bmc_code');
        var dcs_code= $(this).attr('data-dcs_code');
        var member_code= $(this).attr('data-member_code');
        

        var parent = $(this).parents('tr');
        var netPay = parseFloat(parent.find('.net-amount').val());
            MemberInstallment(payment_cycle_code, bmc_code, dcs_code, member_code, netPay);
    });

    function MemberInstallment(payment_cycle_code, bmc_code, dcs_code, member_code, netPay){
        if(payment_cycle_code != '' && bmc_code != '' && dcs_code != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/payment/tbl-member-payment/member-installment']) . "',
                data: {'payment_cycle_code' : payment_cycle_code,'bmc_code' : bmc_code,'dcs_code' : dcs_code, 'member_code': member_code, 'netPay': netPay},
                beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                },
                success: function(data) {
                    $('#member_installment').html(data);
                    $('#MemberInstallmentModal').modal('toggle');              
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

";

$negativeContent = 'no';
$errMsg = '';
if (!empty($negativeValCount)) {
    $negativeContent = 'yes';
    $errMsg = $errMsg . Yii::t('app', 'Net Payable is negative For Following DCS.');
    $errMsg = $errMsg . '<ul>';
    foreach ($negativeValCount as $negativeValCountDcs) {
        $errMsg = $errMsg . '<li>' . $negativeValCountDcs['dcs_name'] . '(' . $negativeValCountDcs['ref_code'] . ')' . '</li>';
    }
    $errMsg = $errMsg . '</ul>';
}
$script .= '  
    $(document).on("click", "#adjustDcsData", function(){
//    $("#adjust").click(function() {
    $(".process_lock_flag").val("Process");
    var negativeVal = "No";
    var message = "' . $message . '";
    var negativeCount = "' . $negativeContent . '";
    if(negativeVal == "Yes") {
        var dispMessage = "' . Yii::t('app', 'Net Payable must be Positive for each Member.') . '";
        bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispMessage+"</span>");
    } else if (negativeCount == "yes") {
        var negativeMsg = "' . $errMsg . '";
        var dispMessage = "' . Yii::t('app', 'Net Payable must be Positive for each Member.') . '";
        bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+negativeMsg+"</span>");
    } else {
        var totalRec=0;
        var totaladjRec=0;
       
        if(false && totalRec != totaladjRec) {
            var dispmessage = "' . Yii::t('app', 'Sum of Adjust Recovery and Sum of Reovery Must be Same.') . '";
            bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispmessage+"</span>");
        } else {
             $("#member-wise-payment-summary-form").submit();
        }
    }
});
$(document).on("click", "#adjust-lock-dcs-data", function(){
//$("#adjust-lock").click(function() {
    $(".process_lock_flag").val("Lock");
    
    var negativeVal = "No";
    
    var message = "' . $message . '";
    var negativeCount = "' . $negativeContent . '";
    if(negativeVal == "Yes") {
        var dispMessage = "' . Yii::t('app', 'Net Payable must be Positive for each Member.') . '";
        bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+dispMessage+"</span>");
    } else if (negativeCount == "yes") {
        var negativeMsg = "' . $errMsg . '";
        var dispMessage = "' . Yii::t('app', 'Net Payable must be Positive for each Member.') . '";
        bootbox.alert("<div class=\"bg-danger\"><i class=\"fa fa-times-circle\"></i></div><span>"+negativeMsg+"</span>");
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
                    $("#member-wise-payment-summary-form").submit();
                }
            }
        });
    
    }
});
$(".shortage-recovery-amount").each(function(){
    var shortRecAmount = $(this).val();
    if(shortRecAmount > 0){
        $("#adjust-lock-dcs-data").prop("disabled", true);
        $("#adjust-lock-dcs-data").addClass("disabled");
    }
});
           ';
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>