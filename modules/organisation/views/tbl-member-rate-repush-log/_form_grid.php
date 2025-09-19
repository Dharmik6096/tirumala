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
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => FALSE],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS') . Yii::t('app', ' Ref. Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'file_type', 'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('process_type')['data'][$model->file_type]) ? Yii::$app->dropdown->getRecords('process_type')['data'][$model->file_type] : 'N/A';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('process_type', $searchModel, 'file_type'),],
        ['attribute' => 'dpu_type', 'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('dpu_type')['data'][$model->dpu_type]) ? Yii::$app->dropdown->getRecords('dpu_type')['data'][$model->dpu_type] : 'N/A';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('dpu_type', $searchModel, 'dpu_type'),],
        ['attribute' => 'log_status', 'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('log_status')['data'][$model->log_status]) ? Yii::$app->dropdown->getRecords('log_status')['data'][$model->log_status] : 'N/A';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('log_status', $searchModel, 'log_status'),],
        ['attribute' => 'purchase_rate_code', 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'member-rate-repush-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
