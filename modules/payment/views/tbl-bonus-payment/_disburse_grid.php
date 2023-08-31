<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = 'Bonus Payment Disburse';
$action = Url::to(['process-payment-disburse']);
$fromDate = Yii::$app->controls->view_date($model->from_datetime);
$toDate = Yii::$app->controls->view_date($model->to_datetime);
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
        <?php
        if (!empty($model->bmc_code)) {
            foreach ($model->bmc_code as $bmc_code) {
                ?>
                <?= Html::activeHiddenInput($model, 'bmc_code[]', ['value' => $bmc_code]); ?>
                <?php
            }
        }
        ?>
        <?= Html::activeHiddenInput($model, 'payment_type'); ?>
        <?= Html::activeHiddenInput($model, 'customer_type'); ?>
        <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
    </div>
    <?php
    $attribute = [
            ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
            },],
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
            ['attribute' => 'customer_ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
            }],
            ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->customer_type);
            }],
            ['attribute' => 'from_datetime', 'label' => Yii::t('app', 'Period'),
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->from_datetime) . ' to ' . Yii::$app->controls->view_date($model->to_datetime);
            }, 'filter' => false],
            ['attribute' => 'payment_count'],
            ['attribute' => 'kg_fat'],
            ['attribute' => 'kg_snf'],
            ['attribute' => 'qty', 'pageSummary' => true],
            ['attribute' => 'amount', 'pageSummary' => true],
            ['attribute' => 'addition', 'pageSummary' => true],
            ['attribute' => 'deduction', 'pageSummary' => true],
            ['attribute' => 'net_payable', 'pageSummary' => true],
    ];

    $grid_option = [
        'id' => 'bonus-payment-disburse-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => true,
        'actions' => [
            'bill-head' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-val' => $model->bonus_payment_summary_code];
                return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-bonus-payment/summary-bill-head', 'id' => $model->bonus_payment_summary_code], $options);
            },
            'payment-detail' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'target' => '_blank', 'data-original-title' => 'View Detail', 'data-val' => $model->bonus_payment_summary_code];
                return GhostHtml::a('<i class="fa fa-users"></i>', ['/payment/tbl-bonus-payment/payment-detail', 'id' => $model->bonus_payment_summary_code], $options);
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
