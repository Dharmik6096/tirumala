<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblLoanProductSaleDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-loan-product-sale-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dcs_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'union_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_code')->textInput() ?>

    <?= $form->field($model, 'sale_date_time')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'entry_type')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'send_status')->textInput() ?>

    <?= $form->field($model, 'response_datetime')->textInput() ?>

    <?= $form->field($model, 'picked_datetime')->textInput() ?>

    <?= $form->field($model, 'resp_desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txfarmer_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
