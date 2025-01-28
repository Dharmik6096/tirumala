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
    'financial_year_code',
        ['attribute' => 'ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->ledgerCode, 'ledger_name');
        }],
        ['attribute' => 'credit_debit',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('credit_debit', $searchModel, 'credit_debit'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('credit_debit')['data'];
            return isset($data[$model->credit_debit]) ? $data[$model->credit_debit] : '';
        }],
    'balance',
];

$grid_option = [
    'id' => 'ledger-opening-balance-list',
    'attributes' => $attribute,
    'active_column' => false
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
