<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
$visible = $batchNoWiseInventory == 1 ? TRUE : FALSE;
$posting_date_visible = (isset($view) && $view == 1) ? TRUE : FALSE;
?>
<div class="col-sm-12 padding-left-0 padding-right-0 hide-grid-settings ">
    <h5 class="panel-heading"><?= Yii::t('app', 'Product Details') ?></h5>


    <?php
    $attribute = [
        ['attribute' => 'product_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
            }, 'visible' => true, 'filter' => false],
        ['attribute' => 'sap_batch_no', 'filter' => false, 'visible' => $visible],
        ['attribute' => 'unit_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unitCode, 'unit_name');
            }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'rate', 'filter' => false],
        ['attribute' => 'dispatch_qty', 'filter' => false],
        ['attribute' => 'received_qty', 'filter' => false],
        ['attribute' => 'rejected_qty', 'filter' => false],
        ['attribute' => 'missing_qty', 'filter' => false],
        ['attribute' => 'basic_amount', 'filter' => false],
        ['attribute' => 'tax', 'filter' => FALSE],
        ['attribute' => 'gross_amount', 'filter' => FALSE],
        ['attribute' => 'rejection_remarks', 'filter' => FALSE],
        ['attribute' => 'missing_remarks', 'filter' => FALSE],
        ['attribute' => 'manuf_date',
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->manuf_date);
            }],
        ['attribute' => 'posting_date',
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->posting_date);
            }, 'visible' => $posting_date_visible],
        ['attribute' => 'is_stock_posted', 'value' => function($model) {
                return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_stock_posted');
            }, 'visible' => TRUE, 'filter' => false],    
    ];


    $grid_option = [
        'id' => 'grn-txn-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
//        'default_sorting' => FALSE
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>