<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
        ['attribute' => 'dcs_code', 'filter' => false],
        ['label' => Yii::t('app', 'Ref. Code'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Society Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
    'bill_head_name',
    'local_name',
        ['attribute' => 'is_default_head',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_default_head'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('boolean_value')['data'];
            return !empty($data[$model->is_default_head]) ? $data[$model->is_default_head] : '';
        }],
        ['attribute' => 'is_disburse_allowed',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_disburse_allowed'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('boolean_value')['data'];
            return !empty($data[$model->is_disburse_allowed]) ? $data[$model->is_disburse_allowed] : '';
        }],
        ['attribute' => 'head_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('type', $searchModel, 'head_type'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('type')['data'];
            return !empty($data[$model->head_type]) ? $data[$model->head_type] : '';
        }],
        ['attribute' => 'allow_adjustment',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'allow_adjustment'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('boolean_value')['data'];
            return !empty($data[$model->allow_adjustment]) ? $data[$model->allow_adjustment] : '';
        }],
];

$grid_option = [
    'id' => 'member-bill-head-list',
    'attributes' => $attribute,
    'active_column' => true
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
