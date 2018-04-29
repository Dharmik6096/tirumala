<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hardwareconfigutation\models\TblInterfacingDeviceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-interfacing-device-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'device_code') ?>

    <?= $form->field($model, 'baud_rate') ?>

    <?= $form->field($model, 'bit_rate') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'device_name') ?>

    <?php // echo $form->field($model, 'device_type') ?>

    <?php // echo $form->field($model, 'discard_char') ?>

    <?php // echo $form->field($model, 'end_char') ?>

    <?php // echo $form->field($model, 'flg_sentbox_entry') ?>

    <?php // echo $form->field($model, 'incoming_data_type') ?>

    <?php // echo $form->field($model, 'is_active')->checkbox() ?>

    <?php // echo $form->field($model, 'is_delete')->checkbox() ?>

    <?php // echo $form->field($model, 'is_snf')->checkbox() ?>

    <?php // echo $form->field($model, 'length') ?>

    <?php // echo $form->field($model, 'parity') ?>

    <?php // echo $form->field($model, 'reading_type') ?>

    <?php // echo $form->field($model, 'reg_expression') ?>

    <?php // echo $form->field($model, 'split_char') ?>

    <?php // echo $form->field($model, 'start_char') ?>

    <?php // echo $form->field($model, 'stop_bit') ?>

    <?php // echo $form->field($model, 'sync_status') ?>

    <?php // echo $form->field($model, 'sync_timestamp') ?>

    <?php // echo $form->field($model, 'tare') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'device_manufacturer_id') ?>

    <?php // echo $form->field($model, 'union_code') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
