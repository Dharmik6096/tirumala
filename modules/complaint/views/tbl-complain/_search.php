<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\complaint\models\TblComplainSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-complain-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'complain_code') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'plant_code') ?>

    <?= $form->field($model, 'mcc_plant_code') ?>

    <?= $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'dcs_code') ?>

    <?php // echo $form->field($model, 'location_type') ?>

    <?php // echo $form->field($model, 'complain_for') ?>

    <?php // echo $form->field($model, 'complain_type_code') ?>

    <?php // echo $form->field($model, 'complain_datetime') ?>

    <?php // echo $form->field($model, 'complain_assignment_datetime') ?>

    <?php // echo $form->field($model, 'asset_code') ?>

    <?php // echo $form->field($model, 'complain_problem_code') ?>

    <?php // echo $form->field($model, 'serial_number') ?>

    <?php // echo $form->field($model, 'new_serial_no') ?>

    <?php // echo $form->field($model, 'contact_person') ?>

    <?php // echo $form->field($model, 'mobile_no') ?>

    <?php // echo $form->field($model, 'complain_status') ?>

    <?php // echo $form->field($model, 'complain_status_datetime') ?>

    <?php // echo $form->field($model, 'user_code') ?>

    <?php // echo $form->field($model, 'physical_damage') ?>

    <?php // echo $form->field($model, 'spare_required') ?>

    <?php // echo $form->field($model, 'affects_data') ?>

    <?php // echo $form->field($model, 'lat_long') ?>

    <?php // echo $form->field($model, 'location_details') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'entry_type') ?>

    <?php // echo $form->field($model, 'resolved_status') ?>

    <?php // echo $form->field($model, 'resolved_datetime') ?>

    <?php // echo $form->field($model, 'resolved_remarks') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
