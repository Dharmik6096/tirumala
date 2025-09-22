<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false,],
        ['attribute' => 'voucher_type_code'],
        ['attribute' => 'voucher_type_name'],
        ['attribute' => 'local_name', 'filter' => false],
];

$grid_option = [
    'id' => 'voucher-types-list',
    'attributes' => $attribute,
    'active_column' => true
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
               
