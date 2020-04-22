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
                    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code')],
                    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
                    }],
                    ['attribute' => 'dcs_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                    }],
                    ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member Code')],
                    ['attribute' => 'member_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
                    }],
                    ['attribute' => 'kg_fat'],
                    ['attribute' => 'kg_snf'],
                    ['attribute' => 'qty', 'pageSummary' => true],
                    ['attribute' => 'total_amount', 'value' => 'total_amount', 'pageSummary' => true],
                    ['attribute' => 'total_addition', 'pageSummary' => true],
                    ['attribute' => 'total_deduction', 'pageSummary' => true],
                    ['attribute' => 'previous_hold', 'pageSummary' => true],
                    ['attribute' => 'previous_due', 'pageSummary' => true],
                    ['attribute' => 'net_payable', 'pageSummary' => true],
                    ['attribute' => 'hold_amount', 'pageSummary' => true],
                    ['attribute' => 'additional_pay', 'pageSummary' => true],
                    ['attribute' => 'final_amount', 'pageSummary' => true],
                    ['attribute' => 'adjust_remark', 'pageSummary' => true],
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