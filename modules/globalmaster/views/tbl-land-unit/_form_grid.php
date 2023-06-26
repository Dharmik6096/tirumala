<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'land_unit_code', 'value' => 'land_unit_code'],
    ['attribute' => 'land_unit_name', 'value' => 'land_unit_name'],
    ['attribute' => 'local_name'],
    ['attribute' => 'land_unit',
        'width' => '100px',
        'value' => function($model) {
            return isset($model->landUnit->land_unit_name) ? $model->landUnit->land_unit_name : '';
        },
        'filter' => Html::activeDropDownList($searchModel, 'land_unit', $unit_data, ['prompt' => 'Select', 'class' => 'form-control'])],
    ['attribute' => 'conversion_factor', 'value' => 'conversion_factor'],
];
$grid_option = [
    'id' => 'land-unit-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'edit' => function ($url, $model) {
            $disabled = ($model->is_default == 1) ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip' , 'data-placement' => 'top', 'data-original-title' => 'Edit' , 'class' => $disabled];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/globalmaster/tbl-land-unit/update', 'id' => $model->land_unit_code], $options);
        },
        'delete' => ['option' => 'land_unit_name,land_unit_code,tbl-land-unit/delete,checkDefault()'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>