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
                //['attribute' => 'dcs_code', 'value' =>  'dcs_code'],
                ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name'],
                ['attribute' => 'member_code', 'value' => 'memberCode.member_name'],
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
                'id' => 'member-index',
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