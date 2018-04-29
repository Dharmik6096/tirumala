<?php

use yii\helpers\Html;
?>

<div class="grid-search">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    'sale_detail_code',
        'product_sale_code',
        'product_code',
        'rate_app_code',
        'rate',
        // 'qty',
        // 'amount',
        // 'created_at',
        // 'created_by',
        // 'updated_at',
        // 'updated_by',
];

$grid_option = [
    'id' => 'product-sale-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
        'delete' => ['option' => ''],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>