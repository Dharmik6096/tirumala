<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'sub_ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->subLedgerCode, 'sub_ledger_name');
        }],
        ['attribute' => 'credit_debit',
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('credit_debit')['data'];
            return isset($data[$model->credit_debit]) ? $data[$model->credit_debit] : '';
        }],
    'amount',
    'narration',
];

$grid_option = [
    'id' => 'voucher-sub-ledger-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
