<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblBmcCollectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-bmc-collection-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'milk_collection_code') ?>

    <?= $form->field($model, 'dcs_code') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'mobile_no') ?>

    <?= $form->field($model, 'milk_type_code') ?>

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

    <?php // echo $form->field($model, 'remarks') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
