<?php

use yii\helpers\Html;

?>
<?php

$attribute = [
    ['attribute' => 'department_id', 'filter' => FALSE],
    ['attribute' => 'department'],
    ['attribute' => 'local_name', 'filter' => FALSE],
];

$grid_option = [
    'id' => 'department-master-list',
    'attributes' => $attribute,
    'active_column' => TRUE,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
