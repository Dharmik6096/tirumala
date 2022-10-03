<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblVspPaymentDataConfigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-vsp-payment-data-config-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'vsp_payment_data_config_code') ?>

    <?= $form->field($model, 'date_time_of_collection') ?>

    <?= $form->field($model, 'shift_code') ?>

    <?php//$form->field($model, 'dcs_code') ?>

   <?php // $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'mcc_plant_code') ?>

    <?php // echo $form->field($model, 'plant_code') ?>

    <?php // echo $form->field($model, 'union_code') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <?php // echo $form->field($model, 'x_col1') ?>

    <?php // echo $form->field($model, 'x_col2') ?>

    <?php // echo $form->field($model, 'x_col3') ?>

    <?php // echo $form->field($model, 'x_col4') ?>

    <?php // echo $form->field($model, 'x_col5') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
