<?php

use yii\helpers\Html;

$attribute = [

    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        },
        'filter' => false,],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        },
        'filter' => false,],
    ['attribute' => 'bill_head_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->billHeadCode, 'bill_head_name');
        }, 'filter' => false,],
    ['attribute' => 'payment_cycle_code', 'value' => function($model) {
            return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'from_date')) . ' to ' . Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'to_date'));
        }, 'filter' => false], ['attribute' => 'amount'],
];
$grid_option = [
    'id' => 'bill-head-detail-list',
    'attributes' => $attribute,
    'active_column' => true,
   
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>