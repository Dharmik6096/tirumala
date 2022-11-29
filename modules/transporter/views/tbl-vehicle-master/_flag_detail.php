<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'wef_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }, 'filter' => false],
    ['attribute' => 'qty_flag', 'value' => function($model) {
            return isset($model->qty_flag) ? Yii::$app->dropdown->getRecords('billing_qty_flag')['data'][$model->qty_flag] : '';
        }],
];

$grid_option = [
    'id' => 'qty-flag-list',
    'attributes' => $attribute,
    'active_column' => false,
        // 'actions' => []
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
