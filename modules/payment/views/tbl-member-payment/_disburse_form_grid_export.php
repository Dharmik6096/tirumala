<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;

$action = Url::to(['disburse-member-payment']);
$fromDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'from_date'));
$toDate = Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'to_date'));
$message = Yii::t('app', 'Payment data of  all society will be Disbursed for ' . Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name') . ' (' . $fromDate . ' to ' . $toDate . '). Are you sure ?');
?>
<div class="" >
    <?php
    $form = ActiveForm::begin([
                'action' => $action,
                'method' => 'post'
    ]);
    ?>
    <div class="grid-button-wrap" >
        <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
        <?= Html::activeHiddenInput($model, 'union_code'); ?>
        <?= Html::activeHiddenInput($model, 'plant_code'); ?>
        <?= Html::activeHiddenInput($model, 'mcc_plant_code'); ?>
        <?= Html::activeHiddenInput($model, 'bmc_code'); ?>
        <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
    </div>
    <?php
    $attribute = [
//            ['class' => 'kartik\grid\CheckboxColumn',
//            'rowSelectedClass' => GridView::TYPE_SUCCESS,
//            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
//            'checkboxOptions' => function($model) {
//                return ['value' => $model['dcs_code']];
//            }],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code')],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
            }],
        ['attribute' => 'dcs_code', 'value' => function($model) {
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
    <?php if (!empty($model->payment_cycle_code) && !empty($dataProvider->getModels())) { ?>
        <div class="col-md-12 mt10" >
            <?= Html::button(Yii::t('app', 'Disburse Payment'), ['class' => 'btn btn-primary sub', 'name' => 'member']); ?>
            <?= Html::button(Yii::t('app', 'Export Data'), ['class' => 'btn btn-primary sub', 'name' => 'member-file']); ?>
        </div>
    <?php } ?>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
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
                            $('form#w1').submit();
                        }
                    }
                });
            }


        } else {
            $('form#w1').submit();
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
