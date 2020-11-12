<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;

$attribute = [
    ['attribute' => 'salary_head_name'],
    ['attribute' => 'salary_head_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('type', $searchModel, 'salary_head_type'),
        'value' => function($model) {
            return isset($model->salary_head_type) ? Yii::$app->dropdown->getRecords('type')['data'][$model->salary_head_type] : 'N/A';
        },],
];
$grid_option = [
    'id' => 'salary-heads-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
//        'delete' => ['option' => 'salary_head_name,salary_head_code,tbl-salary-heads/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>