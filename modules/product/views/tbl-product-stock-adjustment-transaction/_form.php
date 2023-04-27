<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductStockAdjustmentTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-product-stock-adjustment-transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product_stock_adjustment_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plant_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mcc_plant_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bmc_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dcs_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sap_batch_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'old_value')->textInput() ?>

    <?= $form->field($model, 'new_value')->textInput() ?>

    <?= $form->field($model, 'final_value')->textInput() ?>

    <?= $form->field($model, 'adjustment_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transaction_date')->textInput() ?>

    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stock')->textInput() ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remarks')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_org_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

    <?= $form->field($model, 'x_col1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'x_col5')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
