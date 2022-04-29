<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => TRUE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => TRUE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => TRUE],
    ['label' => Yii::t('app', 'BMC Code'), 'visible' => TRUE, 'attribute' => 'bmc_code', 'filter' => FALSE],
    ['label' => Yii::t('app', 'BMC Code Ex'), 'attribute' => 'bmc_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_code_ex');
        }, 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['label' => 'Date', 'attribute' => 'date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date);
        },
        'filter' => TRUE],
    ['attribute' => 'vehicle_type_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->vehicleTypeCode, 'vehicle_type_name');
        }],
    ['attribute' => 'vehicle_no', 'filter' => TRUE],
    ['attribute' => 'type',
        'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('weigh_type', $model, 'type');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('weigh_type', $searchModel, 'type'),],
    ['attribute' => 'location_code', 'filter' => TRUE],
    ['attribute' => 'location_detail', 'filter' => TRUE],
    ['attribute' => 'material_type_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }, 'filter' => false],
    ['attribute' => 'gross_weight', 'filter' => TRUE],
    ['attribute' => 'gross_weight_time', 'filter' => false],
    ['attribute' => 'tare_weight', 'filter' => TRUE],
    ['attribute' => 'tare_weight_time', 'filter' => TRUE],
    ['attribute' => 'weight', 'filter' => TRUE],
];

$grid_option = [
    'id' => 'weigh-bridge-list',
    'attributes' => $attribute,
    'active_column' => false,
//    'actions' => [
//        'view' => TRUE,
//    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
