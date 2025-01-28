<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false],
        ['attribute' => 'ledger_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->ledgerTypeCode, 'ledger_type_name');
        },],
        ['attribute' => 'ledger_group_code'],
        ['attribute' => 'ledger_group_name'],
        ['attribute' => 'local_name'],
];

$grid_option = [
    'id' => 'ledger-groups-list',
    'attributes' => $attribute,
    'active_column' => true,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
