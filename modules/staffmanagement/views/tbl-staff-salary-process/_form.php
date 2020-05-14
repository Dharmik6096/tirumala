<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffSalaryProcess */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-staff-salary-process-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'salary_code')->textInput() ?>

    <?= $form->field($model, 'staff_member_code')->textInput() ?>

    <?= $form->field($model, 'actual_value')->textInput() ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <?= $form->field($model, 'disbursement_date')->textInput() ?>

    <?= $form->field($model, 'effective_working_days')->textInput() ?>

    <?= $form->field($model, 'lwp')->textInput() ?>

    <?= $form->field($model, 'month')->textInput() ?>

    <?= $form->field($model, 'designation_code')->textInput() ?>

    <?= $form->field($model, 'account_no')->textInput() ?>

    <?= $form->field($model, 'bank_code')->textInput() ?>

    <?= $form->field($model, 'branch_code')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'originating_org_code')->textInput() ?>

    <?= $form->field($model, 'originating_org_type')->textInput() ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

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
