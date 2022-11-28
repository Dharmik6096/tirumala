<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;

$client_code = \Yii::$app->session->get('eiplCode') == 'UMANG' ? TRUE : FALSE;
?>
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
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_code', 'visible' => false, 'value' => 'bmc_code', 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'bmc_ref_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mainBmcCode, 'ref_code');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'bmc_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'route_code', 'label' => (Yii::t('app', 'Route Name')), 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsCode, ['routeMapping'], 'route_name') == 'N/A' ? Yii::$app->general->getforeignkey($model->routeCode, 'route_name') : Yii::$app->general->getmultiforeignkey($model->dcsCode, ['routeMapping'], 'route_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_silos_info_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->silosCode, 'silo_no');
        }, 'vAlign' => 'middle'],
//    ['attribute' => 'route_name', 'value' => function($model) {
//            return Yii::$app->general->getmultiforeignkey($model->dcsCode, ['routeMapping'], 'route_name');
//        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        },],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'SAP Vendor Code'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type, FALSE, FALSE, FALSE, TRUE);
        }, 'filter' => FALSE, 'visible' => $client_code],
    ['attribute' => 'customer_code', 'filter' => FALSE],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
        }, 'filter' => false],
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type);
        }],
    ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type, false, false, TRUE);
        }],
    [
        'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
    ['attribute' => 'shift_code', 'value' => 'shiftCode.shift', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'doc_no', 'vAlign' => 'middle'],
    ['attribute' => 'sample_no', 'vAlign' => 'middle'],
    ['attribute' => 'created_at', 'vAlign' => 'middle', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->created_at, 'php:H:i:s');
        }],
    ['attribute' => 'milk_type_code', 'value' => function($model) {
            return isset($model->milkType) ? $model->milkType->animal_type_name : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
            return isset($model->milkQualityType) ? $model->milkQualityType->milk_quality_type_name : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'fat', 'value' => 'fat', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'snf', 'value' => 'snf', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'clr', 'value' => 'clr', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'qty_mode',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('p_ltr_kg', $searchModel, 'qty_mode'),
        'value' => function ($model) {
            return isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '';
        },],
    ['attribute' => 'converted_qty', 'value' => 'converted_qty', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'rtpl'],
    ['attribute' => 'amount', 'value' => 'amount', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'transporter_code', 'value' => function($model) {
            return isset($model->transporter) ? $model->transporter->transporter_name : '';
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'vehicle_code', 'value' => function($model) {
            return isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '';
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'collection_type', 'value' => function($model) {
            return !empty($model->collection_type) ? ((Yii::$app->dropdown->getRecords('collection_type')['data'][$model->collection_type] != '') ? Yii::$app->dropdown->getRecords('collection_type')['data'][$model->collection_type] : '') : '';
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'route_arrival_time', 'filter' => false, 'visible' => false],
    ['attribute' => 'remarks', 'value' => 'remarks', 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'originating_org_type', 'filter' => FALSE,
        'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('originating_type_flag', $model, 'originating_type');
        },],
    ['attribute' => 'originating_type', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('originating_type', $searchModel, 'originating_type'),
        'value' => function ($model) {
            return Yii::$app->general->getOriginatingType($model, 'originating_type');
        },],
    ['attribute' => 'tag_1', 'value' => function($model) {
            return Yii::$app->general->getSapStatus($model->tag_1 . $model->tag_2);
        }, 'filter' => false],
    ['attribute' => 'error_desc', 'filter' => FALSE],
    ['attribute' => 'adt_param', 'filter' => FALSE, 'visible' => false],
    ['attribute' => 'adt_value', 'filter' => FALSE, 'visible' => false],
    ['attribute' => 'qlty_time', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->qlty_time);
        }, 'filter' => false],
    ['attribute' => 'qty_time', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->qty_time);
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'bmc-collection',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'view' => TRUE,
//        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>