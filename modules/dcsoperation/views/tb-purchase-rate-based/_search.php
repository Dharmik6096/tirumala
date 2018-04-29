<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblPurchaseRateBasedSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-purchase-rate-based-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'rate_detail_id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'deleted_at') ?>

    <?= $form->field($model, 'end_range') ?>

    <?= $form->field($model, 'deduction_type') ?>

    <?php // echo $form->field($model, 'ref_type') ?>

    <?php // echo $form->field($model, 'fixed_point') ?>

    <?php // echo $form->field($model, 'value') ?>

    <?php // echo $form->field($model, 'kg_rate') ?>

    <?php // echo $form->field($model, 'flg_sentbox_entry') ?>

    <?php // echo $form->field($model, 'quality_param') ?>

    <?php // echo $form->field($model, 'milk_quality_type_code') ?>

    <?php // echo $form->field($model, 'start_range') ?>

    <?php // echo $form->field($model, 'sync_status') ?>

    <?php // echo $form->field($model, 'sync_timestamp') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'milk_type_code') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'purchase_rate_code') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
