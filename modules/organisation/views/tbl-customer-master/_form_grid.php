<?php

use yii\helpers\Html;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => FALSE],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => FALSE],
    ['attribute' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        }, 'filter' => Yii::$app->dropdown->dropdownfilter('customer_type', $searchModel, 'customer_type', Yii::t('app', 'Select'))],
    ['attribute' => 'customer_code'],
    ['attribute' => 'customer_code_ex'],
    ['attribute' => 'customer_name'],
    ['attribute' => 'local_name', 'filter' => FALSE],
    ['attribute' => 'gst_no',],
    ['attribute' => 'address', 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'local_address', 'filter' => FALSE, 'visible' => FALSE],
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
