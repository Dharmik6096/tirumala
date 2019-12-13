<?php

$attribute = [
    ['attribute' => 'p_mcc_plant_code', 'filter' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'source-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'mccCode.name,mcc_plant_mapping_code,tbl-mcc-plant/delete-mcc'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>