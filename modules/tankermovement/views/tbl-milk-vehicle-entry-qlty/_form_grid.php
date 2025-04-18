<?php

use app\modules\usermanagement\components\GhostHtml;

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
        ['attribute' => 'arrival_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->arrival_datetime);
        }, 'filter' => false],
        ['attribute' => 'lot_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->lot_datetime);
        }, 'filter' => false],
        ['attribute' => 'lot_no', 'filter' => false],
        ['attribute' => 'status', 'filter' => false],
        ['attribute' => 'status_datetime', 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->status_datetime);
        }, 'filter' => false],
        ['attribute' => 'acidity', 'filter' => false],
        ['attribute' => 'mbrt', 'filter' => false],
        ['attribute' => 'fat', 'filter' => false],
        ['attribute' => 'snf', 'filter' => false],
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
    'id' => 'milk-vehicle-entry-qlty-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => function ($url, $model) {
            $name = $model->chamber_no;
            $tripCode = $model->trip_code;
            $tripData = $model->getTripData($model->trip_code);
            $class = (($tripData > 0) && ($model->status == 'pending' || $model->status == 'done')) ? '' : 'disabled';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->milk_vehicle_entry_qlty_code, 'data-name' => $name, 'trip-code' => $tripCode];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/tankermovement/tbl-milk-vehicle-entry-qlty/update', 'id' => $model->milk_vehicle_entry_qlty_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#']);
?>