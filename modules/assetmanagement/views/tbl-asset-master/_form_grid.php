<?php

use yii\helpers\Html;

?>
<?php

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
    ['attribute' => 'cmpl_product_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assetType, 'cmpl_product_name');
        }],
    ['attribute' => 'local_name', 'visible' => TRUE, 'filter' => FALSE],
];

$grid_option = [
    'id' => 'asset-master-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
