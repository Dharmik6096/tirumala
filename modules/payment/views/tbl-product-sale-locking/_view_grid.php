<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'customer_type', 'value' => function($model) {
            return isset($model->productSaleCode->customer_type) ? (strtolower($model->productSaleCode->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($model->productSaleCode->customerType, 'customer_desc') ) : '';
        }, 'vAlign' => 'middle'],
    ['attribute' => 'customer_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productSaleCode, 'customer_code');
        }, 'label' => Yii::t('app', 'Code')],
    ['label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return isset($model->productSaleCode->customer_type) ? Yii::$app->general->getCustomer($model->productSaleCode, $model->productSaleCode->customer_type, true) : '';
        }, 'vAlign' => 'middle'],
    ['label' => Yii::t('app', 'Code'), 'value' => function($model) {
            return isset($model->productSaleCode->customer_type) ? Yii::$app->general->getCustomer($model->productSaleCode, $model->productSaleCode->customer_type, FALSE, FALSE, true) : '';
        }, 'vAlign' => 'middle'],
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return isset($model->productSaleCode->customer_type) ? Yii::$app->general->getCustomer($model->productSaleCode, $model->productSaleCode->customer_type) : '';
        }, 'vAlign' => 'middle'],
    ['attribute' => 'product_code', 'value' => 'productCode.product_name', 'filter' => false],
    ['attribute' => 'sap_batch_no', 'filter' => false],
    ['attribute' => 'rate', 'format' => Yii::$app->general->CurrencyFormat(), 'filter' => false],
    ['attribute' => 'quantity', 'filter' => false],
    ['attribute' => 'amount', 'format' => Yii::$app->general->CurrencyFormat(), 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'product-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
