<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\DpuStationDetailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dpu-station-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'station_code') ?>

    <?= $form->field($model, 'flag_key') ?>

    <?= $form->field($model, 'flag_value') ?>

    <?= $form->field($model, 'other_value') ?>

    <?php // echo $form->field($model, 'vendor_code') ?>

    <?php // echo $form->field($model, 'company_code') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'transferred_datetime') ?>

    <?php // echo $form->field($model, 'transferred_by') ?>

    <?php // echo $form->field($model, 'download_datetime') ?>

    <?php // echo $form->field($model, 'download_by') ?>

    <?php // echo $form->field($model, 'ref_code') ?>

    <?php // echo $form->field($model, 'dpu_header') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
