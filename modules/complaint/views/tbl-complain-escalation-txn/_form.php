<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\complaint\models\TblComplainEscalationTxn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-complain-escalation-txn-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'complain_escalation_code')->textInput() ?>

    <?= $form->field($model, 'user_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'escalation_time')->textInput() ?>

    <?= $form->field($model, 'level')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
