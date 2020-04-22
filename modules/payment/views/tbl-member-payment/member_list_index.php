<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

Url::remember();

$this->title = 'Members';
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
                        return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
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
            ];

            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create'], false);
            ?>
            <div class="clearfix"></div>

        </div>
    </div>
</div>