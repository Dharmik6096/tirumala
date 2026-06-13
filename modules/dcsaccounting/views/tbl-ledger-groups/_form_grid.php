<?php

use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false],
        ['attribute' => 'ledger_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->ledgerTypeCode, 'ledger_type_name');
        },],
        ['attribute' => 'ledger_group_code', 'visible' => false, 'filter' => false],
        ['attribute' => 'ledger_group_name'],
        ['attribute' => 'local_name'],
        ['attribute' => 'ref_code'],
        ['attribute' => 'is_cash'],
];

$grid_option = [
    'id' => 'ledger-groups-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'edit' => function ($url, $model) {
            return GhostHtml::a('<i class="fa fa-pencil"></i>', Url::to(['tbl-ledger-groups/update', 'id' => $model->ledger_group_code]), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit']);
        },
        'delete' => ['option' => 'ledger_group_name,ledger_group_code,/dcsaccounting/tbl-ledger-groups/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
