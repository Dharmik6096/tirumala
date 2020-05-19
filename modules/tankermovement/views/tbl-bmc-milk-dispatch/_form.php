<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcMilkDispatch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-bmc-milk-dispatch-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bmc_milk_dispatch_code')->textInput() ?>

    <?= $form->field($model, 'challan_no')->textInput() ?>

    <?= $form->field($model, 'transaction_date')->textInput() ?>

    <?= $form->field($model, 'from_date')->textInput() ?>

    <?= $form->field($model, 'from_shift_code')->textInput() ?>

    <?= $form->field($model, 'to_date')->textInput() ?>

    <?= $form->field($model, 'to_shift_code')->textInput() ?>

    <?= $form->field($model, 'destination_type')->textInput() ?>

    <?= $form->field($model, 'destination_code')->textInput() ?>

    <?= $form->field($model, 'vehicle_code')->textInput() ?>

    <?= $form->field($model, 'trip_code')->textInput() ?>

    <?= $form->field($model, 'driver_name')->textInput() ?>

    <?= $form->field($model, 'driver_contact_no')->textInput() ?>

    <?= $form->field($model, 'authorizer_name')->textInput() ?>

    <?= $form->field($model, 'vehicle_in_time')->textInput() ?>

    <?= $form->field($model, 'vehicle_out_time')->textInput() ?>

    <?= $form->field($model, 'remarks')->textInput() ?>

    <?= $form->field($model, 'gross_weight')->textInput() ?>

    <?= $form->field($model, 'tare_weight')->textInput() ?>

    <?= $form->field($model, 'is_last_destination')->textInput() ?>

    <?= $form->field($model, 'purchase_rate_code')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput() ?>

    <?= $form->field($model, 'plant_code')->textInput() ?>

    <?= $form->field($model, 'mcc_plant_code')->textInput() ?>

    <?= $form->field($model, 'bmc_code')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <?= $form->field($model, 'originating_org_code')->textInput() ?>

    <?= $form->field($model, 'originating_org_type')->textInput() ?>

    <?= $form->field($model, 'x_col1')->textInput() ?>

    <?= $form->field($model, 'x_col2')->textInput() ?>

    <?= $form->field($model, 'x_col3')->textInput() ?>

    <?= $form->field($model, 'x_col4')->textInput() ?>

    <?= $form->field($model, 'x_col5')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
