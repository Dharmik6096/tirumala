<?php

$attribute = [
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'Parent Code'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, TRUE, FALSE);
        }, 'filter' => false],
        ['attribute' => 'bmc_name', 'label' => Yii::t('app', 'Parent Name'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, FALSE, FALSE);
        }, 'filter' => false],
        ['attribute' => 'applicable_code', 'filter' => false],
        ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->applicable_for, false, FALSE, TRUE);
        }, 'filter' => false],
        ['attribute' => 'code_ex', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
        }, 'filter' => false],
        ['attribute' => 'mcc_name', 'label' => Yii::t('app', 'Applicable Name'), 'value' => function($model) {
            if ($model->applicable_for == 'PLANT') {
                return Yii::$app->general->getforeignkey($model->plantCode, 'name');
            } else if ($model->applicable_for == 'MCC') {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            } else if ($model->applicable_for == 'BMC') {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            } else if ($model->applicable_for == 'DCS') {
                return Yii::$app->general->getforeignkey($model->dcsName, 'dcs_name');
            } else {
                return Yii::$app->general->getforeignkey($model->customerMasterCode, 'customer_name');
            }
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'scheme-rate-applicability-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['index', 'dcs' => Yii::$app->request->get('dcs'), 'dcsname' => Yii::$app->request->get('dcsname'), 'subcenter' => Yii::$app->request->get('subcenter'), 'subname' => Yii::$app->request->get('subname'), 'type' => Yii::$app->request->get('type')]);
?>