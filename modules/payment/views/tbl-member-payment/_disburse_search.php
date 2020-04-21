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
                'validateOnBlur' => false,
                'validateOnEnter' => TRUE,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
    ]);
    ?>   
    <div class="col-sm-12 mt10 padding-left-0">
        <div class="col-sm-3" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmemberpaymentalias-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
        </div> 
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmemberpaymentalias-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
        </div>      
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmemberpaymentalias-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
        </div>
        <!--<div class="col-sm-2">-->
        <?php // Yii::$app->dropdown->customer_type($model, $form, 'tblmemberpaymentalias-bmc_code', 'customer_type', TRUE, FALSE); ?>
        <!--</div>-->
        <div class="col-sm-2">
            <?php
            $where = json_encode(['data_lock_member' => 1, 'billing_lock_member' => 0]);
            echo Html::hiddenInput('customer_type', 'DCS', ['id' => 'customer_type']);
            echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
            echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
            ?>
            <?= Yii::$app->dropdown->paymentCycle($model, $form, 'tblmemberpaymentalias-union_code,tblmemberpaymentalias-bmc_code,customer_type,applicable_for,data_lock_bmc', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code'), FALSE, FALSE); ?>
        </div>

        <div class=" col-sm-3 form-group mt23">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
