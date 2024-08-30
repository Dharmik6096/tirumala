<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\clienterp\models\TblClientErpApiLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-client-erp-api-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'log_id') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'plant_code') ?>

    <?= $form->field($model, 'mcc_plant_code') ?>

    <?= $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'end_point') ?>

    <?php // echo $form->field($model, 'request_url') ?>

    <?php // echo $form->field($model, 'request_desc') ?>

    <?php // echo $form->field($model, 'txn_type') ?>

    <?php // echo $form->field($model, 'date1') ?>

    <?php // echo $form->field($model, 'date2') ?>

    <?php // echo $form->field($model, 'desc1') ?>

    <?php // echo $form->field($model, 'desc2') ?>

    <?php // echo $form->field($model, 'request_header') ?>

    <?php // echo $form->field($model, 'request_payload') ?>

    <?php // echo $form->field($model, 'response_payload') ?>

    <?php // echo $form->field($model, 'request_timestamp') ?>

    <?php // echo $form->field($model, 'response_timestamp') ?>

    <?php // echo $form->field($model, 'status_code') ?>

    <?php // echo $form->field($model, 'status_type') ?>

    <?php // echo $form->field($model, 'status_response') ?>

    <?php // echo $form->field($model, 'status_message') ?>

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
