<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblVspPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
$multiple = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'allow_multiselect_in_payment', 'PORTAL') == '1' ? TRUE : FALSE;
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
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvendorpaymentholdrelease-union_code', 'plant_code', 'Plant'); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvendorpaymentholdrelease-plant_code', 'mcc_plant_code', 'MCC', $multiple, '', false, false, '', false); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvendorpaymentholdrelease-mcc_plant_code', 'bmc_code', 'BMC', $multiple, '', '', false, false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->customer_type($model, $form, 'tblvendorpaymentholdrelease-bmc_code', 'customer_type', 'Customer Type'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->HoldReleasePaymentCycle($model, $form, 'tblvendorpaymentholdrelease-union_code,tblvendorpaymentholdrelease-bmc_code', 'payment_cycle_code', 'Payment Cycle'); ?>
    </div>
    <div class="form-group padding_top_20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
