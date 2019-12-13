<?php

use yii\helpers\Html;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE],
    ['attribute' => 'customer_code'],
    ['attribute' => 'customer_name'],
    ['attribute' => 'local_name', 'filter' => FALSE],
    ['attribute' => 'gst_no',],
    ['attribute' => 'customer_type', 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->dropdown->getRecords('customer_type')['data'][$model->customer_type] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('customer_type', $searchModel, 'customer_type')],
    ['attribute' => 'sap_code'],
    ['attribute' => 'refference_code'],
];

$grid_option = [
    'id' => 'customer-master-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
