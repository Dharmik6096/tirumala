<?php

$attribute = [
    'task_performed_for',
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Code'),
        'filter' => false],
        ['attribute' => 'bmc_name',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
        ['attribute' => 'task_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->task_datetime);
        }, 'filter' => false],
        ['attribute' => 'user_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }],
        ['attribute' => 'task_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->taskTypeCode, 'task_type');
        }],
        ['attribute' => 'form_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->formTypeCode, 'form_name');
        }],
    'title',
    'description',
    'status',
        [
        'attribute' => 'is_cancel',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_cancel'),
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->is_cancel, 'boolean_value');
        }, 'visible' => false],
        [
        'attribute' => 'is_notified',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_notified'),
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->is_notified, 'boolean_value');
        }, 'visible' => false],
        ['attribute' => 'notified_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->notified_datetime);
        }, 'visible' => false, 'filter' => false],
];
$grid_option = [
    'id' => 'task-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => TRUE
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
