<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'dcs_code', 'filter' => false],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
    ['attribute' => 'member_code', 'filter' => false],
    ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'filter' => false],
    ['attribute' => 'product_code', 'label' => Yii::t('app', 'Product'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
        }, 'filter' => false],
    ['attribute' => 'amount', 'format' => Yii::$app->general->CurrencyFormat(), 'filter' => false],
];

$grid_option = [
    'id' => 'product-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
