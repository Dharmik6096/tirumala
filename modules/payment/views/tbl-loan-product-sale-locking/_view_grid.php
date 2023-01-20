<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'bmc_code', 'filter' => false],
    ['attribute' => 'bmc', 'label' => Yii::t('app', 'BMC'), 'filter' => false],
    ['attribute' => 'customer_code', 'filter' => false],
    ['attribute' => 'customer_name', 'filter' => false],
    ['attribute' => 'invoice_date', 'filter' => false],
    ['attribute' => 'product', 'filter' => false],
    ['attribute' => 'amount', 'filter' => false],
];

$grid_option = [
    'id' => 'product-lock-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
