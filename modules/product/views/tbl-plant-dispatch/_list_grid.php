<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
$visible = $batchNoWiseInventory == 1 ? TRUE : FALSE;
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">
    <h5 class="panel-heading"><?= Yii::t('app', 'Product Details') ?></h5>


    <?php
    $attribute = [
        ['attribute' => 'product_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
            }, 'visible' => true, 'filter' => false],
        ['attribute' => 'sap_batch_no','visible'=>$visible, 'filter' => FALSE],
        ['attribute' => 'unit_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unitCode, 'unit_name');
            }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'rate', 'filter' => false],
        ['attribute' => 'qty', 'filter' => false],
        ['attribute' => 'amount', 'filter' => FALSE],
        ['attribute' => 'lr_no', 'filter' => FALSE],
        ['attribute' => 'po_itemno', 'filter' => FALSE],
        ['attribute' => 'product_mrp', 'filter' => FALSE],
        ['attribute' => 'distributor_landing_rate', 'filter' => FALSE],
        ['attribute' => 'sachiv_price', 'filter' => FALSE],
        ['attribute' => 'member_price', 'filter' => FALSE],
    ];


    $grid_option = [
        'id' => 'plant-dispatch-txn-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>