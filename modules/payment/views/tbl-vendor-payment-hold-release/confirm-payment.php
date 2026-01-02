<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$action = Url::to(['bank-payment']);
$bmc_info = '';
$code = $name = '';
if (!empty($searchModel)) {
    $data = Yii::$app->general->getPaymentHeader($searchModel);
    if (!empty($data)) {
        $code = $data['code'];
        $name = $data['name'];
    }
    $bmc_info = $code . ' > ' . $name . ' > ';
    $bmc_info .= Yii::$app->general->getforeignkey($searchModel->customerType, 'customer_desc') . ' > ';
    $bmc_info .= Yii::$app->controls->view_date($searchModel->from_datetime) . ' to ' . Yii::$app->controls->view_date($searchModel->to_datetime);
}
$title = Yii::t('app', 'Vendor Hold Relese Payment Disburse : Step 2') . ' ' . ' (' . $bmc_info . ')';
?>
<div class="tbl-member-payment-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $title ?> 
            <div id="total-payment">
                Total Payable :: <?= $pay_amount ?>
            </div>
        </div>
        <?php
        $form = ActiveForm::begin(['options' => [
            'class' => 'popup-form',
            'id' => 'otp-form',
            ],
            'action' => Url::to(['bank-payment'])
        ]);
        ?>  
        <div class="panel-body">
            <div class="grid-search no-effect" >

                <?= Html::activeHiddenInput($searchModel, 'payment_cycle_code'); ?>
                <?= Html::activeHiddenInput($searchModel, 'union_code'); ?>
                <?php 
                if (is_array($searchModel->mcc_plant_code)) {
                    foreach ($searchModel->mcc_plant_code as $mcc_plant_code) {
                        echo Html::activeHiddenInput($searchModel, 'mcc_plant_code[]', ['value' => $mcc_plant_code]);
                    }
                } else {
                    echo Html::activeHiddenInput($searchModel, 'mcc_plant_code');
                }
                if (is_array($searchModel->bmc_code)) {
                    foreach ($searchModel->bmc_code as $bmc_code) {
                        echo Html::activeHiddenInput($searchModel, 'bmc_code[]', ['value' => $bmc_code]);
                    }
                } else {
                    echo  Html::activeHiddenInput($searchModel, 'bmc_code');
                }
                echo Html::activeHiddenInput($searchModel, 'customer_type');
                ?>


                <div class="col-sm-2">
                    <?php /*
                    $allow_disburse_without_release = isset(Yii::$app->session->get('unionConfig')[$searchModel->union_code]['allow_disburse_without_release']) ? Yii::$app->session->get('unionConfig')[$searchModel->union_code]['allow_disburse_without_release'] : 0;
                    if ($allow_disburse_without_release == '1') {
                        echo Yii::$app->dropdown->dropdownStatic('payment_release_type', $searchModel, $form, 'form-group', $searchModel->getAttributeLabel('payment_release_type'), FALSE, 'payment_release_type', FALSE, FALSE, FALSE);
                    } else {
                        $searchModel->payment_release_type = '0';
                        echo Html::activeHiddenInput($searchModel, 'payment_release_type');
                    } */
                    ?>
                </div>
                <div class="clearfix"></div>
                <?php
                $attribute = [
                    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
                    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function ($model) {
                            return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
                        }, 'filter' => false],
                    ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function ($model) {
                            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
                        },],
                    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function ($model) {
                            return Yii::$app->general->getCustomer($model, $model->customer_type);
                        }],
                    ['attribute' => 'is_verified',
                        'value' => function ($model) {
                            return ($model->is_verified == 0) ? 'Not Verified' : ($model->is_verified == 1 ? 'Verified' : 'Rejected');
                        }
                    ],
                    ['attribute' => 'bank_name'],
                    ['attribute' => 'branch_name'],
                    ['attribute' => 'bank_account_no'],
                    ['attribute' => 'ifsc'],
                    ['attribute' => 'amount', 'pageSummary' => true, 'value' => 'amount',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                    ['attribute' => 'addition', 'pageSummary' => true, 'value' => 'addition',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                    ['attribute' => 'deduction', 'pageSummary' => true, 'value' => 'deduction',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                    ['attribute' => 'previous_hold', 'pageSummary' => true, 'value' => 'previous_hold',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                    ['attribute' => 'previous_due', 'pageSummary' => true, 'value' => 'previous_due',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                    ['attribute' => 'hold_amount', 'pageSummary' => true, 'value' => 'hold_amount',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                    ['attribute' => 'adjust_amount', 'pageSummary' => true, 'value' => 'adjust_amount',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                    // ['attribute' => 'adjust_recovery', 'pageSummary' => true,
                    //     'hAlign' => Yii::$app->general->ColoumnAlign(),
                    //     'format' => Yii::$app->general->CurrencyFormat()
                    // ],
                    // ['attribute' => 'recovery', 'pageSummary' => true,
                    //     'hAlign' => Yii::$app->general->ColoumnAlign(),
                    //     'format' => Yii::$app->general->CurrencyFormat()
                    // ],
                    ['attribute' => 'final_pay', 'pageSummary' => true, 'value' => function ($model) {
                            return $model->final_pay;
                        },
                        'contentOptions' => ['class' => 'final-amount'],
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                ];

                $grid_option = [
                    'id' => 'bank-vendor-payment-final-grid',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'showPageSummary' => true,
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
                ?>
                <div class="clearfix"></div>
                <div class="col-md-12" >   
                    <?php /*
                    if (!empty($bank_show)) {
                        $array = [];
                        foreach ($bank_show as $data) {
                            $array[$data['union_bank_payment_code']] = $data['bank_name'];
                        }
                        if (count($array) > 1) {
                            ?>
                            <div class="col-sm-2 mr-10">
                                <?php
                                echo $form->field($searchModel, 'union_bank_payment_code')->dropDownList($array, ['prompt' => Yii::t('app', 'Select Bank *')])->label(false);
                                ?>                   
                                <?php
                            } else if (!empty($bank_show) && count($bank_show) == 1) {
                                $searchModel->union_bank_payment_code = $bank_show[0]['union_bank_payment_code'];
                                echo Html::activeHiddenInput($searchModel, 'union_bank_payment_code');
                            }
                        } */
                        ?>
                    </div>
                    <?= Html::button(Yii::t('app', 'Disburse'), ['class' => 'btn btn-primary disburse', 'name' => 'member']); ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'payment-disburse'); ?> 
                </div>
                <?= $this->render('/tbl-member-payment/verify-otp', ['model' => $searchModel, 'form' => $form]) ?>
                <div class="clearfix"></div>
               
            </div>
        </div>
         <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = "
$(document).ready(function(){ 
    $('.kv-panel-before').hide();
    function SumAmount() {
        var total = parseFloat(0.00);
        $('.checkbox').each(function() {
            if($(this).is(':checked')){
                var amt = $(this).closest('tr').find('.final-amount').text();
                amt=amt.replace(',','');
                total =  parseFloat(total) + parseFloat(amt);  
            }
        }).get();
        $('#total-payment').html('Total Payable :: '+total.toFixed(2));
    }      
});
$('.disburse').on('click',function(){
    // var postVspDisbData = $('#otp-form').serializeArray();
    var bank = document.getElementById('tblvendorpaymentholdrelease-union_bank_payment_code');
    var bankCode= bank != null ? bank.value : '';
    if(bankCode == '' &&  bank != null){
        var dispMessage = '" . Yii::t('app', 'Please select bank for disbursement.') . "';
        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+dispMessage+'</span>');
    } else {
        var ucode = '" . $searchModel->union_code . "';
        var payCycleCode = ' ". $searchModel->payment_cycle_code . "';
        var bmcCode = ".json_encode($searchModel->bmc_code).";
        var type = 'VSP';
        $.ajax({
            type: 'post',
            url: '" . Url::to(['tbl-member-payment/validate-bank-details']) . "',
            data: {'union_code':ucode,'union_bank_payment_code':bankCode,'payment_cycle_code':payCycleCode,'bmc_code':bmcCode,'type':type},
            success: function (data) {
                var obj = $.parseJSON(data);
                if (obj.status == 'success') {
                    if(obj.is_send_otp == 'yes'){
                        sendotp();
                    } else {
                        $('#otp-form').submit();
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    }
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
                                //$('form#w1').submit();
                                // sendotp();
                                if(obj.is_send_otp == 'yes'){
                                    sendotp();
                                } else {
                                    $('#otp-form').submit();
                                    $('#loadercontent').show();
                                    $('#pageloader').show();
                                }
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
                                if(obj.is_send_otp == 'yes'){
                                    sendotp();
                                } else {
                                    $('#otp-form').submit();
                                    $('#loadercontent').show();
                                    $('#pageloader').show();
                                }
                            }
                        }
                    });
                } else {
                    bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
                }
            }
        });                
    }
});
function sendotp(){
    var bank = document.getElementById('tblvendorpaymentholdrelease-union_bank_payment_code');
    var bankCode= bank != null ? bank.value : '';
    var ucode = '".$searchModel->union_code . "';
    $.ajax({
        type: 'post',
        url: '" . Url::to(['tbl-member-payment/send-otp']) . "',
        data: {'union_code':ucode,'union_bank_payment_code':bankCode},
        success: function (data) {
            $('#loadercontent').hide();
            $('#pageloader').hide();
            var obj = $.parseJSON(data);
            if (obj.status == 'success')
            {
                $('#OtpModal').modal('toggle'); 
            } else {
                bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+obj.message+'</span>');
            }
        }
    });  
}";
$this->registerJs($script, View::POS_END, 'data-export-script');
?>
<?php
$script = "    
$('.verify').on('click',function(){     
    var otp=$('#tblvendorpaymentholdrelease-otp_code').val();
    if(otp!=''){
        $.ajax({
            type: 'post',
            url: '" . Url::to(['tbl-member-payment/verify-otp']) . "',
            data: 'otp_code='+otp,
            success: function (data) {
                var obj = $.parseJSON(data);
                if (obj.status == 'success')
                {
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
});";
$this->registerJs($script, View::POS_END, 'otp-verify-script');
?>