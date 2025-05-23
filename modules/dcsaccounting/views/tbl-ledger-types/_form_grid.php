<?php

use kartik\grid\GridView;
?>

<?php

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
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
