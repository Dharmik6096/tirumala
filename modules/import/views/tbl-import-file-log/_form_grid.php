<?php

use yii\helpers\Html;
use yii\helpers\Url;

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
    'log_id',
    'file_type',
    'file_name',
    'total_count',
    'success_count',
    'error_count',
    [
        'attribute' => 'status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('file_status', $searchModel, 'status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('file_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('file_status')['data'][$model->status] : '';
        }],
    [
        'attribute' => 'created_at',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->created_at);
        }],
    'created_by',
    [
        'attribute' => 'pick_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->pick_datetime);
        }],
    [
        'attribute' => 'response_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime);
        }],
    'response_msg',
    [
        'attribute' => 'error_file_path',
        'format' => 'raw',
        'value' => function($model) {
            if (!empty($model->error_file_path)) {
                $absoluteBaseUrl = Url::base(true);
                return Html::a('<i class="fa fa-download"><i/>', $absoluteBaseUrl . $model->error_file_path, ['target' => '_blank']);
            }
        }],
];
$grid_option = [
    'id' => 'import-file-list',
    'active_column' => false,
    'attributes' => $attribute,
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>