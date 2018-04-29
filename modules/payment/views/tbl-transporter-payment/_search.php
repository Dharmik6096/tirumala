<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblTransporterPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-transporter-payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['payment-disburse'],
        'method' => 'get',
    ]); ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
    </div>
    
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->transporterpaymentcycle($model, $form, 'tbltransporterpayment-union_code', 'transporter_payment_cycle'); ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
