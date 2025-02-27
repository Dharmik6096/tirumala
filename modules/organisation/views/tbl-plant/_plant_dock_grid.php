<?php

use app\modules\usermanagement\components\GhostHtml;

$attribute = [
        ['attribute' => 'dock_no', 'filter' => false],
        ['attribute' => 'dock_name', 'filter' => false],
];

$grid_option = [
    'id' => 'plant-dock-list',
    'attributes' => $attribute,
    'active_column' => false,
];
if ($mapping_flag == 'view') {
    unset($grid_option['actions']['delete-mapping']);
} else {
    $grid_option['actions']['delete-mapping'] = function($url, $model) use ($mapping_flag) {
        $class = ($mapping_flag == 'view') ? 'link-disable' : '';
        $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Delete Dock Mapping', 'class' => $class];
        return GhostHtml::a('<i class="fa fa-trash"></i>', ['tbl-plant/delete-dock-mapping', 'id' => $model->plant_dock_mapping_code], $options);
    };
}
Yii::$app->grid->bind($dockdataProvider, $docksearchModel, $grid_option);
?>