<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
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
        ['attribute' => 'ledger_group_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->ledgerGroupCode, 'ledger_group_name');
        },],
        ['attribute' => 'ledger_code', 'visible' => FALSE],
        ['attribute' => 'ledger_name'],
        ['attribute' => 'local_name'],
        ['attribute' => 'ref_code'],
        ['attribute' => 'has_sub_ledger',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'has_sub_ledger'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('boolean_value')['data'];
            return isset($data[$model->has_sub_ledger]) ? $data[$model->has_sub_ledger] : '';
        }],
];

$grid_option = [
    'id' => 'ledger-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'edit' => function ($url, $model) {
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', Url::to(['tbl-ledgers/update', 'id' => $model->ledger_code]), ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit']);
        },
        'delete' => ['option' => 'ledger_name,ledger_code,/dcsaccounting/tbl-ledgers/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
