<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

?>
<?php

$attribute = [
        ['attribute' => 'file_type', 'visible' => true, 'filter' => true],
        ['attribute' => 'report_title', 'visible' => true, 'filter' => true],
        ['attribute' => 'created_at',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->created_at, 'php:d-m-Y H:i:s');
        }],
        ['attribute' => 'pick_datetime',
        'value' => function($model) {
            $fromDate = !empty($model->cron_pick_datetime) ? $model->cron_pick_datetime : $model->pick_datetime;
            return Yii::$app->controls->view_datetime($fromDate, 'php:d-m-Y H:i:s');
        }, 'filter' => FALSE],
        ['attribute' => 'response_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => FALSE],
        ['attribute' => 'response_msg', 'visible' => true, 'filter' => true],
        [
        'attribute' => 'status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('report_req_status', $searchModel, 'status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('report_req_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('report_req_status')['data'][$model->status] : '';
        }],
        [
        'attribute' => 'interval',
        'filter' => FALSE,
        'value' => function($model) {
            if (!empty($model->cron_pick_datetime) && !empty($model->response_datetime)) {
                $datetime1 = new DateTime($model->cron_pick_datetime);
                $datetime2 = new DateTime($model->response_datetime);
                $interval = $datetime1->diff($datetime2);
                return $interval->format('%h') . ":" . $interval->format('%i') . ":" . $interval->format('%s');
            }
        }],
];

$grid_option = [
    'id' => 'report-txn-log',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'download' => function ($url, $model) {
            if (!empty($model->file_path)) {
                $absoluteBaseUrl = Url::base(true);
                return Html::a('<i class="fa fa-download"><i/>', $absoluteBaseUrl . $model->file_path, ['target' => '_blank']);
            }
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>