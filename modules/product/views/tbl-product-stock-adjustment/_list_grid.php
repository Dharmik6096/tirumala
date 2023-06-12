<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
$visible = $batchNoWiseInventory == 1 ? TRUE : FALSE;
$reason = $model->adjustment_type == 'Good Issue' ? TRUE : FALSE;
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">
    <?php
    $attribute = [
            ['attribute' => 'union_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
            }, 'visible' => true,],
            ['attribute' => 'product_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
            }, 'visible' => true,],
            ['attribute' => 'sap_batch_no', 'filter' => true, 'visible' => $visible],
            ['attribute' => 'adjustment_type', 'filter' => false, 'visible' => true],
            ['attribute' => 'stock', 'filter' => true, 'visible' => true],
            ['attribute' => 'qty', 'filter' => true, 'visible' => true],
            [
            'attribute' => 'transaction_date',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->transaction_date);
            }
        ],
        ['attribute' => 'reason', 'filter' => true, 'visible' => $reason],
        ['attribute' => 'remarks', 'visible' => true],
    ];

    $grid_option = [
        'id' => 'sale-rate-history-grid',
        'attributes' => $attribute,
        'active_column' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>