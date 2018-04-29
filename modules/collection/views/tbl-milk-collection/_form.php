<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-milk-collection-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'milk_collection_code')->textInput() ?>

    <?= $form->field($model, 'member_code')->textInput() ?>

    <?= $form->field($model, 'dcs_code')->textInput() ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'mobile_no')->textInput() ?>

    <?= $form->field($model, 'milk_type_code')->textInput() ?>

    <?= $form->field($model, 'fat')->textInput() ?>

    <?= $form->field($model, 'snf')->textInput() ?>

    <?= $form->field($model, 'water')->textInput() ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'rtpl')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'auto_flag')->textInput() ?>

    <?= $form->field($model, 'shift')->textInput() ?>

    <?= $form->field($model, 'date_time_of_collection')->textInput() ?>

    <?= $form->field($model, 'date_time_of_recieve')->textInput() ?>

    <?= $form->field($model, 'village_code')->textInput() ?>

    <?= $form->field($model, 'sample_no')->textInput() ?>

    <?= $form->field($model, 'type_of_data_receive')->textInput() ?>

    <?= $form->field($model, 'rate_code')->textInput() ?>

    <?= $form->field($model, 'error_log')->textInput() ?>

    <?= $form->field($model, 'ack')->textInput() ?>

    <?= $form->field($model, 'soc_bmc_flag')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
