<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

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
    ['attribute' => 'created_at',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->created_at);
        }],
    [
        'attribute' => 'created_by',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }],
//    'created_by',
    ['attribute' => 'pick_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->pick_datetime);
        }],
    ['attribute' => 'cron_pick_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->cron_pick_datetime);
        }],
    ['attribute' => 'response_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime);
        }],
    [
    'attribute' => 'interval',
        'filter' => FALSE,
        'value' => function($model) {
        $fromDate = !empty($model->cron_pick_datetime) ? $model->cron_pick_datetime : $model->pick_datetime;
        if ($fromDate !== null && !empty($model->response_datetime)) {
            $datetime1 = new DateTime($fromDate);
            $datetime2 = new DateTime($model->response_datetime);
            $interval = $datetime1->diff($datetime2);
            return $interval->format('%h') . ":" . $interval->format('%i') . ":" . $interval->format('%s');
        } else {
            return '0:0:0';
        }
    }
],

    'response_msg',
    [
        'attribute' => 'error_file_path',
        'format' => 'raw',
        'value' => function($model) {
            if (!empty($model->error_file_path)) {
                $absoluteBaseUrl = Url::base(true);
                $absoluteBaseUrl = str_replace("/web", "", $absoluteBaseUrl);
                return Html::a('<i class="fa fa-download"><i/>', $absoluteBaseUrl . $model->error_file_path, ['target' => '_blank']);
            }
        }],
    [
        'attribute' => 'success_file_path',
        'format' => 'raw',
        'value' => function($model) {
            if (!empty($model->success_file_path)) {
                $absoluteBaseUrl = Url::base(true);
                $absoluteBaseUrl = str_replace("/web", "", $absoluteBaseUrl);
                return Html::a('<i class="fa fa-download"><i/>', $absoluteBaseUrl . $model->success_file_path, ['target' => '_blank']);
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