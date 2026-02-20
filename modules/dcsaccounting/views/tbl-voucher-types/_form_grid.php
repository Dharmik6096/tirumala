<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'voucher_type_code', 'visible' => false, 'filter' => false],
        ['attribute' => 'voucher_type_name'],
        ['attribute' => 'local_name', 'filter' => false],
        ['attribute' => 'ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->ledgerCode, 'ledger_name');
        }],
        ['attribute' => 'voucher_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('payment_mode_member', $searchModel, 'voucher_type'),
        'value' => function($model) {
            return isset($model->voucher_type) ? Yii::$app->dropdown->getRecords('payment_mode_member')['data'][$model->voucher_type] : '';
        }],
        ['attribute' => 'credit_debit',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('credit_debit', $searchModel, 'credit_debit'),
        'value' => function($model) {
            return isset($model->voucher_type) ? Yii::$app->dropdown->getRecords('credit_debit')['data'][$model->credit_debit] : '';
        }],
];

$grid_option = [
    'id' => 'voucher-types-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'edit' => function ($url, $model) {
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', Url::to(['tbl-voucher-types/update', 'id' => $model->voucher_type_code]), ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit']);
        },
        'delete' => ['option' => 'voucher_type_name,voucher_type_code,tbl-voucher-types/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
