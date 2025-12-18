<?php

use app\modules\usermanagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true],
    ['attribute' => 'asset_group_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assetGroupCode, 'asset_group_name');
        }, 'visible' => true],
    ['attribute' => 'asset_code'],
    ['attribute' => 'asset_name'],
    ['attribute' => 'is_serial_number', 'value' => function($model) {
            return ($model->is_serial_number == 0) ? 'No' : 'Yes';
        }, 'filter' => FALSE],
    ['attribute' => 'is_spare', 'value' => function($model) {
            return ($model->is_spare == 0) ? 'No' : 'Yes';
        }, 'filter' => FALSE],
    ['attribute' => 'local_name', 'filter' => FALSE],
    ['attribute' => 'ref_code', 'filter' => FALSE],
    ['attribute' => 'asset_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assetTypeCode, 'asset_type_name');
        }, 'visible' => true],
];

$grid_option = [
    'id' => 'asset-master-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
        'asset-bom' => function ($url, $model) {
            $class = $model->is_spare == 0 ? '' : 'disabled';
            $options = ['data-code' => $model->asset_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Asset Bom', 'class' => $class,];
            return GhostHtml::a('<i class="fa fa-money-bill"></i>', ['/assetmanagement/tbl-asset-bom/create', 'id' => $model->asset_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
