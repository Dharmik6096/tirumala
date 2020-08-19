<?php

use yii\helpers\Html;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true],
    ['attribute' => 'sloc_code'],
    ['attribute' => 'store_location_name'],
    ['attribute' => 'store_location_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->storeLocType, 'slt_name');
        }],
    ['attribute' => 'reference_code'],
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
