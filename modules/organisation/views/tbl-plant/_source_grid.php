<?php

$attribute = [
    ['attribute' => 'party_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->party, 'party_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'sap_vendor_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->party, 'sap_vendor_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'owner_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->party, 'owner_name');
        }, 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'party-grid-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'party.party_name,conversion_vendor_mapping_id,tbl-plant/delete-mapping'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>