<?php

use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'mcc_plant_code', 'label' => Yii::t('app', 'MCC') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code');
        }, 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Society Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . ' ' . Yii::t('app', 'Code Ex'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'date_time_of_collection', 'label' => 'Collection Date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ], 'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
        ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilter('shift', $searchModel, 'shift_code', Yii::t('app', 'Select'))],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkQualityCode, 'milk_quality_type_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'qty'],
        ['attribute' => 'fat'],
        ['attribute' => 'snf'],
        ['attribute' => 'clr'],
        ['attribute' => 'water', 'filter' => false, 'visible' => false],
        ['attribute' => 'protein', 'filter' => false, 'visible' => false],
        ['attribute' => 'density', 'filter' => false, 'visible' => false],
        ['attribute' => 'lactose', 'filter' => false, 'visible' => false],
        ['attribute' => 'sample_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'qty_mode', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('p_ltr_kg', $searchModel, 'qty_mode'), 'value' => function ($model) {
            return isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '';
        },],
        ['attribute' => 'converted_qty', 'vAlign' => 'middle'],
        ['attribute' => 'converted_qty_mode', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('p_ltr_kg', $searchModel, 'converted_qty_mode'), 'value' => function ($model) {
            return isset($model->converted_qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->converted_qty_mode] : '';
        }, 'filter' => false], ['attribute' => 'rtpl', 'filter' => true],
        ['attribute' => 'amount', 'format' => Yii::$app->general->CurrencyFormat()],
        ['attribute' => 'purchase_rate_code', 'vAlign' => 'middle'],
        ['attribute' => 'qlty_time', 'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => ['pluginOptions' => ['format' => 'dd-mm-yyyy', 'autoclose' => true]
        ], 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->qlty_time, 'php:d-m-Y H:i:s');
        }],
        ['attribute' => 'qty_time', 'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => ['pluginOptions' => ['format' => 'dd-mm-yyyy', 'autoclose' => true]
        ], 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->qty_time, 'php:d-m-Y H:i:s');
        }],
        ['attribute' => 'qlty_auto', 'value' => function ($model) {
            return isset($model->qlty_auto) ? Yii::$app->dropdown->getRecords('is_quality_auto')['data'][$model->qlty_auto] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_quality_auto', $searchModel, 'qlty_auto'),],
        ['attribute' => 'qty_auto', 'value' => function ($model) {
            return isset($model->qty_auto) ? Yii::$app->dropdown->getRecords('is_quality_auto')['data'][$model->qty_auto] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_quality_auto', $searchModel, 'qty_auto'),],
        ['attribute' => 'milk_analyser_type_code', 'visible' => false, 'filter' => false],
        ['attribute' => 'ws_code', 'visible' => false, 'filter' => false],
        ['attribute' => 'source_of_milk', 'vAlign' => 'middle'],
        ['attribute' => 'remarks', 'visible' => false, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'device_lat', 'visible' => false, 'filter' => FALSE],
        ['attribute' => 'device_long', 'visible' => false, 'filter' => FALSE],
        ['attribute' => 'version_no', 'filter' => FALSE, 'visible' => false],
];
$grid_option = [
    'id' => 'sample-milk-collection-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

