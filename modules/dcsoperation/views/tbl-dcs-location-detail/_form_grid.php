<?php

use kartik\grid\GridView;

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
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => FALSE],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC') . ' ' . Yii::t('app', 'Ref Code'), 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => FALSE],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . ' ' . Yii::t('app', 'Ref. Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'date_time_of_location',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => ['pluginOptions' => ['format' => 'dd-mm-yyyy', 'autoclose' => true]
        ], 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->date_time_of_location, 'php:d-m-Y H:i:s');
        },],
        ['attribute' => 'x_col3', 'filter' => TRUE],
        ['attribute' => 'received_timestamp',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => ['pluginOptions' => ['format' => 'dd-mm-yyyy', 'autoclose' => true]
        ], 'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->received_timestamp, 'php:d-m-Y H:i:s');
        }, 'visible' => FALSE],
        ['attribute' => 'latitude', 'visible' => FALSE, 'filter' => TRUE],
        ['attribute' => 'longitude', 'visible' => FALSE, 'filter' => TRUE],
];

$grid_option = [
    'id' => 'dcs-location-detail-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
