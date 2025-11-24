<?php

$attribute = [
    ['attribute' => 'department_id', 'filter' => FALSE],
    ['attribute' => 'department'],
    ['attribute' => 'local_name', 'filter' => FALSE],
    ['attribute' => 'seq_no', 'label' => 'Level', 'filter' => true, 'value' => function($model) {
            return $model->seq_no ? 'level ' . $model->seq_no : 'N/A';
        }],
];

$grid_option = [
    'id' => 'department-master-list',
    'attributes' => $attribute,
    'active_column' => TRUE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
