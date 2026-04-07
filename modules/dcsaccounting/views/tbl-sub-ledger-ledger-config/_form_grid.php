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
        ['attribute' => 'sub_ledger_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('consumer_type', $searchModel, 'sub_ledger_type'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('consumer_type')['data'];
            return !empty($data[$model->sub_ledger_type]) ? $data[$model->sub_ledger_type] : '';
        }],
        ['attribute' => 'ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->ledgerCode, 'ledger_name');
        }],
];

$grid_option = [
    'id' => 'sub-ledger-ledger-config-list',
    'attributes' => $attribute,
    'active_column' => FALSE
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
