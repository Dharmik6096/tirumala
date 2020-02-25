<?php

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        }, 'filter' => false],
    ['attribute' => 'customer_code', 'filter' => false, 'label' => Yii::t('app', 'Code')],
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type);
        }, 'filter' => false],
    ['attribute' => 'bill_head_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->billHeadCode, 'bill_head_name');
        }, 'filter' => false,],
    ['attribute' => 'payment_cycle_code', 'value' => function($model) {
            return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'from_date')) . ' to ' . Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'to_date'));
        }, 'filter' => false],
    ['attribute' => 'no_installment', 'filter' => false],
    ['attribute' => 'amount', 'filter' => false],
];
$grid_option = [
    'id' => 'bill-head-detail-list-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>