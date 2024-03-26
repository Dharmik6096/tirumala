<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$action = Url::to(['disburse-member-payment']);
$fromDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'from_date'));
$toDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'to_date'));
//$message = Yii::t('app', 'Payment data of  all society will be Disbursed for ' . Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name') . ' (' . $fromDate . ' to ' . $toDate . '). Are you sure ?');
$code = $name = '';
$data = Yii::$app->general->getPaymentHeader($model);
if (!empty($data)) {
    $code = $data['code'];
    $name = $data['name'];
}
$message = Yii::t('app', 'Payment data of  all society will be Disbursed for ' . $name . ' (' . $fromDate . ' to ' . $toDate . '). Are you sure ?');
$showButtons = (!empty($model->payment_cycle_code) && !empty($dataProvider->getModels())) ? TRUE : FALSE;
?>
<div class="" >
    <?php
    $form = ActiveForm::begin([
                'action' => $action,
                'method' => 'post',
                'id' => 'otp-form',
    ]);
    ?>
    <div class="col-sm-12 mt10 padding-left-0">
        <div class="grid-button-wrap" >
            <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
            <?= Html::activeHiddenInput($model, 'union_code'); ?>
            <?= Html::activeHiddenInput($model, 'plant_code'); ?>
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

            <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
        </div>
        <div class="col-sm-2">
            <?php
            if ($showButtons) {
                $allow_disburse_without_release = isset(Yii::$app->session->get('unionConfig')[$model->union_code]['allow_disburse_without_release']) ? Yii::$app->session->get('unionConfig')[$model->union_code]['allow_disburse_without_release'] : 0;
                if ($allow_disburse_without_release == '1') {
                    echo Yii::$app->dropdown->dropdownStatic('payment_release_type', $model, $form, 'form-group', $model->getAttributeLabel('payment_release_type'), FALSE, 'payment_release_type', FALSE, FALSE, FALSE);
                } else {
                    $model->payment_release_type = '0';
                    echo Html::activeHiddenInput($model, 'payment_release_type');
                }
            }
            ?>
        </div>
        <div class="clearfix"></div>
        <?php
        $attribute = [
//            ['class' => 'kartik\grid\CheckboxColumn',
//            'rowSelectedClass' => GridView::TYPE_SUCCESS,
//            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
//            'checkboxOptions' => function($model) {
//                return ['value' => $model['dcs_code']];
//            }],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
                }],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                }],
            ['attribute' => 'dcs_code', 'value' => function ($model) {
                    return !empty($model->dcs_name) ? $model->dcs_name : Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }],
            ['attribute' => 'member_count'],
            ['attribute' => 'kg_fat'],
            ['attribute' => 'kg_snf'],
            ['attribute' => 'qty', 'pageSummary' => true],
            ['attribute' => 'total_amount', 'pageSummary' => true],
            ['attribute' => 'total_addition', 'pageSummary' => true],
            ['attribute' => 'total_deduction', 'pageSummary' => true],
            ['attribute' => 'previous_hold', 'pageSummary' => true],
            ['attribute' => 'previous_due', 'pageSummary' => true],
            ['attribute' => 'net_payable', 'pageSummary' => true,],
            ['attribute' => 'hold_amount', 'pageSummary' => true,],
            ['attribute' => 'additional_pay', 'pageSummary' => true,],
            ['attribute' => 'final_amount', 'pageSummary' => true,],
        ];

        $grid_option = [
            'id' => 'member-payment-export-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => true,
            'actions' => [
                'bill-head' => function ($url, $model) {
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code];
                    return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-member-payment/bill-head', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code], $options);
                },
                'members' => function ($url, $model) {
                    $options = ['data-toggle' => 'tooltip', 'target' => '_blank', 'data-placement' => 'top', 'data-original-title' => 'View Members'];
                    return GhostHtml::a('<i class="fa fa-users"></i>', ['/payment/tbl-member-payment/payment-members-list', 'cycle' => $model['payment_cycle_code'], 'dcs_code' => $model['dcs_code']], $options);
                },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create'], false);
        ?>
        <div class="clearfix"></div>
        <?php if ($showButtons) { ?>
            <div class="col-md-12 mt10" >
                <?php
                if (!empty($bank_show)) {
                    $array = [];
                    foreach ($bank_show as $data) {
                        $array[$data['union_bank_payment_code']] = $data['bank_name'];
                    }
                    if (count($array) > 1) {
                        ?>
                        <div class="col-sm-2 mr-10">
                            <?php
                            echo $form->field($model, 'union_bank_payment_code')->dropDownList($array, ['prompt' => Yii::t('app', 'Select Bank *')])->label(false);
                            ?>                   
                            <?php
                        } else if (!empty($bank_show) && count($bank_show) == 1) {
                            $model->union_bank_payment_code = $bank_show[0]['union_bank_payment_code'];
                            echo Html::activeHiddenInput($model, 'union_bank_payment_code');
                        }
                    }
                    ?>
                </div>
                <?= Html::button(Yii::t('app', 'Disburse Payment'), ['class' => 'btn btn-primary sub', 'name' => 'member']); ?>
                <?= Html::button(Yii::t('app', 'Export Data'), ['class' => 'btn btn-primary sub', 'name' => 'member-file']); ?>
            </div>
        <?php } ?>
        <?= $this->render('verify-otp', ['model' => $model, 'form' => $form]) ?>

        <div class="clearfix"></div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<div id='bill_head_view'></div>

<?php
$script = "
    

$(document).on('click','.view-head',function(e){
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

    $('.kv-panel-before').hide();
    $('.sub').on('click',function(){
//        var checkBoxCount = $('.kv-row-checkbox:checked').length;
//        if(checkBoxCount > 0) {
//            $('#flag').val($(this).prop('name'));
//            $('form#w1').submit();
//        } else {
//            bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Please Select atleast one Record') . "</span>');
//        }
        var flagName = $(this).prop('name');
        $('#flag').val(flagName);
        if(flagName == 'member') {
            var negativeCount = " . $negativeValCount . ";
            var message = '" . $message . "';
            var bank = document.getElementById('tblmemberpaymentalias-union_bank_payment_code');
            var bankCode= bank != null ? bank.value : '';
            if(bankCode == '' &&  bank != null){
                 var dispMessage = '" . Yii::t('app', 'Please select bank for disbursement.') . "';
                 bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+dispMessage+'</span>');
             }   
            else
            {
            if(negativeCount > 0) {
                var dispMessage = '" . Yii::t('app', 'Net Payable must be Positive for each Member.') . "';
                bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+dispMessage+'</span>');
            } else {
//                bootbox.confirm({
//                    message: '<div class=\'bg-danger\'><i class=\'fa fa-question-circle\'></i></div><span>'+message+'</span>',
//                    buttons: {
//                        confirm: {
//                            label: '" . Yii::t('app', 'Yes') . " ',
//                            className: 'btn-primary'
//                        },
//                        cancel: {
//                            label: '" . Yii::t('app', 'No') . "' ,
//                            className: 'btn-danger'
//                        }
//                    },
//                    callback: function (result) {
//                        if(result){
//                            $('form#w1').submit();
//                        }
//                    }
//                });
//            }
//         }
//
//        } else {
//            $('form#w1').submit();
//        }
//    });
        var ucode = '" . $model->union_code . "';
                var payCycleCode = ' ". $model->payment_cycle_code . "';
                var bmcCode = ".json_encode($model->bmc_code).";
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['validate-bank-details']) . "',
                    data: {'union_code':ucode,'payment_cycle_code':payCycleCode,'bmc_code':bmcCode},
//                    data: 'union_code=" . $model->union_code . "',
                    success: function (data) {
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success') {
                            // $('#OtpModal').modal('toggle'); 
                           sendotp();
//                          $('form#w1').submit();
//allow_without_otp
                        } else if (obj.status == 'allow_without_otp') { 
                            $('#otp-form').submit();
                            $('#loadercontent').show();
                            $('#pageloader').show();
                        } else if (obj.status == 'validate_member_bank_detail_confirmation') { 
                            bootbox.confirm({
                                message: '<div class=\'bg-danger\'><i class=\'fa fa-question-circle\'></i></div><span>'+obj.message+'</span>',
                                buttons: {
                                    confirm: {
                                        label: '" . Yii::t('app', 'Yes') . " ',
                                        className: 'btn-primary'
                                    },
                                    cancel: {
                                        label: '" . Yii::t('app', 'No') . "' ,
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function (result) {
                                    if(result){
//                                        $('form#w1').submit();
                                        sendotp();
                                    }
                                }
                            });
                        } else if (obj.status == 'validate_member_bank_detail_verification_confirmation') { 
                            bootbox.confirm({
                                message: '<div class=\'bg-danger\'><i class=\'fa fa-question-circle\'></i></div><span>'+obj.message+'</span>',
                                buttons: {
                                    confirm: {
                                        label: '" . Yii::t('app', 'Yes') . " ',
                                        className: 'btn-primary'
                                    },
                                    cancel: {
                                        label: '" . Yii::t('app', 'No') . "' ,
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function (result) {
                                    if(result){
//                                        $('form#w1').submit();
                                        sendotp();
                                    }
                                }
                            });
                           // bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
                        } else {
                           bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
                        }
                    }
                });
//                bootbox.confirm({
//                    message: '<div class=\'bg-danger\'><i class=\'fa fa-question-circle\'></i></div><span>'+message+'</span>',
//                    buttons: {
//                        confirm: {
//                            label: '" . Yii::t('app', 'Yes') . " ',
//                            className: 'btn-primary'
//                        },
//                        cancel: {
//                            label: '" . Yii::t('app', 'No') . "' ,
//                            className: 'btn-danger'
//                        }
//                    },
//                    callback: function (result) {
//                        if(result){
//                            $('form#w1').submit();
//                        }
//                    }
 //               });
                } 
            }
		  

        } else {
            $('form#w1').submit();
        }
    });
    

//
            function sendotp(){
                var ucode = '" . $model->union_code . "';
                var from_date = '" . $fromDate . "';
                var to_date = '" . $toDate . "';
                var amount = '" . $finalP . "';
                var bmc_name = '" . Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name') . "';
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['send-otp']) . "',
                    data: {'union_code':ucode,'from_date':from_date,'to_date':to_date,'bmc_name':bmc_name,'amount':amount},
