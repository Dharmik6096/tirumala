<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplicationDisbursementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-scheme-application-disbursement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'disburse_id') ?>

    <?= $form->field($model, 'application_id') ?>

    <?= $form->field($model, 'disburse_date') ?>

    <?= $form->field($model, 'disburse_value') ?>

    <?= $form->field($model, 'disburse_by') ?>

    <?php // echo $form->field($model, 'payment_mode') ?>

    <?php // echo $form->field($model, 'bank_name') ?>

    <?php // echo $form->field($model, 'branch_name') ?>

    <?php // echo $form->field($model, 'party_name') ?>

    <?php // echo $form->field($model, 'party_relation') ?>

    <?php // echo $form->field($model, 'payment_ref_id') ?>

    <?php // echo $form->field($model, 'payment_detail') ?>

    <?php // echo $form->field($model, 'remarks') ?>

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
