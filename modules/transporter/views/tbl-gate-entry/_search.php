<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\transporter\models\TblGateEntrySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-gate-entry-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'gate_entry_code') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'plant_code') ?>

    <?= $form->field($model, 'mcc_plant_code') ?>

    <?= $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'dcs_code') ?>

    <?php // echo $form->field($model, 'route_code') ?>

    <?php // echo $form->field($model, 'transporter_code') ?>

    <?php // echo $form->field($model, 'vehicle_code') ?>

    <?php // echo $form->field($model, 'date_time_of_collection') ?>

    <?php // echo $form->field($model, 'shift_code') ?>

    <?php // echo $form->field($model, 'define_arrival_time') ?>

    <?php // echo $form->field($model, 'actual_arrival_time') ?>

    <?php // echo $form->field($model, 'grace_time') ?>

    <?php // echo $form->field($model, 'late_by_time') ?>

    <?php // echo $form->field($model, 'responsibility_code') ?>

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
