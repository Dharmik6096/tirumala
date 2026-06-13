<?php

use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;

$grid_id = isset($grid_id) ? $grid_id : 'debit-voucher-transaction-list';
$label = (isset($grid_id) && $grid_id == 'credit-voucher-transaction-list') ? 'Credit' : 'Debit';
?>

<?php

$attribute = [
        ['attribute' => 'ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->ledgerCode, 'ledger_name');
        }, 'filter' => false],
        ['attribute' => 'amount', 'filter' => false],
        ['attribute' => 'credit_debit', 'label' => $label,
        'value' => function ($model) {
            $data = Yii::$app->dropdown->getRecords('credit_debit')['data'];
            return isset($data[$model->credit_debit]) ? $data[$model->credit_debit] : '';
        }, 'filter' => false],
        ['attribute' => 'narration', 'filter' => false],
        ['attribute' => 'auto_posted_screen', 'format' => 'boolean', 'filter' => false],
];

$grid_option = [
    'id' => $grid_id,
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view-sub-ledger' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'class' => 'view-sub-ledger', 'data-original-title' => 'View Sub Ledger', 'data-val' => $model->voucher_transaction_code];
            return GhostHtml::a_alert('<i class="fa fa-link"></i>', ['/dcsaccounting/tbl-voucher/view-sub-ledger', 'id' => $model->voucher_transaction_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
