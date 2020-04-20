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
                    ['attribute' => 'dcs_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                    }], //'dcsCode.dcs_name'],
                ['attribute' => 'member_code', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
                    }],
                    ['attribute' => 'qty'],
                    ['attribute' => 'avg_fat'],
                    ['attribute' => 'avg_snf'],
                    ['attribute' => 'avg_rate'],
                    ['attribute' => 'total_amount', 'pageSummary' => true,
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => Yii::$app->general->CurrencyFormat(),
                    'value' => 'total_amount'],
                    ['attribute' => 'total_deduction', 'pageSummary' => true,
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => Yii::$app->general->CurrencyFormat(),
                    'value' => 'total_deduction'],
                    ['attribute' => 'adjust_amount', 'pageSummary' => true,
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => Yii::$app->general->CurrencyFormat(),
                    'value' => 'adjust_amount'],
                    ['attribute' => 'final_amount', 'pageSummary' => true,
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => Yii::$app->general->CurrencyFormat(),
                    'value' => 'final_amount'],
                'adjust_remark'
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