<?php

use yii\helpers\Html;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true],
    ['attribute' => 'asset_group_code'],
    ['attribute' => 'asset_group_name'],
    ['attribute' => 'reference_code'],
    ['attribute' => 'is_active', 'visible' => false],
];

$grid_option = [
    'id' => 'asset-group-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
