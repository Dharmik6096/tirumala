<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->ledgerCode, 'ledger_name');
        }, 'filter' => false],
        ['attribute' => 'sub_ledger_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->subLedgerCode, 'sub_ledger_name');
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'ledger-sub-ledgers-mapping-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
