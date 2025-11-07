<?php

use yii\helpers\Html;
use kartik\grid\GridView;

//use yii\grid\GridView;
?>
<?php

$attribute = [
    ['attribute' => 'asset_code', 'enableSorting' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assetCode, 'asset_name');
        }, 'filter' => false],
    ['attribute' => 'sap_code', 'filter' => false],
    ['attribute' => 'serial_number', 'filter' => false],
    ['attribute' => 'qty', 'filter' => false],
    ['attribute' => 'received_date', 'attribute' => 'received_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->received_date);
        }, 'filter' => false],
    ['attribute' => 'received_by', 'filter' => false],
    ['attribute' => 'transaction_date', 'attribute' => 'transaction_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->transaction_date);
        }],
//    ['attribute' => 'asset_type', 'value' => function($model) {
//            return isset($model->asset_type) ? Yii::$app->dropdown->getRecords('asset_type')['data'][$model->asset_type] : '';
//        }, 'filter' => false],
    ['attribute' => 'status', 'value' => function($model) {
            return isset($model->status) ? Yii::$app->dropdown->getRecords('asset_status')['data'][$model->status] : '';
        }, 'filter' => false],
    ['attribute' => 'from_type', 'value' => function($model) {
            return ($model->from_type == 'VEN') ? $model->from_type : Yii::$app->general->getmultiforeignkey($model->fromStoreLocCode, ['storeLocType'], 'slt_name');
        }, 'filter' => false],
    ['attribute' => 'from_dest', 'value' => function($model) {
            return ($model->from_type == 'VEN') ? Yii::$app->general->getmultiforeignkey($model->assetDetail, ['manufacturerCode'], 'customer_name') : Yii::$app->general->getforeignkey($model->fromStoreLocCode, 'store_location_name');
        }, 'filter' => false],
    ['attribute' => 'to_type', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->toStoreLocCode, ['storeLocType'], 'slt_name');
        }, 'filter' => false],
    ['attribute' => 'to_dest', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toStoreLocCode, 'store_location_name');
        }, 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false,],
];
$grid_option = [
    'id' => 'transaction-detail',
    'attributes' => $attribute,
    'active_column' => false,
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
