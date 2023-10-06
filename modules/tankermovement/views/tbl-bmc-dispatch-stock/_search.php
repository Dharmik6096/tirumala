<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcDispatchStockSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-bmc-dispatch-stock-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bmc_dispatch_stock_code') ?>

    <?= $form->field($model, 'transaction_date') ?>

    <?= $form->field($model, 'to_date') ?>

    <?= $form->field($model, 'to_shift_code') ?>

    <?= $form->field($model, 'qty_diff_type_code') ?>

    <?php // echo $form->field($model, 'milk_quality_type_code') ?>

    <?php // echo $form->field($model, 'milk_type_code') ?>

    <?php // echo $form->field($model, 'bmc_silos_info_code') ?>

    <?php // echo $form->field($model, 'opening_bal') ?>

    <?php // echo $form->field($model, 'closing_bal') ?>

    <?php // echo $form->field($model, 'purchase_qty') ?>

    <?php // echo $form->field($model, 'qty_diff') ?>

    <?php // echo $form->field($model, 'extra_qty') ?>

    <?php // echo $form->field($model, 'balance_qty') ?>

    <?php // echo $form->field($model, 'fat') ?>

    <?php // echo $form->field($model, 'snf') ?>

    <?php // echo $form->field($model, 'water') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'union_code') ?>

    <?php // echo $form->field($model, 'plant_code') ?>

    <?php // echo $form->field($model, 'mcc_plant_code') ?>

    <?php // echo $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'originating_type') ?>

    <?php // echo $form->field($model, 'originating_org_code') ?>

    <?php // echo $form->field($model, 'originating_org_type') ?>

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
