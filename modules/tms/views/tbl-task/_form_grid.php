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
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Code'),
        'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'bmc_name',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'remarks'],
    'task_code',
    'task_type_code',
    'form_type_code',
    'task_performed_for',
    'title',
        // 'description',
        // 'task_datetime',
        // 'is_cancel',
        // 'user_code',
        // 'route_code',
        // 'bmc_code',
        // 'mcc_plant_code',
        // 'plant_code',
        // 'union_code',
        // 'is_notified',
        // 'notified_datetime',        
];
$grid_option = [
    'id' => 'task-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
