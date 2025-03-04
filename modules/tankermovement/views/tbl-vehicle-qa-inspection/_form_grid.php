<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'transporter_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->transporter, 'transporter_name');
        }, 'filter' => false],
        ['attribute' => 'vehicle_code', 'label' => Yii::t('app', 'Vehicle No.'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
        }, 'filter' => false],
        ['attribute' => 'trip_code'],
        ['attribute' => 'transaction_datetime', 'value' => function ($model) {
            return Yii::$app->controls->view_datetime($model->transaction_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => false],
        ['attribute' => 'status'],
        ['attribute' => 'remarks'],
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
    'id' => 'vehicle-qa-inspection-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'delete' => ['option' => 'vehicle_qa_inspection_code,vehicle_qa_inspection_code,tbl-vehicle-qa-inspection/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
