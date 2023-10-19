<?php

use kartik\grid\GridView;
?>

<?php
    $attribute = [
//        ['attribute' => 'union_code'],
        ['attribute' => 'party_master_code', 'label' => Yii::t('app', 'Code')],
        ['attribute' => 'party_master_code', 'label' => Yii::t('app', 'Party'), 'value' => function($model){
//                echo "<pre>";
//                print_r($model);
//                die;
            return Yii::$app->general->getforeignkey($model->partyMaster, 'party_name');
        }],
        ['attribute' => 'payment_type'],
        ['attribute' => 'from_date', 'label' => Yii::t('app', 'Period'),
            'value' => function ($model) {
                return Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date);
            }, 'filter' => false],
//            ['attribute' => 'payment_count'],
        ['attribute' => 'disp_kg_fat'],
        ['attribute' => 'disp_kg_snf'],
        ['attribute' => 'disp_qty'],
        ['attribute' => 'rec_kg_fat'],
        ['attribute' => 'rec_kg_snf'],
        ['attribute' => 'rec_qty'],
        ['attribute' => 'rd_kg_fat_diff'],
        ['attribute' => 'rd_kg_snf_diff'],
        ['attribute' => 'rd_qty_diff'],
        ['attribute' => 'no_of_days'],
        ['attribute' => 'total_qty'],
        ['attribute' => 'avg_fat'],
        ['attribute' => 'avg_snf'],
        ['attribute' => 'avg_rate'],
        ['attribute' => 'total_amount'],
        ['attribute' => 'total_addition'],
        ['attribute' => 'total_deduction'],
        ['attribute' => 'final_amount'],
        ['attribute' => 'net_amount'],
        ['attribute' => 'adjust_remark'],
        ['attribute' => 'bank_name'],
        ['attribute' => 'bank_code'],
        ['attribute' => 'branch_name'],
        ['attribute' => 'branch_code'],
        ['attribute' => 'ifsc'],
        ['attribute' => 'bank_account_no'],
        ['attribute' => 'beneficiary_name'],
        ['attribute' => 'status'],
    ];
$grid_option = [
    'id' => 'party-payment-list-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE,
    'actions' => [
        'view' => TRUE
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>