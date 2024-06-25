<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

?>
<?php
$attributes = [
    ['attribute' => 'union_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->plantCode, 'name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
    }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code',
    'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
    },
    'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
    ['attribute' => 'bmc_code',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'payment_cycle', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date);
        }, 'filter' => false, 'format' => 'raw'],
    ['attribute' => 'customer_type'],
    [
        'attribute' => 'payment_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->payment_date);
        }],
    ['attribute' => 'kg_fat',],
    ['attribute' => 'kg_snf',],
    ['attribute' => 'qty',],
    ['attribute' => 'avg_fat', 'visible' => false],
    ['attribute' => 'avg_snf', 'visible' => false],
    ['attribute' => 'avg_rate', 'visible' => false],
    ['attribute' => 'total_amount',],
    ['attribute' => 'total_deduction',],
    ['attribute' => 'final_amount',],
    ['attribute' => 'total_count',],
    ['attribute' => 'approval_status',],
    ['attribute' => 'remarks',],
];

$grid_option = [
    'id' => 'member-payment-export-grid',
    'attributes' => $attributes,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'detail-view' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => Yii::t('app', 'View'), 'class' => ''];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/payment/tbl-payment-transaction-approval/view', 'payment_transaction_approval_code' => $model->payment_transaction_approval_code, 'bmc_code' => $model->bmc_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

