<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkDispatch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-milk-dispatch-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dcs_code')->textInput() ?>

    <?= $form->field($model, 'bmc_code')->textInput() ?>

    <?= $form->field($model, 'milk_type_code')->textInput() ?>

    <?= $form->field($model, 'fat')->textInput() ?>

    <?= $form->field($model, 'snf')->textInput() ?>

    <?= $form->field($model, 'water')->textInput() ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'shift')->textInput() ?>

    <?= $form->field($model, 'date_time_of_collection')->textInput() ?>

    <?= $form->field($model, 'date_time_of_recieve')->textInput() ?>

    <?= $form->field($model, 'village_code')->textInput() ?>

    <?= $form->field($model, 'sample_no')->textInput() ?>

    <?= $form->field($model, 'type_of_data_receive')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
