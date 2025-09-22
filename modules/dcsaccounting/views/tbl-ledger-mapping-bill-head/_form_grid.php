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
        ['attribute' => 'ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->ledgerCode, 'ledger_name');
        }],
        ['attribute' => 'bill_head_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->billHeadCode, 'bill_head_name');
        }],
        ['attribute' => 'bill_criteria_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->billCriteriaCode, 'criteria');
        }],
        ['attribute' => 'type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('type', $searchModel, 'type'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('type')['data'];
            return !empty($data[$model->type]) ? $data[$model->type] : '';
        }],
        ['attribute' => 'has_sub_ledger',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'has_sub_ledger'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('boolean_value')['data'];
            return !empty($data[$model->has_sub_ledger]) ? $data[$model->has_sub_ledger] : '';
        }],
        ['attribute' => 'credit_debit',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('credit_debit', $searchModel, 'credit_debit'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('credit_debit')['data'];
            return !empty($data[$model->credit_debit]) ? $data[$model->credit_debit] : '';
        }],
];

$grid_option = [
    'id' => 'ledger-mapping-bill-head',
    'attributes' => $attribute,
    'active_column' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
