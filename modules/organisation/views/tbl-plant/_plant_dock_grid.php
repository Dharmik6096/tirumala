<?php

$attribute = [
        ['attribute' => 'dock_no', 'filter' => false],
        ['attribute' => 'dock_name', 'filter' => false],
];

$grid_option = [
    'id' => 'plant-dock-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dockdataProvider, $docksearchModel, $grid_option);
?>