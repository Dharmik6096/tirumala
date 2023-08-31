<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$this->title = 'Bonus Payment Disburse';
$action = Url::to(['process-payment-disburse']);
$fromDate = Yii::$app->controls->view_date('from_datetime');
$toDate = Yii::$app->controls->view_date('to_datetime');
$message = Yii::t('app', 'Payment data will be Disbursed for ' . ' (' . $fromDate . ' to ' . $toDate . '). Are you sure ?');
$showButtons = (!empty($model->payment_cycle_code) && !empty($dataProvider->getModels())) ? TRUE : FALSE;
?>
<div class="" >
    <?php
    $form = ActiveForm::begin([
                'id' => 'bonus-payment-disburse',
                'action' => $action,
                'method' => 'post'
    ]);
    ?>
    <div class="grid-button-wrap" >
        <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
        <?= Html::activeHiddenInput($model, 'union_code'); ?>
        <?= Html::activeHiddenInput($model, 'plant_code'); ?>
        <?= Html::activeHiddenInput($model, 'mcc_plant_code'); ?>
        <?php foreach ($model->bmc_code as $bmc_code) { ?>
            <?= Html::activeHiddenInput($model, 'bmc_code[]', ['value' => $bmc_code]); ?>
        <?php } ?>
        <?= Html::activeHiddenInput($model, 'payment_type'); ?>
        <?= Html::activeHiddenInput($model, 'customer_type'); ?>
        <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
    </div>
    <?php
    $attribute = [
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
            }, 'filter' => false],
            ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return !empty($model->customer_name) ? $model->customer_name : Yii::$app->general->getCustomer($model, $model->customer_type);
            }],
            ['attribute' => 'amount', 'pageSummary' => true, 'value' => 'amount',
            'label' => Yii::t('app', 'Milk Amount(+)'),
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
            ['attribute' => 'addition', 'pageSummary' => true, 'value' => 'addition',
            'label' => Yii::t('app', 'Addition(+)'),
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
            ['attribute' => 'deduction', 'pageSummary' => true, 'value' => 'deduction',
            'label' => Yii::t('app', 'Deduction(-)'),
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
            ['attribute' => 'final_pay', 'pageSummary' => true, 'value' => 'final_pay',
            'hAlign' => Yii::$app->general->ColoumnAlign(),
            'format' => Yii::$app->general->CurrencyFormat(),
        ],
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
        'id' => 'bonus-payment-disburse-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => true,
        'actions' => [
            'bill-head' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code];
                return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-member-payment/bill-head', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code], $options);
            },
            'payment-detail' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'target' => '_blank', 'data-placement' => 'top', 'data-original-title' => 'View Members'];
                return GhostHtml::a('<i class="fa fa-users"></i>', ['/payment/tbl-member-payment/payment-members-list', 'cycle' => $model['payment_cycle_code'], 'dcs_code' => $model['dcs_code']], $options);
            },
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
    <?php if ($showButtons) { ?>
        <div class="col-md-12 mt10" >
            <?= Html::button(Yii::t('app', 'Disburse Payment'), ['class' => 'btn btn-primary disburse-process', 'name' => 'disburse']); ?>
            <?= Html::button(Yii::t('app', 'Export Data'), ['class' => 'btn btn-primary disburse-process', 'name' => 'export-file']); ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>
<div id='bill_head_view'></div>

<?php
$script = "$('.kv-panel-before').hide();";
$script .= "$(document).ready(function(){
    $(document).on('click','.view-head',function(e){
    var id= $(this).attr('data-val');
  ViewBillHead(id);
    });
    function ViewBillHead(code){
        if(code != ''){         
        $.ajax({
                type: 'get',
                url: '" . Url::to(['/payment/tbl-bonus-payment/summary-bill-head']) . "',
                data: {'code' : code},
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
$('.disburse-process').on('click',function(){
        var flagName = $(this).prop('name');
        $('#flag').val(flagName);
        if(flagName == 'disburse') {
            var message = '" . $message . "';
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
                            $('form#bonus-payment-disburse').submit();
                        }
                    }
                });
        } else {
            $('form#bonus-payment-disburse').submit();
        }
    });    
});";
$this->registerJs($script, View::POS_END, 'bonus-payment-disburse-script');
