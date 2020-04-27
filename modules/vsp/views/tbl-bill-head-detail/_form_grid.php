<?php

use yii\helpers\Html;

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
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
            return $model->customer_type == 'MEMBER' ? 'MEMBER' : Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        }],
    ['attribute' => 'customer_code'],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
        }, 'filter' => false],
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type);
        }],
    ['attribute' => 'bill_head_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->billHeadCode, 'bill_head_name');
        }],
    ['attribute' => 'payment_cycle_code', 'value' => function($model) {
            return '<div>' . Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'from_date')) . ' to ' . Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->paymentCycleCode, 'to_date')) . '</div>';
        }, 'format' => 'raw', 'filter' => false],
    ['attribute' => 'no_installment'],
    ['attribute' => 'amount'],
];
$grid_option = [
    'id' => 'bill-head-detail-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => true,
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>