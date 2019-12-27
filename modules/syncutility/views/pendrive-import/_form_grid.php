<?php

$attribute = [
    ['attribute' => 'file_id'],
    ['attribute' => 'file_name'],
    ['attribute' => 'source_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('file_type', $searchModel, 'source_type'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('file_type')['data'][$model->source_type]) ? Yii::$app->dropdown->getRecords('file_type')['data'][$model->source_type] : '';
        },],
    ['attribute' => 'total_record'],
    ['attribute' => 'processed_record'],
    ['attribute' => 'file_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('file_status', $searchModel, 'status'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('file_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('file_status')['data'][$model->status] : '';
        },],
    [
        'attribute' => 'created_at',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->created_at);
        }],
    [
        'attribute' => 'updated_at',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->updated_at);
        }],
];
$grid_option = [
    'id' => 'pd-file-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
//    'actions' => [
//      //  'view' => true,
//    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
