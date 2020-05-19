<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\staffmanagement\models\TblStaffSalaryProcessSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-staff-salary-process-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'salary_code') ?>

    <?= $form->field($model, 'staff_member_code') ?>

    <?= $form->field($model, 'actual_value') ?>

    <?= $form->field($model, 'value') ?>

    <?= $form->field($model, 'disbursement_date') ?>

    <?php // echo $form->field($model, 'effective_working_days') ?>

    <?php // echo $form->field($model, 'lwp') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'designation_code') ?>

    <?php // echo $form->field($model, 'account_no') ?>

    <?php // echo $form->field($model, 'bank_code') ?>

    <?php // echo $form->field($model, 'branch_code') ?>

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
