<?php

$attribute = [
        ['attribute' => 'task_type', 'filter' => true],
        [
            'attribute' => 'has_form', 
            'filter' => ['1' => 'Yes', '0' => 'No'],
            'value' => function ($model) {
                return ($model->has_form == 1) ? 'Yes' : 'No';
            }
        ],
        ['attribute' => 'union_code', 'visible' => false],
        
];
$grid_option = [
    'id' => 'task-type-list',
    'attributes' => $attribute,
    'active_column' => true,
    
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
