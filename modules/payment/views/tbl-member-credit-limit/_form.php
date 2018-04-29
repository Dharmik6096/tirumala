<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblMemberCreditLimit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-member-credit-limit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'balance')->textInput() ?>

    <?= $form->field($model, 'member_code')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput() ?>

    <?= $form->field($model, 'dcs_code')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
