<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'milk_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
        }, 'filter' => false],
    ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
        }, 'filter' => false],
    ['attribute' => 'dispatch_qty', 'filter' => false],
    ['attribute' => 'qty_mode',
        'value' => function ($model) {
            return isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '';
        }, 'filter' => false],
    ['attribute' => 'nos_of_can', 'filter' => false],
    ['attribute' => 'converted_qty', 'filter' => false],
    ['attribute' => 'converted_qty_mode',
        'value' => function ($model) {
            return isset($model->converted_qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->converted_qty_mode] : '';
        }, 'filter' => false],
    ['attribute' => 'avg_fat', 'filter' => false],
    ['attribute' => 'avg_snf', 'filter' => false],
    ['attribute' => 'avg_clr', 'filter' => false],
    ['attribute' => 'water', 'filter' => false],
];

$grid_option = [
    'id' => 'transcation-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($bdataProvider, $bsearchModel, $grid_option, [Yii::$app->controller->action->id, 'id' => Yii::$app->request->get('id')]);
?>
