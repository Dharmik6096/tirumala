<?php

$attribute = [
    ['attribute' => 'bmc_ref_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'filter' => false],
    ['attribute' => 'bmc_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
    ['attribute' => 'product_code', 'filter' => true],
    ['attribute' => 'item_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'item_code');
        }, 'filter' => false],
    ['attribute' => 'qty', 'filter' => FALSE],
    [
        'attribute' => 'stock_date',
        'filter' => false,
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->stock_date);
        },
    ],
];

$grid_option = [
    'id' => 'product-stock-sap-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>