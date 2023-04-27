<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductStockAdjustmentTransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-product-stock-adjustment-transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'product_stock_adjustment_transaction_code') ?>

    <?= $form->field($model, 'product_stock_adjustment_code') ?>

    <?= $form->field($model, 'union_code') ?>

    <?= $form->field($model, 'plant_code') ?>

    <?= $form->field($model, 'mcc_plant_code') ?>

    <?php // echo $form->field($model, 'bmc_code') ?>

    <?php // echo $form->field($model, 'dcs_code') ?>

    <?php // echo $form->field($model, 'product_code') ?>

    <?php // echo $form->field($model, 'sap_batch_no') ?>

    <?php // echo $form->field($model, 'old_value') ?>

    <?php // echo $form->field($model, 'new_value') ?>

    <?php // echo $form->field($model, 'final_value') ?>

    <?php // echo $form->field($model, 'adjustment_type') ?>

    <?php // echo $form->field($model, 'transaction_date') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'stock') ?>

    <?php // echo $form->field($model, 'qty') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'remarks') ?>

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
