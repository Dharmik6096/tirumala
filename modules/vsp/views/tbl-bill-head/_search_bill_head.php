<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblVspPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
$depend = 'tblbillhead';
?>

<div class="tbl-vsp-payment-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>   

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, $depend . '-union_code', 'plant_code', 'Plant'); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, $depend . '-plant_code', 'mcc_plant_code', Yii::t('app', 'MCC')); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, $depend . '-mcc_plant_code', 'bmc_code', 'BMC'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('bill_head_for', $model, $form, 'form-group', $model->getAttributeLabel('bill_head_for'), false, 'bill_head_for', false); ?> 
    </div>
    <div class="col-sm-2 customertype">
        <?= Yii::$app->dropdown->customer_type($model, $form, $depend . '-bmc_code,tblbillhead-bill_head_for', 'customer_type', 'Customer Type'); ?>
    </div>
    <div class="col-sm-2">

        <?= Yii::$app->dropdown->paymentCycle($model, $form, $depend . '-union_code,' . $depend . '-bmc_code,' . $depend . '-customer_type,applicable_for,data_lock_bmc', 'payment_cycle_code', 'Payment Cycle'); ?>
    </div>
    <div class="form-group padding_top_20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $where = json_encode(['data_lock_bmc' => 1, 'billing_lock_bmc' => 0]);
    echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
    echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
    ?>
</div>

