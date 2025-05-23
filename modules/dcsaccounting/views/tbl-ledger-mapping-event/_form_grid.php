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
    'event_code_default',
        ['attribute' => 'event_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->eventCode, 'event_name');
        }],
        ['attribute' => 'credit_ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->creditLedgerCode, 'ledger_name');
        }],
        ['attribute' => 'debit_ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->debitLedgerCode, 'ledger_name');
        }],
        ['attribute' => 'credit_sub_ledger',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'credit_sub_ledger'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('boolean_value')['data'];
            return isset($data[$model->credit_sub_ledger]) ? $data[$model->credit_sub_ledger] : '';
        }],
        ['attribute' => 'debit_sub_ledger',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'debit_sub_ledger'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('boolean_value')['data'];
            return isset($data[$model->debit_sub_ledger]) ? $data[$model->debit_sub_ledger] : '';
        }],
        ['attribute' => 'voucher_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->voucherTypeCode, 'voucher_type_name');
        }],
];

$grid_option = [
    'id' => 'ledger-mapping-event-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
