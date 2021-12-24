<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">


    <?php
    $attribute = [
        ['attribute' => 'product_code', 'label' => 'Product', 'filter' => false,
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
            }],
        ['attribute' => 'unit_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unitCode, 'unit_name');
            }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'available_stock', 'visible' => true, 'filter' => false],
        ['attribute' => 'qty', 'filter' => false],
    ];


    $grid_option = [
        'id' => 'inventory-transfer-txn-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>