<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcMilkDispatchSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-bmc-milk-dispatch-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bmc_milk_dispatch_code') ?>

    <?= $form->field($model, 'challan_no') ?>

    <?= $form->field($model, 'transaction_date') ?>

    <?= $form->field($model, 'from_date') ?>

    <?= $form->field($model, 'from_shift_code') ?>

    <?php // echo $form->field($model, 'to_date') ?>

    <?php // echo $form->field($model, 'to_shift_code') ?>

    <?php // echo $form->field($model, 'destination_type') ?>

    <?php // echo $form->field($model, 'destination_code') ?>

    <?php // echo $form->field($model, 'vehicle_code') ?>

    <?php // echo $form->field($model, 'trip_code') ?>

    <?php // echo $form->field($model, 'driver_name') ?>

    <?php // echo $form->field($model, 'driver_contact_no') ?>

    <?php // echo $form->field($model, 'authorizer_name') ?>

    <?php // echo $form->field($model, 'vehicle_in_time') ?>

    <?php // echo $form->field($model, 'vehicle_out_time') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'gross_weight') ?>

    <?php // echo $form->field($model, 'tare_weight') ?>

    <?php // echo $form->field($model, 'is_last_destination') ?>

    <?php // echo $form->field($model, 'purchase_rate_code') ?>

    <?php // echo $form->field($model, 'union_code') ?>

    <?php // echo $form->field($model, 'plant_code') ?>

    <?php // echo $form->field($model, 'mcc_plant_code') ?>

    <?php // echo $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

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