//                    data: 'union_code=" . $model->union_code . "',
                    success: function (data) {
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                            var obj = $.parseJSON(data);
                            if (obj.status == 'success') {
                                $('#OtpModal').modal('toggle'); 
                            } else {
                                bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
                            }
                    }
                });  
            }


            $('.verify').on('click', function () {
                var otp = $('#tblmemberpaymentalias-otp_code').val();
                if (otp != '') {
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['verify-otp']) . "',
                        data: 'otp_code='+otp,
                        success: function (data) {
                            var obj = $.parseJSON(data);
                            if (obj.status == 'success') {
                                $('#otp-form').submit();
                                $('#loadercontent').show();
                                $('#pageloader').show();
                            } else {
                                $('#error-summary ul').html('');
                                $('#error-summary ul').append('<li>' + obj.message + '</li>');
                                $('#error-summary').show();
                            }
                        }
                    });
                } else {
                    $('#error-summary ul').html('');
                    $('#error-summary ul').append('<li>OTP Can not be blank.</li>');
                    $('#error-summary').show();

                }
            });
    

    
    $('.bank').on('click',function(){
        $('#error-summary').hide();
        $('#flag').val($(this).prop('name'));
        $('form#w1').submit();
//         $.ajax({
//            type: 'post',
//            url: '" . Url::to(['check-bank']) . "',
//            data: 'union_code=" . $model->union_code . "',
//            success: function (data) {
//                var obj = $.parseJSON(data);
//                if (obj.status == 'success')
//                {
//                  $('form#w1').submit();
//                }else{
//                   bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
//                }
//            }
//        });

    });
    ";
$this->registerJs($script, View::POS_END, 'data-export-script');

