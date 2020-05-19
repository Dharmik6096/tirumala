<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductDispatchTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-product-dispatch-transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dispatch_transaction_code')->textInput() ?>

    <?= $form->field($model, 'vendor_type')->textInput() ?>

    <?= $form->field($model, 'vendor_code')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput() ?>

    <?= $form->field($model, 'plant_code')->textInput() ?>

    <?= $form->field($model, 'mcc_plant_code')->textInput() ?>

    <?= $form->field($model, 'bmc_code')->textInput() ?>

    <?= $form->field($model, 'challan_no')->textInput() ?>

    <?= $form->field($model, 'dispatch_date')->textInput() ?>

    <?= $form->field($model, 'product_requisition_code')->textInput() ?>

    <?= $form->field($model, 'requisition_transaction_code')->textInput() ?>

    <?= $form->field($model, 'product_code')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'discount_amount')->textInput() ?>

    <?= $form->field($model, 'dispatch_qty')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'originating_org_code')->textInput() ?>

    <?= $form->field($model, 'originating_org_type')->textInput() ?>

    <?= $form->field($model, 'originating_type')->textInput() ?>

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
