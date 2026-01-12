<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$grnWithoutStockEntry = Yii::$app->general->getUnionConfigResult(Yii::$app->session->get('Unions'), 'grn_without_stock_entry') == 1 ? TRUE : FALSE;
$attribute = [
//    ['attribute' => 'product_sale_code'],
    ['attribute' => 'product_code', 'value' => 'productCode.product_name'],
    ['attribute' => 'product_desc', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->productCode, 'product_desc');
    }],
    ['attribute' => 'rate', 'format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'quantity'],
    ['attribute' => 'amount', 'format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'remarks'],
    ['attribute' => 'send_status',
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('data_post_status')['data'][$model->send_status]) ? Yii::$app->dropdown->getRecords('data_post_status')['data'][$model->send_status] : 'Pending';
        }, 'filter' => false, 'visible' => false],
    ['attribute' => 'picked_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->picked_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => FALSE, 'visible' => false],
    ['attribute' => 'response_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => FALSE, 'visible' => false],
    ['attribute' => 'resp_desc', 'filter' => FALSE, 'visible' => false],
];

$gridId = 'product-list';
$grid_option = [
    'id' => $gridId,
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'repush' => function ($url, $model) use ($grnWithoutStockEntry, $gridId) {
            if ($grnWithoutStockEntry) {
                return Yii::$app->general->createRePushLink($url, $model, $gridId, 'product_sale_transaction_code', 'send_status');
            }
            return '';
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
