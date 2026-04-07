<?php

$attribute = [
    ['attribute' => 'symptom_id', 'filter' => false],
    ['attribute' => 'symptom_name',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->symptom, 'symptom_name');
        }, 'filter' => false
    ],
];

$grid_option = [
    'id' => 'medicine-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'symptom_id,medicine_symptom_id,tbl-medicine-master/delete-source'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>