<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\View;
?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'value' => 'bmc_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'bmc_ref_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Code'), 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'route_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
            }, 'filter' => false],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'value' => 'dcs_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'dcs_ref_code', 'label' => (Yii::t('app', 'DCS Ref.Code')), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        },],
    ['attribute' => 'customer_code', 'filter' => true],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
        }, 'filter' => false],
        
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
    ['attribute' => 'shift_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }
    ],
    ['attribute' => 'milk_type_code', 'filter' => false],
    ['attribute' => 'milk_quality_type_code', 'filter' => false],
    ['attribute' => 'milk_analyser_type_code', 'filter' => false],
    ['attribute' => 'bmc_silos_info_code', 'filter' => false],
    ['attribute' => 'antibiotic', 'filter' => false],
    ['attribute' => 'tare_weight', 'filter' => false],
    ['attribute' => 'gross_weight', 'filter' => false],
    ['attribute' => 'ws_code', 'filter' => false],
    ['attribute' => 'vehicle_no', 'filter' => false],
    ['attribute' => 'route_arrival_time', 'filter' => false],
    ['attribute' => 'own_bmc_code', 'filter' => false],
    ['attribute' => 'own_mcc_plant_code', 'filter' => false],
    ['attribute' => 'purchase_rate_code', 'filter' => false],
    ['attribute' => 'scheme_rate', 'filter' => false],
    ['attribute' => 'scheme_rate_code', 'filter' => false],
    ['attribute' => 'actual_rate', 'filter' => false],
    ['attribute' => 'adt_param', 'filter' => false],
    ['attribute' => 'adt_value', 'filter' => false],
    ['attribute' => 'sample_no', 'filter' => false],
    ['attribute' => 'qty', 'filter' => false],
    ['attribute' => 'fat', 'filter' => false],
    ['attribute' => 'snf', 'filter' => false],
    ['attribute' => 'clr', 'filter' => false],
    ['attribute' => 'water', 'filter' => false],
    ['attribute' => 'protein', 'filter' => false],
    ['attribute' => 'density', 'filter' => false],
    ['attribute' => 'lactose', 'filter' => false],
    ['attribute' => 'rtpl', 'filter' => false],
    ['attribute' => 'amount', 'filter' => false],
    ['attribute' => 'qty_auto', 'filter' => false],
    ['attribute' => 'qlty_auto', 'filter' => false],
    ['attribute' => 'no_of_can', 'filter' => false],
    ['attribute' => 'converted_qty_mode', 'filter' => false],
    ['attribute' => 'converted_qty', 'filter' => false],
    ['attribute' => 'qty_mode', 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-bmc-collection-audit-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'default_sorting' => FALSE
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>