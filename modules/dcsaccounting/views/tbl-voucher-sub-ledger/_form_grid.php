<?php

use kartik\grid\GridView;

$credit_debit_label = ($credit_debit_val == 1) ? 'Credit' : 'Debit';
?>

<?php

$attribute = [
        ['attribute' => 'sub_ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->subLedgerCode, 'sub_ledger_name');
        }, 'filter' => false],
        ['attribute' => 'credit_debit', 'label' => $credit_debit_label,
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('credit_debit')['data'];
            return isset($data[$model->credit_debit]) ? $data[$model->credit_debit] : '';
        }, 'filter' => false],
        ['attribute' => 'amount', 'filter' => false],
        ['attribute' => 'narration', 'filter' => false],
];

$grid_option = [
    'id' => 'voucher-sub-ledger-list',
    'attributes' => $attribute,
    'active_column' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
