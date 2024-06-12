<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
$attributes = [
    ['attribute' => 'code','filter' => false],
    ['attribute' => 'name','filter' => false],
    ['attribute' => 'type','filter' => false],
    [
        'attribute' => 'payment_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->payment_date);
        },'filter' => false],
    ['attribute' => 'kg_fat','filter' => false],
    ['attribute' => 'kg_snf','filter' => false],
    ['attribute' => 'qty','filter' => false],
    ['attribute' => 'avg_fat','filter' => false],
    ['attribute' => 'avg_snf','filter' => false],
    ['attribute' => 'avg_rate','filter' => false],
    ['attribute' => 'total_amount','filter' => false],
    ['attribute' => 'total_deduction','filter' => false],
    ['attribute' => 'final_amount','filter' => false],
    ['attribute' => 'member_count','filter' => false],
    ['attribute' => 'type','filter' => false],
    ['attribute' => 'bank_name','filter' => false],
    ['attribute' => 'bank_code','filter' => false],
    ['attribute' => 'branch_name','filter' => false],    
    ['attribute' => 'branch_code','filter' => false],    
    ['attribute' => 'ifsc','filter' => false],    
    ['attribute' => 'bank_account_no','filter' => false],
    ['attribute' => 'mobile_no','filter' => false],
];

$grid_option = [
    'id' => 'payment-transaction-export-grid',
    'attributes' => $attributes,
    'active_column' => false,
    'default_sorting' => FALSE,
];

Yii::$app->grid->bind($dataProvider, $transaction, $grid_option, '', false);
?>

