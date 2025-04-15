<?php

use kartik\grid\GridView;

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'value' => 'bmc_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'bmc_ref_code', 'label' => Yii::t('app', 'BMC') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Code'), 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'route_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'filter' => false],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'value' => 'dcs_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'dcs_ref_code', 'label' => Yii::t('app', 'DCS') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    [
        'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }
    ],
    [
        'attribute' => 'shift_code',
        'filter' => false,
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }
    ],
    [
        'attribute' => 'milk_type_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }
    ],
    [
        'attribute' => 'milk_quality_type_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
        }
    ],
    ['attribute' => 'milk_analyser_type_code', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_silos_info_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->silosCode, 'silo_no');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'antibiotic', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'tare_weight', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'gross_weight', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'ws_code', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'vehicle_no', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'route_arrival_time', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->route_arrival_time);
        }, 'filter' => false],
    ['attribute' => 'own_bmc_code', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'own_mcc_plant_code', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'purchase_rate_code', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'scheme_rate', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'scheme_rate_code', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'actual_rate', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'adt_param', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'adt_value', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'sample_no', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'fat', 'value' => 'fat', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'snf', 'value' => 'snf', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'clr', 'value' => 'clr', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'water', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'protein', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'density', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'lactose', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'rtpl', 'filter' => false],
    ['attribute' => 'amount', 'value' => 'amount', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'qty_auto', 'value' => function ($model) {
            return isset($model->qty_auto) ? Yii::$app->dropdown->getRecords('is_quality_auto')['data'][$model->qty_auto] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_quality_auto', $searchModel, 'qty_auto'),],
    ['attribute' => 'qlty_auto', 'value' => function ($model) {
            return isset($model->qlty_auto) ? Yii::$app->dropdown->getRecords('is_quality_auto')['data'][$model->qlty_auto] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_quality_auto', $searchModel, 'qlty_auto'),],
    ['attribute' => 'no_of_can', 'visible' => false, 'filter' => true],
    [
        'attribute' => 'converted_qty_mode',
        'value' => function ($model) {
            return isset($model->converted_qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->converted_qty_mode] : '';
        },
        'filter' => false,
        'visible' => FALSE
    ],
    ['attribute' => 'converted_qty', 'value' => 'converted_qty', 'vAlign' => 'middle', 'filter' => false],
    [
        'attribute' => 'qty_mode',
        'value' => function ($model) {
            return isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '';
        },
        'filter' => false,
        'visible' => FALSE
    ],
];

$grid_option = [
    'id' => 'tbl-bmc-collection-audit-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'default_sorting' => FALSE
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>