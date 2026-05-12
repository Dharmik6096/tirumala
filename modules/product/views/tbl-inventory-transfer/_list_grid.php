<?php

use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;

$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
$visible = $batchNoWiseInventory == 1 ? TRUE : FALSE;
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">


    <?php
    $attribute = [
        ['attribute' => 'product_code', 'label' => 'Product', 'filter' => false,
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
            }],
        ['attribute' => 'sap_batch_no', 'visible' => $visible, 'filter' => false],
        ['attribute' => 'unit_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unitCode, 'unit_name');
            }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'available_stock', 'visible' => true, 'filter' => false],
        ['attribute' => 'qty', 'filter' => false],
        ['attribute' => 'is_stock_posted', 'value' => function($model) {
                return Yii::$app->general->getStaticDropdownVal('boolean_value', $model, 'is_stock_posted');
            }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'data_post_status',
                'value' => function($model) {
                    return isset(Yii::$app->dropdown->getRecords('data_post_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('data_post_status')['data'][$model->data_post_status] : 'Pending';
                }, 'filter' => false, 'visible' => false],
        ['attribute' => 'response_msg', 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'picked_datetime',
                'value' => function($model) {
                    return Yii::$app->controls->view_datetime($model->picked_datetime, 'php:d-m-Y H:i:s');
                }, 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'response_datetime',
                'value' => function($model) {
                    return Yii::$app->controls->view_datetime($model->response_datetime, 'php:d-m-Y H:i:s');
                }, 'filter' => FALSE, 'visible' => false],
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