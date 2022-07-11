<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\transporter\models\TblGateEntry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-gate-entry-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plant_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mcc_plant_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bmc_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dcs_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'route_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transporter_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vehicle_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_time_of_collection')->textInput() ?>

    <?= $form->field($model, 'shift_code')->textInput() ?>

    <?= $form->field($model, 'define_arrival_time')->textInput() ?>

    <?= $form->field($model, 'actual_arrival_time')->textInput() ?>

    <?= $form->field($model, 'grace_time')->textInput() ?>

    <?= $form->field($model, 'late_by_time')->textInput() ?>

    <?= $form->field($model, 'responsibility_code')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <?= $form->field($model, 'x_col1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col5')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
