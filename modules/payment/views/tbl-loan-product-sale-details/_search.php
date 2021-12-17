<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblLoanProductSaleDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-loan-product-sale-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sale_detail_code') ?>

    <?= $form->field($model, 'dcs_code') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'member_code') ?>

    <?= $form->field($model, 'product_code') ?>

    <?php // echo $form->field($model, 'sale_date_time') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'entry_type') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'send_status') ?>

    <?php // echo $form->field($model, 'response_datetime') ?>

    <?php // echo $form->field($model, 'picked_datetime') ?>

    <?php // echo $form->field($model, 'resp_desc') ?>

    <?php // echo $form->field($model, 'data_inserted_from') ?>

    <?php // echo $form->field($model, 'txfarmer_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
