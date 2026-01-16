<?php

use kartik\grid\GridView;
?>

<?php

$attribute = [
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
        'label' => Yii::t('app', 'BMC Code'),
        'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'bmc_name',
        'label' => Yii::t('app', 'BMC Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        },],
        ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
        ['attribute' => 'customer_ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
        }],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->customer_type);
        }],
        ['attribute' => 'payment_cycle_code', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_datetime) . ' to ' . Yii::$app->controls->view_date($model->to_datetime);
        }, 'filter' => false, 'format' => 'raw'],
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
        ['attribute' => 'kg_fat'],
        ['attribute' => 'kg_snf'],
        ['attribute' => 'total_qty'],
        ['attribute' => 'amount'],
        ['attribute' => 'addition'],
        ['attribute' => 'deduction'],
        ['attribute' => 'previous_hold'],
        ['attribute' => 'previous_due'],
        ['attribute' => 'hold_amount'],
        ['attribute' => 'adjust_amount'],
        ['attribute' => 'final_pay'],
        ['attribute' => 'adjust_remark'],
        ['attribute' => 'status', 'value' => function($model) {
            return $model->status == 'sent' ? 'disbursed' : $model->status;
        }],
        ['attribute' => 'bank_name', 'visible' => false],
        ['attribute' => 'branch_name', 'visible' => false],
        ['attribute' => 'ifsc', 'visible' => false],
        ['attribute' => 'bank_account_no', 'visible' => false],
];


$grid_option = [
    'id' => 'vsp-payment-list-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'view' => TRUE
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
        