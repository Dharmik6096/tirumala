<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

Url::remember();

$this->title = 'Vehicles';
?>
<div class="tbl-vehicle-payment-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
<?= $this->title; ?>           
        </div>


        <div class="panel-body">
            <div class="grid-search large-search hidden-print">

            </div>

            <?php
            $attribute = [
                ['attribute' => 'transporter_code', 'value' => 'transporterCode.transporter_name', 'label' => Yii::t('app', 'Transporter')],
                ['attribute' => 'bmc_code', 'value' => 'bmcCode.bmc_name', 'label' => Yii::t('app', 'BMC')],
                ['attribute' => 'total_amount', 'pageSummary' => true,
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => Yii::$app->general->CurrencyFormat(),
                    'value' => 'total_amount'],
                ['attribute' => 'total_deduction', 'pageSummary' => true,
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => Yii::$app->general->CurrencyFormat(),
                    'value' => 'total_deduction'],
                ['attribute' => 'final_amount', 'pageSummary' => true,
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => Yii::$app->general->CurrencyFormat(),
                    'value' => 'final_amount'],
            ];

            $grid_option = [
                'id' => 'vehicle-index',
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