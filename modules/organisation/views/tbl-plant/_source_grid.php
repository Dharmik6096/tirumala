<?php

$attribute = [
    ['attribute' => 'party_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->party, 'party_name');
        }, 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'party-grid-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'conversion_vendor_mapping_id,conversion_vendor_mapping_id,tbl-plant/delete-mapping'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>