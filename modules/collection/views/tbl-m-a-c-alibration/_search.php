<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMACAlibrationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-macalibration-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'BMCCode') ?>

    <?= $form->field($model, 'PPCode') ?>

    <?= $form->field($model, 'dtdate') ?>

    <?= $form->field($model, 'shift') ?>

    <?php // echo $form->field($model, 'CalibrationFat') ?>

    <?php // echo $form->field($model, 'CalibrationSnf') ?>

    <?php // echo $form->field($model, 'CalibrationWater') ?>

    <?php // echo $form->field($model, 'MilkType') ?>

    <?php // echo $form->field($model, 'updatedby') ?>

    <?php // echo $form->field($model, 'updateddate') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
