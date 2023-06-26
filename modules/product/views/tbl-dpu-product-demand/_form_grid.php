<?php
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'Id', 'filter'=>false],
    ['attribute' => 'bmc_code', 'filter'=>false],
    ['attribute' => 'bmc_name', 'value' => function($model) { return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'); }, 'filter'=>false],
    ['attribute' => 'dcs_code', 'filter'=>false],
    ['attribute' => 'dcs_name', 'value' => function($model) { return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'); }, 'filter'=>false],
    ['attribute' => 'member_code', 'filter'=>false],
    ['attribute' => 'member_name', 'value' => function($model) { return Yii::$app->general->getforeignkey($model->memberCode, 'member_name'); }, 'filter'=>false],
    ['attribute' => 'product_code', 'filter'=>false],
    ['attribute' => 'product_name', 'value' => function($model) { return $model->is_loan_product == 1 ? Yii::$app->general->getforeignkey($model->loanProductCode, 'product_name') : Yii::$app->general->getforeignkey($model->productCode, 'product_name'); }, 'filter'=>false],    ['attribute' => 'PQty', 'visible' => false, 'filter'=>false],
    ['attribute' => 'PAmount', 'visible' => false, 'filter'=>false],
    ['attribute' => 'trDate', 'visible' => false, 'value' => function ($model) { return Yii::$app->controls->view_date($model->trDate); }, 'filter'=>false],
    ['attribute' => 'shift', 'visible' => false, 'value' => function($model) { return Yii::$app->general->getforeignkey($model->shiftCode, 'shift'); }, 'filter'=>false],
    ['attribute' => 'Status', 'visible' => false, 'filter'=>false],
    ['attribute' => 'type', 'value' => function($model) { return Yii::$app->general->getmultiforeignkey($model->productCode, ['productGroupCode'], 'product_group_name'); }, 'visible' => false, 'filter'=>false],
    ['attribute' => 'ProductStatus', 'visible' => false, 'value' => function($model) { return !empty($model->ProductStatus) ? $model->ProductStatus : 'DPU'; }, 'filter'=>false],
    ['attribute' => 'unit_code', 'visible' => false, 'value' => function($model) { return Yii::$app->general->getmultiforeignkey($model->productCode, ['unitCode'], 'unit_name'); }, 'filter'=>false],
];

$grid_option = [
    'id' => 'product-sale-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true
//        'delete' => ['option' => ''],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>