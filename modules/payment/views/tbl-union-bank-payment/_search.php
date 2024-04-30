<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblUnionBankPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-union-bank-payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'union_bank_payment_code') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'bank_name') ?>

    <?= $form->field($model, 'bank_code') ?>

    <?= $form->field($model, 'branch_name') ?>

    <?php // echo $form->field($model, 'branch_code') ?>

    <?php // echo $form->field($model, 'ifsc') ?>

    <?php // echo $form->field($model, 'bank_account_no') ?>

    <?php // echo $form->field($model, 'file_path') ?>

    <?php // echo $form->field($model, 'server_type') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'mobile_no') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'ftp_type') ?>

    <?php // echo $form->field($model, 'ftp_server') ?>

    <?php // echo $form->field($model, 'ftp_username') ?>

    <?php // echo $form->field($model, 'ftp_password') ?>

    <?php // echo $form->field($model, 'ftp_port') ?>

    <?php // echo $form->field($model, 'reverse_ftp_path') ?>

    <?php // echo $form->field($model, 'reverse_server_path') ?>

    <?php // echo $form->field($model, 'compare_file_name') ?>

    <?php // echo $form->field($model, 'bank_email') ?>

    <?php // echo $form->field($model, 'bank_mobile') ?>

    <?php // echo $form->field($model, 'corporate_code') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
