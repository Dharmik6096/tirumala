<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;

$attribute = [
    ['attribute' => 'designation_code', 'visible' => FALSE],
    ['attribute' => 'designation_name'],
    ['attribute' => 'designation_type',
//        'width' => '100px',
        'value' => function($model) {
            return ($model->designation_type == 1) ? 'Committee' : 'Staff';
        },
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('designation_type', $searchModel)],
];
$grid_option = [
    'id' => 'designation-type-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
//        'delete' => ['option' => 'designation_name,designation_code,tbl-designation/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>