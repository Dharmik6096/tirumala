<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name'],
    ['attribute' => 'member_classification_code',],
    ['attribute' => 'member_classification_name', 'value' => 'member_classification_name',],
    ['attribute' => 'range_from'],
    ['attribute' => 'range_to'],
    ['attribute' => 'member_classification_type', 'value' => 'member_classification_type'],
    ['attribute' => 'local_name'],
];

$grid_option = [
    'id' => 'member-classification-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
        'delete' => ['option' => 'member_classification_name,member_classification_code,tbl-member-classification/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>