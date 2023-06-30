<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

Url::remember();

$this->title = Yii::t('app', 'Members');
?>
<div class="tbl-member-payment-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>


        <div class="panel-body">
            <div class="grid-search large-search hidden-print">

            </div>

            <?php
            $attribute = [
                ['attribute' => 'union_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
                    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
                ['attribute' => 'plant_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
                ['attribute' => 'mcc_plant_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
                ['attribute' => 'bmc_code', 'visible' => false,
                    'label' => Yii::t('app', 'BMC Code'),
                    'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
                ['attribute' => 'bmc_code', 'visible' => false,
                    'label' => Yii::t('app', 'BMC Name'),
                    'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                    }, 'vAlign' => 'middle', 'filter' => false],
                ['attribute' => 'dcs_code', 'visible' => false],
                ['attribute' => 'ex_code', 'visible' => false, 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                    }],
                ['attribute' => 'dcs_name', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                    }, 'label' => Yii::t('app', 'DCS')],
                ['attribute' => 'member_code', 'value' => function($model) {
                        return substr($model->member_code, -4);
                    }, 'label' => Yii::t('app', 'Member Code')],
                ['attribute' => 'member_code', 'value' => function($model) {
                        return !empty($model->member_name) ? $model->member_name : Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
                    }],
                ['attribute' => 'payment_cycle_code', 'value' => function($model) {
                        return Yii::$app->controls->view_date($model->from_datetime) . ' to ' . Yii::$app->controls->view_date($model->to_datetime);
                    }, 'filter' => false, 'format' => 'raw'],
                [
                    'attribute' => 'payment_date',
                    'value' => function($model) {
                        return Yii::$app->controls->view_date($model->payment_date);
                    }],
                ['attribute' => 'kg_fat'],
                ['attribute' => 'kg_snf'],
                ['attribute' => 'qty'],
                ['attribute' => 'avg_fat', 'visible' => false],
                ['attribute' => 'avg_snf', 'visible' => false],
                ['attribute' => 'avg_rate', 'visible' => false],
                ['attribute' => 'bank_name', 'visible' => false],
                ['attribute' => 'branch_name', 'visible' => false],
                ['attribute' => 'ifsc', 'visible' => false],
                ['attribute' => 'bank_account_no', 'visible' => false],
                ['attribute' => 'total_amount'],
                ['attribute' => 'total_addition'],
                ['attribute' => 'total_deduction'],
                ['attribute' => 'previous_hold'],
                ['attribute' => 'previous_due'],
                ['attribute' => 'net_payable'],
                ['attribute' => 'hold_amount'],
                ['attribute' => 'additional_pay'],
                ['attribute' => 'final_amount'],
                ['attribute' => 'adjust_remark'],
                ['attribute' => 'payment_status'],
            ];

            $grid_option = [
                'id' => 'member-list-index',
                'attributes' => $attribute,
                'active_column' => false,
                'showPageSummary' => true,
                'actions' => [
                    'member-bill-head' => function ($url, $model) {
                        $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-head', 'title' => 'View Bill Head', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code, 'data-member_code' => $model->member_code];
                        return GhostHtml::a_alert('<i class="fa fa fa-money-bill"></i>', ['/payment/tbl-member-payment/member-bill-head', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code, 'member_code' => $model->member_code], $options);
                    },
                    'member-payment-installment' => function ($url, $model) {
                        $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-member-installment', 'title' => 'View Bill Head', 'data-payment_cycle_code' => $model->payment_cycle_code, 'data-bmc_code' => $model->bmc_code, 'data-dcs_code' => $model->dcs_code, 'data-member_code' => $model->member_code];
                        return GhostHtml::a_alert('<i class="fa fa-plus"></i>', ['/payment/tbl-member-payment/view-member-installment', 'payment_cycle_code' => $model->payment_cycle_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code, 'member_code' => $model->member_code], $options);
                    },
                ]
            ];

            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create'], false);
            ?>
            <div class="clearfix"></div>

        </div>
    </div>
</div>


<div id='bill_head_view'></div>
<div id='member_installment_view'></div>
<?php
$script = " 


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

$(document).on('click','.view-member-installment',function(e){
    var payment_cycle_code= $(this).attr('data-payment_cycle_code');
    var bmc_code= $(this).attr('data-bmc_code');
    var dcs_code= $(this).attr('data-dcs_code');
    var member_code= $(this).attr('data-member_code');
    ViewMemberInstallment(payment_cycle_code, bmc_code, dcs_code, member_code);
});

function ViewMemberInstallment(payment_cycle_code, bmc_code, dcs_code, member_code){
    if(payment_cycle_code != '' && bmc_code != '' && dcs_code != ''){         
    $.ajax({
            type: 'get',
            url: '" . Url::to(['/payment/tbl-member-payment/view-member-installment']) . "',
            data: {'payment_cycle_code' : payment_cycle_code,'bmc_code' : bmc_code,'dcs_code' : dcs_code, 'member_code': member_code},
            beforeSend:function(data) {
                $('#loadercontent').show();
                $('#pageloader').show();
            },
            success: function(data) {
                $('#member_installment_view').html(data);
                $('#MemberInstallmentModel').modal('toggle');              
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


$this->registerJs($script, View::POS_END, 'member-payment-head-script');
?>