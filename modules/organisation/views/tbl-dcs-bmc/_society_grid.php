<?php

$attribute = [
    ['attribute' => 'from_dest', 'label' => 'Society Code', 'filter' => false],
    ['attribute' => 'from_dest', 'label' => 'Society Name', 'value' => 'societyCode.dcs_name', 'filter' => false],
];

$grid_option = [
    'id' => 'bmc-society-mapping-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>