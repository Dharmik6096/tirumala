<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMilkReceipt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-milk-receipt-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'milk_receipt_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'challan_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deleted_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flg_sentbox_entry')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'from_date')->textInput() ?>

    <?= $form->field($model, 'from_shift')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_active')->textInput() ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <?= $form->field($model, 'nos_of_can')->textInput() ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'rate_calculation_date_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receipt_acidity')->textInput() ?>

    <?= $form->field($model, 'receipt_clr')->textInput() ?>

    <?= $form->field($model, 'receipt_density')->textInput() ?>

    <?= $form->field($model, 'receipt_fat')->textInput() ?>

    <?= $form->field($model, 'receipt_freezing_point')->textInput() ?>

    <?= $form->field($model, 'receipt_lactose')->textInput() ?>

    <?= $form->field($model, 'receipt_local_type')->textInput() ?>

    <?= $form->field($model, 'receipt_org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receipt_protein')->textInput() ?>

    <?= $form->field($model, 'receipt_qty')->textInput() ?>

    <?= $form->field($model, 'receipt_snf')->textInput() ?>

    <?= $form->field($model, 'receipt_temp')->textInput() ?>

    <?= $form->field($model, 'receipt_water')->textInput() ?>

    <?= $form->field($model, 'sync_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sync_timestamp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'to_date')->textInput() ?>

    <?= $form->field($model, 'to_shift')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dcs_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deleted_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'milk_quality_type_code')->textInput() ?>

    <?= $form->field($model, 'milk_type')->textInput() ?>

    <?= $form->field($model, 'receipt_org_chilling_center')->textInput() ?>

    <?= $form->field($model, 'receipt_org_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_center_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
