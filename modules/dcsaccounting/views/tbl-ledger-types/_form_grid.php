<?php

use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false],
        ['attribute' => 'ledger_type_code'],
        ['attribute' => 'ledger_type_name'],
        ['attribute' => 'local_name'],
        ['attribute' => 'balance_sheet',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'balance_sheet'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('boolean_value')['data'];
            return isset($data[$model->balance_sheet]) ? $data[$model->balance_sheet] : '';
        }],
        ['attribute' => 'profit_loss',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'profit_loss'),
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('boolean_value')['data'];
            return isset($data[$model->profit_loss]) ? $data[$model->profit_loss] : '';
        }],
];

$grid_option = [
    'id' => 'ledger-types-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'edit' => function ($url, $model) {
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', Url::to(['tbl-ledger-types/update', 'id' => $model->ledger_type_code]), ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit']);
        },
        'delete' => ['option' => 'ledger_type_name,ledger_type_code,/dcsaccounting/tbl-ledger-types/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
