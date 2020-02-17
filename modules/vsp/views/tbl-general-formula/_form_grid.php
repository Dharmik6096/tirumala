<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'formula_name'],
    ['attribute' => 'formula'],
];

$grid_option = [
    'id' => 'general-formula-master-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => FALSE,
        'update' => TRUE,
        'delete' => ['option' => 'formula_name,general_formula_code,tbl-general-formula/delete']
        ],
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>