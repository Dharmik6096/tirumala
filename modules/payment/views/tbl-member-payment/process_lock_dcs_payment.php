<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;

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
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title . ' (' . $bmc_info . ')' ?>   
            <div id="total-payment">
                Total Payable :: <?= $tot_amt; ?>
            </div>     
        </div>


        <div class="panel-body">    
            <div class="grid-search large-search hidden-print">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'member-wise-payment-summary-form',
                            'validateOnBlur' => false,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
//                            'action' => Url::to(['list-member-payment-summary-data']),
                            'action' => Url::to(['list-member-payment'])
                ]);
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
                <?= Html::hiddenInput('process_lock_flag', 'Process', ['class' => 'process_lock_flag']); ?>
                <?php
                $attribute = [
                        ['class' => 'kartik\grid\CheckboxColumn',
                        'rowSelectedClass' => GridView::TYPE_SUCCESS,
                        'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                        'checkboxOptions' => function ($model) {
                            return ['value' => $model['dcs_code']];
                        }],
                        ['attribute' => 'ref_code', 'label' => Yii::t('app', 'DCS Code'), 'value' => function ($model) {
                            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
                        }],
                        ['attribute' => 'dcs_code_ex', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function ($model) {
                            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                        }],
                        ['attribute' => 'dcs_code', 'value' => function ($model) {
                            return !empty($model->dcs_name) ? $model->dcs_name : Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                        }],
                        ['attribute' => 'member_count'],
                        ['attribute' => 'kg_fat'],
                        ['attribute' => 'kg_snf'],
                        ['attribute' => 'qty', 'pageSummary' => true],
                        ['attribute' => 'shortage_amount',
                        'label' => Yii::t('app', 'Shortage Amount'),
                        'value' => function ($model) {
                            $other_member_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveryOtherMember, 'recovery_amount'));
                            $mpg_member_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveryMpgMember, 'recovery_amount'));
                            $total_shortage_amount = (!empty($other_member_amount) ? $other_member_amount : 0) + (!empty($mpg_member_amount) ? $mpg_member_amount : 0);
                            return $total_shortage_amount;
                        }, 'visible' => $milk_short_recovery_member == '1', 'pageSummary' => true],
                        ['attribute' => 'recovered_amount',
                        'label' => Yii::t('app', 'Recovered Amount'),
                        'value' => function ($model) {
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
                    'id' => 'member-payment-process-first',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'showPageSummary' => true,
                    'actions' => [
                        'bill-head' => function ($url, $model) {
                            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'title' => 'View Bill Head', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code];
                            return GhostHtml::a_alert('<i class="fa fa fa-money-bill"></i>', ['/payment/tbl-member-payment/bill-head', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code], $options);
                        },
                        'members' => function ($url, $model) {
                            $options = ['data-bs-toggle' => 'tooltip', 'target' => '_blank', 'data-placement' => 'top', 'title' => Yii::t('app', 'View Members')];
                            return GhostHtml::a('<i class="fa fa-users"></i>', ['/payment/tbl-member-payment/payment-members-list', 'cycle' => $model->payment_cycle_code, 'dcs_code' => $model->dcs_code], $options);
                        },
                    ]
                        //'actions' => []
                ];
                $rowOptions = function ($model)use ($milk_short_recovery_member) {
                    if ($milk_short_recovery_member == 1) {
                        $other_member_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveryOtherMember, 'recovery_amount'));
                        $mpg_member_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveryMpgMember, 'recovery_amount'));
                        $member_recovered_amount = floatval(Yii::$app->general->getforeignkey($model->shortageRecoveredMember, 'amount'));
                        $total_shortage_amount = (!empty($other_member_amount) ? $other_member_amount : 0) + (!empty($mpg_member_amount) ? $mpg_member_amount : 0);

                        if ($milk_short_recovery_member == 1 && $total_shortage_amount > 0 && $total_shortage_amount != $member_recovered_amount) {
                            return ['class' => 'danger'];
                        }
                    }
                    return '';
                };
                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create'], false, [], [], true, $rowOptions);
                ?>
                <div class="col-md-12" >
                    <?php if (!empty($dataProvider->getModels())) { ?>
                        <?php foreach ($dataProvider->getModels() as $data) { ?>
                            <?= Html::activeHiddenInput($model, 'dcs_code[]', ['value' => $data['dcs_code']]); ?>
                        <?php } ?>
                        <?= Html::button(Yii::t('app', 'Process'), ['class' => 'btn btn-primary btn-login', 'id' => 'adjust']); ?>
                        <?php //Html::button(Yii::t('app', 'Confirm'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock']); ?>
                        <?php // Yii::$app->controls->save('Next', $model); ?>
                    <?php } ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'create-payment', '', 'btn-login'); ?>        
                </div>
                <?php ActiveForm::end(); ?>


            </div>
        </div>
    </div>
</div>
<div id='bill_head_view'></div>
<?php
$script = "
$('.kv-panel-before').hide(); 

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


$('#adjust').click(function() {
    $('.process_lock_flag').val('Process');
    var checkBoxCount = $('.kv-row-checkbox:checked').length;
    if(checkBoxCount > 0) {
        $('#flag').val($(this).prop('name'));
        $('#member-wise-payment-summary-form').submit();
//        $('form#w1').submit();
    } else {
        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Please Select atleast one Record') . "</span>');
    }
});
$('#adjust-lock').click(function() {
    $('.process_lock_flag').val('Lock');
    var negativeCount = " . $negativeValCount . ";
    var message = '" . $message . "';
        
    if(negativeCount > 0) {
        var dispMessage = '" . Yii::t('app', 'Net Payable must be Positive for each Member.') . "';
        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+dispMessage+'</span>');
    } else {
        bootbox.confirm({
            message: '<div class=\'bg-danger\'><i class=\'fa fa-question-circle\'></i></div><span>'+message+'</span>',
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
                    $('#member-wise-payment-summary-form').submit();
                }
            }
        });
    }
//    $('form#w1').submit();
});";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>