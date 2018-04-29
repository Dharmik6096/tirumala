<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblSocietyVendor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-society-vendor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'society_vendor_code')->textInput() ?>

    <?= $form->field($model, 'society_code')->textInput() ?>

    <?= $form->field($model, 'vender_code')->textInput() ?>

    <?= $form->field($model, 'is_active')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
