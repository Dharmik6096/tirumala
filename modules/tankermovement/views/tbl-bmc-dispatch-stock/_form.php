<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcDispatchStock */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-bmc-dispatch-stock-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bmc_dispatch_stock_code')->textInput() ?>

    <?= $form->field($model, 'transaction_date')->textInput() ?>

    <?= $form->field($model, 'to_date')->textInput() ?>

    <?= $form->field($model, 'to_shift_code')->textInput() ?>

    <?= $form->field($model, 'qty_diff_type_code')->textInput() ?>

    <?= $form->field($model, 'milk_quality_type_code')->textInput() ?>

    <?= $form->field($model, 'milk_type_code')->textInput() ?>

    <?= $form->field($model, 'bmc_silos_info_code')->textInput() ?>

    <?= $form->field($model, 'opening_bal')->textInput() ?>

    <?= $form->field($model, 'closing_bal')->textInput() ?>

    <?= $form->field($model, 'purchase_qty')->textInput() ?>

    <?= $form->field($model, 'qty_diff')->textInput() ?>

    <?= $form->field($model, 'extra_qty')->textInput() ?>

    <?= $form->field($model, 'balance_qty')->textInput() ?>

    <?= $form->field($model, 'fat')->textInput() ?>

    <?= $form->field($model, 'snf')->textInput() ?>

    <?= $form->field($model, 'water')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'remarks')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput() ?>

    <?= $form->field($model, 'plant_code')->textInput() ?>

    <?= $form->field($model, 'mcc_plant_code')->textInput() ?>

    <?= $form->field($model, 'bmc_code')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <?= $form->field($model, 'originating_org_code')->textInput() ?>

    <?= $form->field($model, 'originating_org_type')->textInput() ?>

    <?= $form->field($model, 'x_col1')->textInput() ?>

    <?= $form->field($model, 'x_col2')->textInput() ?>

    <?= $form->field($model, 'x_col3')->textInput() ?>

    <?= $form->field($model, 'x_col4')->textInput() ?>

    <?= $form->field($model, 'x_col5')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
