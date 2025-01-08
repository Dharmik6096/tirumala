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
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'value' => 'dcs_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'dcs_ref_code', 'label' => Yii::t('app', 'DCS') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Code'), 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'route_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'filter' => false],
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
    ['attribute' => 'sample_no', 'vAlign' => 'middle', 'filter' => false],
    [
        'attribute' => 'milk_type_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }
    ],
    [
        'attribute' => 'qty_mode',
        'value' => function ($model) {
            return isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '';
        },
        'filter' => false,
        'visible' => FALSE
    ],
    ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'filter' => false],
    [
        'attribute' => 'converted_qty_mode',
        'value' => function ($model) {
            return isset($model->converted_qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->converted_qty_mode] : '';
        },
        'filter' => false,
        'visible' => FALSE
    ],
    ['attribute' => 'converted_qty', 'value' => 'converted_qty', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'cans', 'filter' => false],
    ['attribute' => 'return_type', 'value' => function ($model) {
            return Yii::$app->dropdown->getRecords('return_type')['data'][$model->return_type];
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'device_id', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'version_no', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'doc_no', 'filter' => false],
    ['attribute' => 'vehicle_no', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false, 'visible' => false],
    ['attribute' => 'rejection_reason_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->rejectReason, 'rejection_reason');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'rejection_responsibility_code', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'reference_measurement', 'filter' => false, 'visible' => FALSE],
];

$grid_option = [
    'id' => 'tbl-weight-rejection-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
