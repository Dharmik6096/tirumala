<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblMemberPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-member-payment-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>   
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
    </div>
    <div class="col-sm-3">
        <?php // Yii::$app->dropdown->unionpaymentcycle($model, $form, 'tblmemberpayment-union_code', 'dcs_payment_cycle_code'); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
