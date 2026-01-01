<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['label' => Yii::t('app', 'Plant Code'), 'attribute' => 'plant_code', 'filter' => false, 'visible' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['label' => Yii::t('app', 'MCC Code'), 'attribute' => 'mcc_plant_code', 'filter' => false, 'visible' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['label' => Yii::t('app', 'BMC Code'), 'attribute' => 'bmc_code', 'filter' => false],
        ['label' => Yii::t('app', 'BMC') . ' Ref. Code', 'attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => true, 'filter' => false],
        ['label' => Yii::t('app', 'DCS Code'), 'attribute' => 'dcs_code', 'filter' => false],
        ['label' => Yii::t('app', 'DCS') . ' Ref. Code', 'attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'from_date', 'filter' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
        ['attribute' => 'from_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShift, 'shift');
        }, 'filter' => FALSE],
        ['attribute' => 'to_date', 'filter' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
        ['attribute' => 'to_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShift, 'shift');
        }, 'filter' => FALSE],
        ['attribute' => 'status', 'value' => function ($model) {
                return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->status] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'status')],
];

$grid_option = [
    'id' => 'trasfer-data-update-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>