<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'transporter_code',
        'label' => Yii::t('app', 'Transporter'),
        'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->vehicleCode, ['transporter'], 'transporter_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Code'),
        'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_ref_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle'],
    ['attribute' => 'bmc_name',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type, false, false, TRUE);
        }],
    ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        },],
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type);
        }],
    [
        'attribute' => 'receipt_at',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('receipt_at', $searchModel, 'receipt_at'),
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->receipt_at, 'receipt_at');
        }],
    [
        'attribute' => 'vehicle_entry_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->vehicle_entry_date);
        }],
    ['attribute' => 'trip_code'],
    ['attribute' => 'vehicle_code',
        'label' => Yii::t('app', 'Vehicle No.'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
        }, 'filter' => false],
    ['attribute' => 'qty'],
    [
        'attribute' => 'arrival_time',
        'value' => function($model) {
            return Yii::$app->controls->view_time($model->arrival_time);
        }, 'filter' => false],
    ['attribute' => 'gross_weight'],
    ['attribute' => 'tare_weight'],
    [
        'attribute' => 'tare_weight_time',
        'value' => function($model) {
            return Yii::$app->controls->view_time($model->tare_weight_time);
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'milk-vehicle-entry-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
