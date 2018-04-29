<?php

use yii\helpers\Html;
$attribute = [
    ['attribute' => 'animal_type_code', 'value' => 'animal_type_code'],
    ['attribute' => 'animal_type_name', 'value' => 'animal_type_name'],
    ['attribute' => 'local_name'],
    ['attribute' => 'short_name'],
    ['attribute' => 'is_milch',
        'width' => '100px',
        'value' => function($model) {
            return ($model->is_milch == 1) ? 'Yes' : 'No';
        },
        'filter' => Html::activeDropDownList($searchModel, 'is_milch', ['' => 'Select', 1 => 'Yes', 0 => 'No'], ['class' => 'form-control'])]];
$grid_option = [
    'id' => 'animal-type-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'animal_type_name,animal_type_code,tbl-animal-type/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>