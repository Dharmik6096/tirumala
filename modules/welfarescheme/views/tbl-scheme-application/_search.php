<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplicationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-scheme-application-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'application_id') ?>

    <?= $form->field($model, 'scheme_id') ?>

    <?= $form->field($model, 'member_code') ?>

    <?= $form->field($model, 'application_date') ?>

    <?= $form->field($model, 'min_pouring_day') ?>

    <?php // echo $form->field($model, 'min_pouring_qty') ?>

    <?php // echo $form->field($model, 'actual_pouring_day') ?>

    <?php // echo $form->field($model, 'actual_pouring_qty') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'scheme_value') ?>

    <?php // echo $form->field($model, 'approved_value') ?>

    <?php // echo $form->field($model, 'application_status') ?>

    <?php // echo $form->field($model, 'status_date') ?>

    <?php // echo $form->field($model, 'status_by') ?>

    <?php // echo $form->field($model, 'status_remarks') ?>

    <?php // echo $form->field($model, 'dcs_code') ?>

    <?php // echo $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'mcc_plant_code') ?>

    <?php // echo $form->field($model, 'plant_code') ?>

    <?php // echo $form->field($model, 'union_code') ?>

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
