<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>

<?php


$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'label' => Yii::t('app', 'Company')],
    ['attribute' => 'dcs_payment_cycle_code', 'value' => function($model) {
            $fdate =  Yii::$app->general->getforeignkey($model->fromDate, 'from_date');
            $todate = Yii::$app->general->getforeignkey($model->fromDate, 'to_date');
            return Yii::$app->controls->view_date($fdate) . ' to ' . Yii::$app->controls->view_date($todate);
        }, 'filter' => false, 'format' => 'raw','label' => Yii::t('app', 'Payment Cycle')],
    ['attribute' => 'status'],
    ['attribute' => 'payment_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->payment_date);
        },'filter' => false,],
    ['attribute' => 'file_name'],
    ['attribute' => 'file_status'],
    ['attribute' => 'file_status_desc'],
    ['attribute' => 'file_error_code'],
    ['attribute' => 'file_error_desc'],
    ['attribute' => 'no_of_txn'],
    ['attribute' => 'file_status_code'],
    ['attribute' => 'utf_ref_no'],
    ['attribute' => 'instrument_number'],
];

$grid_option = [
    'id' => 'bank-payment-log-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>