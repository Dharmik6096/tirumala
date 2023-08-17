<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblBonusPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-bonus-payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bonus_payment_code') ?>

    <?= $form->field($model, 'bonus_payment_summary_code') ?>

    <?= $form->field($model, 'customer_type') ?>

    <?= $form->field($model, 'customer_code') ?>

    <?= $form->field($model, 'customer_name') ?>

    <?php // echo $form->field($model, 'kg_fat') ?>

    <?php // echo $form->field($model, 'kg_snf') ?>

    <?php // echo $form->field($model, 'avg_fat') ?>

    <?php // echo $form->field($model, 'avg_snf') ?>

    <?php // echo $form->field($model, 'qty') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'addition') ?>

    <?php // echo $form->field($model, 'deduction') ?>

    <?php // echo $form->field($model, 'net_payable') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'disburse_amount') ?>

    <?php // echo $form->field($model, 'disburse_date') ?>

    <?php // echo $form->field($model, 'payment_date') ?>

    <?php // echo $form->field($model, 'bank_name') ?>

    <?php // echo $form->field($model, 'bank_code') ?>

    <?php // echo $form->field($model, 'branch_name') ?>

    <?php // echo $form->field($model, 'branch_code') ?>

    <?php // echo $form->field($model, 'ifsc') ?>

    <?php // echo $form->field($model, 'bank_account_no') ?>

    <?php // echo $form->field($model, 'beneficiary_name') ?>

    <?php // echo $form->field($model, 'is_verified') ?>

    <?php // echo $form->field($model, 'utr_no') ?>

    <?php // echo $form->field($model, 'reference_no') ?>

    <?php // echo $form->field($model, 'process_date') ?>

    <?php // echo $form->field($model, 'reject_reason') ?>

    <?php // echo $form->field($model, 'bank_status') ?>

    <?php // echo $form->field($model, 'payment_transaction_code') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
