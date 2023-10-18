<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = 'Party Payment Disburse';
$action = Url::to(['process-payment-disburse']);
//echo "<pre>";
//print_r($model);
//die;
$fromDate = Yii::$app->controls->view_date($model->from_date);
$toDate = Yii::$app->controls->view_date($model->to_date);
$message = Yii::t('app', 'Payment data will be Disbursed for ' . ' (' . $fromDate . ' to ' . $toDate . '). Are you sure ?');
$showButtons = (!empty($dataProvider->getModels())) ? TRUE : FALSE;
//$showButtons = (!empty($model->payment_cycle_code) && !empty($dataProvider->getModels())) ? TRUE : FALSE;
?>
<div class="" >
    <?php
    $form = ActiveForm::begin([
                'id' => 'party-payment-disburse',
                'action' => $action,
                'method' => 'post'
    ]);
    ?>
    <div class="grid-button-wrap" >
        <?php //Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
        <?= Html::activeHiddenInput($model, 'union_code'); ?>
        <?= Html::activeHiddenInput($model, 'payment_type'); ?>
        <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
    </div>
    <?php
    $attribute = [
//        ['attribute' => 'union_code'],
        ['attribute' => 'party_master_code', 'label' => Yii::t('app', 'Code')],
        ['attribute' => 'party_master_code', 'label' => Yii::t('app', 'Party'), 'value' => function($model){
            return Yii::$app->general->getforeignkey($model->partyMaster, 'party_name');
        }],
        ['attribute' => 'payment_type'],
        ['attribute' => 'from_date', 'label' => Yii::t('app', 'Period'),
            'value' => function ($model) {
//                echo "<pre>";
//                print_r($model);
//                die;
                return Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date);
            }, 'filter' => false],
//            ['attribute' => 'payment_count'],
        ['attribute' => 'disp_kg_fat'],
        ['attribute' => 'disp_kg_snf'],
        ['attribute' => 'disp_qty'],
        ['attribute' => 'rec_kg_fat'],
        ['attribute' => 'rec_kg_snf'],
        ['attribute' => 'rec_qty'],
        ['attribute' => 'rd_kg_fat_diff'],
        ['attribute' => 'rd_kg_snf_diff'],
        ['attribute' => 'rd_qty_diff'],
        ['attribute' => 'no_of_days'],
        ['attribute' => 'total_qty'],
        ['attribute' => 'avg_fat'],
        ['attribute' => 'avg_snf'],
        ['attribute' => 'avg_rate'],
        ['attribute' => 'total_amount'],
        ['attribute' => 'total_addition'],
        ['attribute' => 'total_deduction'],
        ['attribute' => 'final_amount'],
        ['attribute' => 'net_amount'],
        ['attribute' => 'adjust_remark'],
        ['attribute' => 'bank_name'],
        ['attribute' => 'bank_code'],
        ['attribute' => 'branch_name'],
        ['attribute' => 'branch_code'],
        ['attribute' => 'ifsc'],
        ['attribute' => 'bank_account_no'],
        ['attribute' => 'beneficiary_name'],
        ['attribute' => 'status'],
    ];

    $grid_option = [
        'id' => 'party-payment-disburse-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => true,
        'actions' => [
            'bill-head' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'data-original-title' => 'View Bill Head', 'data-val' => $model->party_payment_code];
                return GhostHtml::a_alert('<i class="fa fa-money"></i>', ['/payment/tbl-party-payment/party-bill-head-detail', 'id' => $model->party_payment_code], $options);
            },
            'payment-detail' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'target' => '_blank', 'data-original-title' => 'View Detail', 'data-val' => $model->party_payment_code];
                return GhostHtml::a('<i class="fa fa-users"></i>', ['/payment/tbl-party-payment/payment-detail', 'id' => $model->party_payment_code], $options);
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
                url: '" . Url::to(['/payment/tbl-party-payment/party-bill-head-detail']) . "',
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
                            $('form#party-payment-disburse').submit();
                        }
                    }
                });
        } else {
            $('form#party-payment-disburse').submit();
        }
    });    
});";
    $this->registerJs($script, View::POS_END, 'party-payment-disburse-script');
    