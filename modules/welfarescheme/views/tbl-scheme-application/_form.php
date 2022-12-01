<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplication */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-scheme-application-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'scheme_id')->textInput() ?>

    <?= $form->field($model, 'member_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'application_date')->textInput() ?>

    <?= $form->field($model, 'min_pouring_day')->textInput() ?>

    <?= $form->field($model, 'min_pouring_qty')->textInput() ?>

    <?= $form->field($model, 'actual_pouring_day')->textInput() ?>

    <?= $form->field($model, 'actual_pouring_qty')->textInput() ?>

    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'scheme_value')->textInput() ?>

    <?= $form->field($model, 'approved_value')->textInput() ?>

    <?= $form->field($model, 'application_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_date')->textInput() ?>

    <?= $form->field($model, 'status_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_remarks')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dcs_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bmc_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mcc_plant_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plant_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <?= $form->field($model, 'originating_org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_type')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
