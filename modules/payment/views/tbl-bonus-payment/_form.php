<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblBonusPayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-bonus-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bonus_payment_summary_code')->textInput() ?>

    <?= $form->field($model, 'customer_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kg_fat')->textInput() ?>

    <?= $form->field($model, 'kg_snf')->textInput() ?>

    <?= $form->field($model, 'avg_fat')->textInput() ?>

    <?= $form->field($model, 'avg_snf')->textInput() ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'addition')->textInput() ?>

    <?= $form->field($model, 'deduction')->textInput() ?>

    <?= $form->field($model, 'net_payable')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disburse_amount')->textInput() ?>

    <?= $form->field($model, 'disburse_date')->textInput() ?>

    <?= $form->field($model, 'payment_date')->textInput() ?>

    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ifsc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_account_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'beneficiary_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_verified')->textInput() ?>

    <?= $form->field($model, 'utr_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reference_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'process_date')->textInput() ?>

    <?= $form->field($model, 'reject_reason')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_transaction_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
