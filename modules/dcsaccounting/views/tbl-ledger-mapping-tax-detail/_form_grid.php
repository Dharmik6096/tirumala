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
        ['attribute' => 'purchase_ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->purchaseLedgerCode, 'ledger_name');
        }],
        ['attribute' => 'sale_ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->saleLedgerCode, 'ledger_name');
        }],
        ['attribute' => 'tax_detail_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->taxDetailCode, ['taxCode'], 'tax_name');
        }],
        ['attribute' => 'tax_detail_code',
        'label' => Yii::t('app', 'Tax Detail'),
        'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->taxDetailCode, ['basicTaxCode'], 'basic_tax_name') . '(' . Yii::$app->general->getforeignkey($model->taxDetailCode, 'percentage') . '%)';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'ledger-mapping-tax-details-list',
    'attributes' => $attribute,
    'active_column' => FALSE
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
