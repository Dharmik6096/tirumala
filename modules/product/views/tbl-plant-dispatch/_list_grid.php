<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">
    <h5 class="panel-heading"><?= Yii::t('app', 'Product Details') ?></h5>


    <?php
    $attribute = [
        ['attribute' => 'product_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
            }, 'visible' => true, 'filter' => false],
        ['attribute' => 'sap_batch_no', 'filter' => FALSE],
        ['attribute' => 'unit_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unitCode, 'unit_name');
            }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'rate', 'filter' => false],
        ['attribute' => 'qty', 'filter' => false],
        ['attribute' => 'amount', 'filter' => FALSE],
    ];


    $grid_option = [
        'id' => 'plant-dispatch-txn-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
//        'default_sorting' => FALSE
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>