<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

?>

<div class="grid-search">
    <?php
    if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions')))>1)
        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>

<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false],
    ['attribute' => 'product_group_code',
     'value' => 'productGroupCode.product_group_name'],
    'product_name',
    'description',
//    'local_name',
    ['attribute' => 'local_name', 'filter' => false],
];

$grid_option = [
    'id' => 'product-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
        'delete' => ['option' => 'product_name,product_code,tbl-product/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
