<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplicationApprovalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-scheme-application-approval-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'app_approval_id') ?>

    <?= $form->field($model, 'application_id') ?>

    <?= $form->field($model, 'level') ?>

    <?= $form->field($model, 'user_code') ?>

    <?= $form->field($model, 'approval_mode') ?>

    <?php // echo $form->field($model, 'approved_value') ?>

    <?php // echo $form->field($model, 'application_status') ?>

    <?php // echo $form->field($model, 'status_date') ?>

    <?php // echo $form->field($model, 'status_by') ?>

    <?php // echo $form->field($model, 'status_remarks') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
