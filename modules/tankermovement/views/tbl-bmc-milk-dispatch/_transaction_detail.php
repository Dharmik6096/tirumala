<?php

use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;
use app\modules\usermanagement\models\User;

$updateTransaction = User::canRoute('/tankermovement/tbl-bmc-milk-dispatch/update-transaction');
?>
<div class="clearfix"></div>
<div class="hide_toolbar_only hide_filters_only">

    <?php
    $attribute = [
        ['attribute' => 'milk_type_code', 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
            }, 'vAlign' => 'middle'],
        ['attribute' => 'milk_quality_type_code', 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
            }, 'vAlign' => 'middle'],
        [
            'attribute' => 'bmc_silos_info_code',
            'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->silosInfoCode, 'silo_no');
            },
            'visible' => $isVisible
        ],
        'chamber_no',
        'shift_of_milk',
        'dispatch_qty',
        ['attribute' => 'qty_diff', 'visible' => $isVisible],
        ['attribute' => 'qty_diff_type_code', 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->qtyDiffType, 'qty_diff_type_name');
            }, 'vAlign' => 'middle'],
        ['attribute' => 'balance_qty', 'visible' => $isVisible],
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
        [
            'attribute' => 'qty_auto',
            'value' => function ($model) {
                return $model->qty_auto == 1 ? 'Yes' : 'No';
            }, 'filter' => false
        ],
        [
            'attribute' => 'qlty_auto',
            'value' => function ($model) {
                return $model->qlty_auto == 1 ? 'Yes' : 'No';
            }, 'filter' => false
        ],
        [
            'attribute' => 'is_rejected',
            'value' => function ($model) {
                return $model->is_rejected == 1 ? 'Yes' : 'No';
            }, 'filter' => false
        ],
        'test_report_no',
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
            'edit' => function ($url, $model) use ($updateTransaction, $txnEdit) {
                if ($txnEdit === 'false' || !$updateTransaction) {
                    return '';
                }
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record', 'data-val' => $model->bmc_milk_dispatch_txn_code, 'data-name' => $model->bmc_milk_dispatch_txn_code, 'title' => Yii::t('app', 'Edit')];
                return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/tankermovement/tbl-bmc-milk-dispatch/update-transaction'], $options);
            },
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
<div id='config_detail_view'></div>