<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionTempSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-milk-collection-temp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'milk_collection_code') ?>

    <?= $form->field($model, 'member_code') ?>

    <?= $form->field($model, 'dcs_code') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'mobile_no') ?>

    <?php // echo $form->field($model, 'milk_type_code') ?>

    <?php // echo $form->field($model, 'fat') ?>

    <?php // echo $form->field($model, 'snf') ?>

    <?php // echo $form->field($model, 'water') ?>

    <?php // echo $form->field($model, 'qty') ?>

    <?php // echo $form->field($model, 'rtpl') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'auto_flag') ?>

    <?php // echo $form->field($model, 'shift') ?>

    <?php // echo $form->field($model, 'date_time_of_collection') ?>

    <?php // echo $form->field($model, 'date_time_of_recieve') ?>

    <?php // echo $form->field($model, 'village_code') ?>

    <?php // echo $form->field($model, 'sample_no') ?>

    <?php // echo $form->field($model, 'type_of_data_receive') ?>

    <?php // echo $form->field($model, 'rate_code') ?>

    <?php // echo $form->field($model, 'error_log') ?>

    <?php // echo $form->field($model, 'ack') ?>

    <?php // echo $form->field($model, 'soc_bmc_flag') ?>

    <?php // echo $form->field($model, 'dt_date') ?>

    <?php // echo $form->field($model, 'sms_status') ?>

    <?php // echo $form->field($model, 'sms_msgid') ?>

    <?php // echo $form->field($model, 'sms_mobile') ?>

    <?php // echo $form->field($model, 'sms_errorlog') ?>

    <?php // echo $form->field($model, 'sms_timestamp') ?>

    <?php // echo $form->field($model, 'data_post_status') ?>

    <?php // echo $form->field($model, 'clr') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'qty_mode') ?>

    <?php // echo $form->field($model, 'qlty_time') ?>

    <?php // echo $form->field($model, 'qty_time') ?>

    <?php // echo $form->field($model, 'no_of_can') ?>

    <?php // echo $form->field($model, 'milk_quality_type_code') ?>

    <?php // echo $form->field($model, 'qlty_auto') ?>

    <?php // echo $form->field($model, 'qty_auto') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'route_code') ?>

    <?php // echo $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'converted_qty') ?>

    <?php // echo $form->field($model, 'is_approved') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
