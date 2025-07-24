<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'plant_code', 'label' => Yii::t('app', 'Plant') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'ref_code');
        }, 'visible' => true, 'filter' => FALSE],
        ['attribute' => 'vehicle_code', 'value' => function($model) {
            return isset($model->vehicle) ? $model->vehicle->parsing_no . '/' . $model->vehicle->vehicleType->vehicle_type_name : '';
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => true],
        ['attribute' => 'trip_code'],
        ['attribute' => 'chamber_no'],
        ['attribute' => 'fat', 'filter' => false],
        ['attribute' => 'snf', 'filter' => false],
        ['attribute' => 'is_qty_only', 'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_qty_only');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_qty_only')],
        ['attribute' => 'is_pending_merge', 'filter' => FALSE,
        'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_pending_merge');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_pending_merge')],
        ['attribute' => 'tested_by', 'filter' => false],
        ['attribute' => 'verified_by', 'filter' => false],
];
foreach ($config_list as $config) {
    $attribute[] = [
        'attribute' => 'config_code', 'label' => Yii::t('app', $config->config_name),
        'value' => function ($model) use ($config) {
            $model->config_code = $config->config_code;
            $configResult = $model->configResult;
            return !empty($configResult) ? (!empty($configResult->configResultCode->config_result) ? $configResult->configResultCode->config_result : $configResult->config_result) : '';
        }
    ];
}
$grid_option = [
    'id' => 'milk-vehicle-entry-qlty-merge-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#']);
?>