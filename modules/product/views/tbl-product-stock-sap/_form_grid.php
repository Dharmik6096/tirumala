<?php
$attribute = [
    ['attribute' => 'mcc_ref_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->tblMccPlant, 'ref_code');
    }, 'filter' => false],
    ['attribute' => 'mcc_name', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->tblMccPlant, 'name');
    }, 'filter' => false],
    ['attribute' => 'product_code', 'filter' => FALSE],
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