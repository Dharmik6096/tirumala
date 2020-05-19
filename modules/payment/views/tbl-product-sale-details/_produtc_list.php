<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
//    ['attribute' => 'product_sale_code'],
    ['attribute' => 'product_code', 'value' =>'productCode.product_name'],
    ['attribute' => 'rate','format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'quantity'],
    ['attribute' => 'amount','format' => Yii::$app->general->CurrencyFormat(),],
];

$grid_option = [
    'id' => 'product-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
