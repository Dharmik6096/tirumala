<?php

use yii\web\View;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
?>
<div class="clearfix"></div>
<div class="hide_toolbar_only hide_filters_only">

    <?php
    $attribute = [
            ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
            }, 'vAlign' => 'middle'],
            ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
            }, 'vAlign' => 'middle'],
            ['attribute' => 'bmc_silos_info_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->silosInfoCode, 'silo_no');
            }],
        'chamber_no',
        'dispatch_qty',
        'qty_diff',
            ['attribute' => 'qty_diff_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->qtyDiffType, 'qty_diff_type_name');
            }, 'vAlign' => 'middle'],
        'balance_qty',
        'fat',
        'snf',
        'water',
        'temperature',
        'clr',
        'protein',
        'density',
        'lactose',
        'freezing_point',
        'hsn_code',
        'seal_no_top',
        'seal_no_bottom',
        'seal_no_broken',
        'dip_open',
        'dip_close',
        'dip_diff',
        'rtpl',
        'amount',
            [
            'attribute' => 'qty_auto',
            'value' => function($model) {
                return $model->qty_auto == 1 ? 'Yes' : 'No';
            }, 'filter' => false],
            [
            'attribute' => 'qlty_auto',
            'value' => function($model) {
                return $model->qlty_auto == 1 ? 'Yes' : 'No';
            }, 'filter' => false],
            [
            'attribute' => 'is_rejected',
            'value' => function($model) {
                return $model->is_rejected == 1 ? 'Yes' : 'No';
            }, 'filter' => false],
    ];

    $grid_option = [
        'id' => 'bmc-milk-dispatch-txn-list',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'actions' => [
            'view-config' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-config', 'data-original-title' => 'View Config Input', 'data-val' => $model->bmc_milk_dispatch_txn_code];
                return GhostHtml::a_alert('<i class="fa fa-eye"></i>', ['/tankermovement/tbl-bmc-milk-dispatch/view-config', 'id' => $model->bmc_milk_dispatch_txn_code], $options);
            },
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
<div id='config_detail_view'></div>
