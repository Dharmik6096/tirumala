<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductDispatch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-product-dispatch-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'challan_no')->textInput() ?>

    <?= $form->field($model, 'challan_date')->textInput() ?>

    <?= $form->field($model, 'challan_verified')->textInput() ?>

    <?= $form->field($model, 'reference_no')->textInput() ?>

    <?= $form->field($model, 'dispatch_date')->textInput() ?>

    <?= $form->field($model, 'vendor_type')->textInput() ?>

    <?= $form->field($model, 'vendor_code')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput() ?>

    <?= $form->field($model, 'plant_code')->textInput() ?>

    <?= $form->field($model, 'mcc_plant_code')->textInput() ?>

    <?= $form->field($model, 'bmc_code')->textInput() ?>

    <?= $form->field($model, 'dcs_code')->textInput() ?>

    <?= $form->field($model, 'route_code')->textInput() ?>

    <?= $form->field($model, 'vehicle_no')->textInput() ?>

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
