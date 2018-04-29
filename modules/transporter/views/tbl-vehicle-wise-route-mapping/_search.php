<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\transporter\models\TblVehicleWiseRouteMappingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-vehicle-wise-route-mapping-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'vehicle_wise_route_code') ?>

    <?= $form->field($model, 'vehicle_code') ?>

    <?= $form->field($model, 'route_code') ?>

    <?= $form->field($model, 'wef_date') ?>

    <?= $form->field($model, 'is_active') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
