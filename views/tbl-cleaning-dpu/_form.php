<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblCleaningDpu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-cleaning-dpu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'VillageId')->textInput() ?>

    <?= $form->field($model, 'Dtdate')->textInput() ?>

    <?= $form->field($model, 'Shift')->textInput() ?>

    <?= $form->field($model, 'c1Date')->textInput() ?>

    <?= $form->field($model, 'c1cycle')->textInput() ?>

    <?= $form->field($model, 'c1testing')->textInput() ?>

    <?= $form->field($model, 'c2Date')->textInput() ?>

    <?= $form->field($model, 'c2cycle')->textInput() ?>

    <?= $form->field($model, 'c2testing')->textInput() ?>

    <?= $form->field($model, 'c3Date')->textInput() ?>

    <?= $form->field($model, 'c3cycle')->textInput() ?>

    <?= $form->field($model, 'c3testing')->textInput() ?>

    <?= $form->field($model, 'c4Date')->textInput() ?>

    <?= $form->field($model, 'c4cycle')->textInput() ?>

    <?= $form->field($model, 'c4testing')->textInput() ?>

    <?= $form->field($model, 'c5Date')->textInput() ?>

    <?= $form->field($model, 'c5cycle')->textInput() ?>

    <?= $form->field($model, 'c5testing')->textInput() ?>

    <?= $form->field($model, 'Counter')->textInput() ?>

    <?= $form->field($model, 'CreatedDate')->textInput() ?>

    <?= $form->field($model, 'ModifyDate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
