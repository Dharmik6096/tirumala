<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblUnionBankPayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'union_bank_payment_code')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'union_code')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'bank_name')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'bank_code')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'branch_name')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'branch_code')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'ifsc')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'bank_account_no')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'account_holder_name')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'file_path')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'server_type')->textInput() ?>

    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'mobile_no')->textInput() ?>
    </div>
     <div class="col-sm-3">
        <?= $form->field($model, 'email')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'bank_email')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'bank_mobile')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'corporate_code')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'integration_mode')->textInput() ?>
    </div><div class="col-sm-3">
        <?= $form->field($model, 'is_active')->textInput() ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
