<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblVspPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
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
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvsppayment-union_code', 'plant_code','Plant'); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvsppayment-plant_code', 'mcc_plant_code','MCC'); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvsppayment-mcc_plant_code', 'bmc_code','BMC'); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->RemunerationPaymentCycle($model, $form, 'tblvsppayment-union_code,tblvsppayment-bmc_code', 'payment_cycle_code','Payment Cycle'); ?>
    </div>

    <div class="form-group padding_top_20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
