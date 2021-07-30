<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
    //'product_group_code',
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false,],
    'product_group_code',
    'product_group_name',
//    'local_name',
    ['attribute' => 'ref_code', 'visible' => false],
    ['attribute' => 'local_name', 'filter' => false],
        ['attribute' => 'unit_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unitCode, 'unit_name');
        }],
];

$grid_option = [
    'id' => 'product-group-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => false,
        'update' => true,
        'delete' => ['option' => 'product_group_name,product_group_code,tbl-product-group/delete'],
    /* 'mapping' => function ($url, $model) {
      $class = $model->nationalized_bank == 1 ? 'link-disable' : '';
      $options = ['data-name' => $model->bank_name, 'data-val' => $model->bank_code, 'class' => $class, 'title' => 'District Mapping'];
      return GhostHtml::a('<span class="glyphicon glyphicon-link"></span>', ['/organisation/tbl-banks/map-districts', 'id' => $model->bank_code], $options);
      }, */
    //'mapping'=> ['option' => 'bank_name,bank_code,/organisation/tbl-banks/map-districts'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
