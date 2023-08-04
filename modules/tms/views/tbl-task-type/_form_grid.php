<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function ($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'filter' => false, 'visible' => false],
    ['attribute' => 'task_type', 'filter' => true],
    [
        'attribute' => 'has_form',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'has_form'),
        'value' => function($model) {
            return Yii::$app->general->getStaticValue($model->has_form, 'boolean_value');
        },
    ],

];
$grid_option = [
    'id' => 'task-type-list',
    'attributes' => $attribute,
    'active_column' => true,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
