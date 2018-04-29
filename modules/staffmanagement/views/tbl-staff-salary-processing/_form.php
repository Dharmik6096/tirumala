<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffSalaryProcessing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-staff-salary-processing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'month')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disbursement_date')->textInput() ?>

    <?= $form->field($model, 'effective_working_days')->textInput() ?>

    <?= $form->field($model, 'flg_sentbox_entry')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <?= $form->field($model, 'lwp')->textInput() ?>

    <?= $form->field($model, 'sync_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sync_timestamp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_of_head')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_center_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'staff_member_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'designation_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dcs_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salary_head_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
