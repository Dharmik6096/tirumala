<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblVspPayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-vsp-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'society_code')->textInput() ?>

    <?= $form->field($model, 'prev_due')->textInput() ?>

    <?= $form->field($model, 'current_amount')->textInput() ?>

    <?= $form->field($model, 'adjust_amount')->textInput() ?>

    <?= $form->field($model, 'net_payable')->textInput() ?>

    <?= $form->field($model, 'adjust_remark')->textInput() ?>

    <?= $form->field($model, 'payment_cycle_applicabilty_code')->textInput() ?>

    <?= $form->field($model, 'payment_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
