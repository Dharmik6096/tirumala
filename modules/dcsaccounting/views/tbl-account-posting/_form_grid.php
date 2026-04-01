<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
        ['label' => Yii::t('app', 'DCS Ref. Code'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
        ['attribute' => 'from_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }, 'filter' => FALSE],
        ['attribute' => 'from_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShift, 'shift');
        }, 'filter' => FALSE],
        ['attribute' => 'to_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'filter' => FALSE],
        ['attribute' => 'to_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShift, 'shift');
        }, 'filter' => FALSE],
        ['attribute' => 'posting_type', 'value' => function($model) {
            return isset($model->posting_type) ? Yii::$app->dropdown->getRecords('account_posting_type')['data'][$model->posting_type] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('account_posting_type', $searchModel, 'posting_type')],
        ['attribute' => 'status', 'value' => function($model) {
            return isset($model->status) ? Yii::$app->dropdown->getRecords('account_status')['data'][$model->status] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('account_status', $searchModel, 'status')],
        ['attribute' => 'event_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->eventCode, 'event_name');
        }],
];

$grid_option = [
    'id' => 'account-posting',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
